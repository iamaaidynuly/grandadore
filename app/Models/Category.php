<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class Category extends AbstractModel
{
    public $translatable = ['name', 'seo_title', 'seo_description', 'seo_keywords'];
    use HasTranslations, UrlUnique, Sortable;

    protected $table = 'categories';

    public $parentsCount = 0;

    public $parents = [];

    public $demo_id;

    public function parents($id)
    {
        $this->demo_id = $id;
        $i = true;
        while ($i == true) {
            $parentModel = $this->where(['id' => $this->demo_id])->with(['parent'])->first();
            if ($parentModel) {
                $this->parents[] = $parentModel;
                $this->parents($parentModel->parent_id);
            } else {
                $i = false;
            }
        }

        return array_reverse($this->parents);
    }

    public function onlyParents()
    {
        return $this->parents($this->parent_id);
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent_id');
    }

    public function children()
    {
//        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->has('items', '>', 0)->with('children')->orderBy('sort', 'asc');
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->with('children')->orderBy('sort', 'asc');
    }

    public function getItemsCountAttribute()
    {

        $count = 0;
        if (!empty($this->childrens) && count($this->childrens)) {

            foreach ($this->childrens as $child) {

                if (!empty($child->children) && count($child->children)) {
                    foreach ($child->children as $last_child) {
                        if (!empty($last_child->items)) {
                            $count += count($last_child->items);
                        }
                    }
                }
                if (!empty($child->items) && count($child->items)) {
                    $count += count($child->items);
                }
            }
        }
        if (!empty($this->items) && count($this->items)) {
            $count += count($this->items);
        }

        return $count;
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->with(['children' => function ($q) {
            $q->with('items', 'filters');
        }, 'filters', 'items'])->orderBy('sort', 'asc');
    }

    public function nestedChildren()
    {
        return $this->hasMany(self::class, 'parent_id')->with(['children' => function (HasMany $query) {
            $query->with('children', 'items');
        }, 'items']);
    }

    public function items()
    {
        return $this->belongsToMany('App\Models\Items', 'items_categories', 'category_id', 'item_id')->where('moderated', 1)->where('active', 1);
    }

    public function createCategory($request)
    {
        merge_model($request, $this, ['name', 'seo_title', 'seo_description', 'seo_keywords']);
        $this->deep = $this->getCategoryDeep($request->input('parent_id'));

        $this->parent_id = $request->input('parent_id');

//        $this->url = $urlIsset ? randomString(2) . '-' . $urlIsset->url : $parsedUrl;
        $this->url = self::url_unique(to_url($request->input('name')['ru']));
        if ($request->hasFile('image')) {
            $allowedExt = ['png', 'jpg', 'jpeg', 'gif'];
            $image = $request->file('image');
            $name = randomImageName(10, $image->extension());
            if (in_array($image->extension(), $allowedExt) && $image->isValid()) {
                $img = Image::make($image->getRealPath());
                $img->resize(280, 280, function ($constraint) {
                    $constraint->upsize();
                })->save(public_path('/u/categories') . '/' . $name);
            }
            $this->image = $name;
        }
        $this->footer = 0;
        if (!empty($request->footer)) {
            $this->footer = $request->footer;
        }

        return $this->save();
    }

    public static function updateCategory($id, $request)
    {

        $categoryModel = self::where(['id' => $id])->first();
        merge_model($request, $categoryModel, ['name', 'seo_title', 'seo_description', 'seo_keywords']);
        $categoryModel->url = self::url_unique(to_url($request->input('name')['ru']), $id);;
        if ($request->hasFile('image')) {
            $allowedExt = ['png', 'jpg', 'jpeg', 'gif'];
            $image = $request->file('image');
            $name = randomImageName(10, $image->extension());
            if (in_array($image->extension(), $allowedExt) && $image->isValid()) {
                File::delete(public_path('u/categories/') . $categoryModel->image);
                $img = Image::make($image->getRealPath());
                $img->resize(280, 280, function ($constraint) {
                    $constraint->upsize();
                })->save(public_path('u/categories/') . $name);
            }
            $categoryModel->image = $name;
        }
        $categoryModel->footer = 0;
        if (!empty($request->footer)) {
            $categoryModel->footer = $request->footer;
        }

        return ['status' => $categoryModel->save(), 'parent_id' => $categoryModel->parent_id];
    }

    public function name()
    {
        $lang = 'name_' . LaravelLocalization::getCurrentLocale();

        return $this->$lang;
    }


    public function getCategoryDeep($parent_id)
    {
        $parent = Category::where(['id' => $parent_id])->first();
        if (!is_null($parent)) {
            $this->parentsCount++;
            $this->getCategoryDeep($parent->parent_id);
        } else {
            return $this->parentsCount;
        }

        return $this->parentsCount;
    }


    public function filters()
    {
        return $this->belongsToMany('App\Models\Filter', 'filter_category')->with('criteria');
    }


    public function getImage($parents)
    {
        if ($this->image) {
            return $this->image;
        } else {
            $parent = $parents->filter(function ($parent) {
                return $parent->deep == 1;
            });

            return dump($parent->image);
            if ($parent->image)
                return $parent->image;
        }

        return null;
    }

    public function adminFilters()
    {
        return $this->hasMany('App\Models\Filter', 'cid', 'id')->with(['criteria']);
    }
}
