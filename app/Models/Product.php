<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Product extends AbstractModel
{
    use HasTranslations, Sortable, UrlUnique;

    public $translatable = ['title', 'short', 'description', 'seo_title', 'seo_description', 'seo_keywords'];

    public static function adminList()
    {
        return self::sort()->get();
    }

    public static function getItem($id)
    {
        return self::with('options')->findOrFail($id);
    }

    public static function getHomeProducts()
    {
        return self::where(['on_home' => 1, 'active' => 1])->whereHas('catalogue', function ($q) {
            $q->where('active', 1);
        })->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->inRandomOrder()->limit(4)->get()->sortByDesc('id')->sortBy('sort')->values();
    }

    public static function getItemSite($url)
    {
        return self::where(['url' => $url, 'active' => 1])->whereHas('catalogue', function ($q) {
            $q->where('active', 1);
        })->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->firstOrFail();
    }

    public static function getRandom($except)
    {
        return self::where('id', '<>', $except)->where('active', 1)->whereHas('catalogue', function ($q) {
            $q->where('active', 1);
        })->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->inRandomOrder()->limit(6)->get();
    }

    public static function getFromArray($ids)
    {
        return self::where('active', 1)->whereHas('catalogue', function ($q) {
            $q->where('active', 1);
        })->whereIn('id', $ids)->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->get();
    }


    public static function search($q)
    {
        $q = preg_quote(mb_strtolower(str_replace(['\\', '"'], ['\\\\', '\\"'], $q)));

        return self::where('active', '1')->whereHas('catalogue', function ($q) {
            $q->where('active', 1);
        })->where(DB::raw('LOWER(`title`)'), 'REGEXP', ':"[^"]*(' . $q . ')[^"]*"')->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->sort()->get();
    }

    public static function action($model, $inputs)
    {
        if (empty($model)) {
            $model = new self;
            $ignore = false;
        } else {
            $ignore = $model->id;
        }
        $model['active'] = (int)!empty($inputs['active']);
        $model['on_home'] = (int)!empty($inputs['on_home']);
        $model['show_image'] = (int)!empty($inputs['show_image']);
        if (!empty($inputs['generate_url'])) {
            $url = self::url_unique($inputs['generated_url'], $ignore);
        } else {
            $url = $inputs['url'];
        }
        $model['url'] = $url;
        merge_model($inputs, $model, ['title', 'short', 'description', 'price', 'sale', 'catalogue_id', 'seo_title', 'seo_description', 'seo_keywords']);
        $resizes = [
            [
                'width' => 512,
                'height' => 288,
                'upsize' => true,
            ]
        ];
        if ($image = upload_image('image', 'u/products/', $resizes, ($ignore && !empty($model->image)) ? $model->image : false)) $model->image = $image;
        $result = $model->save();
        $model->options()->sync(ProductOption::getIds($inputs['options'] ?? []));

        return $result;
    }

    public static function deleteItem($model)
    {
        if ($model->image) {
            File::delete(public_path('u/products/' . $model->image));
        }
        $model->options()->detach();

        return $model->delete();
    }

    public static function getMenu()
    {
        $catalogues = Catalogue::forProducts();

        return self::where('active', 1)->whereHas('catalogue', function ($q) {
            $q->where('active', 1);
        })->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->orderByRaw('field(`catalogue_id`, ' . implode(', ', $catalogues) . ')')->sort()->paginate(settings('pagination', 40));
    }

    public static function getCatalogueProducts($id)
    {
        return self::where(['catalogue_id' => $id, 'active' => 1])->with(['options' => function ($q) {
            $q->where('active', 1);
        }])->sort()->paginate(settings('pagination', 40));
    }

    public function options()
    {
        return $this->belongsToMany('App\Models\ProductOption', 'options_to_products', 'product_id', 'option_id')->sort();
    }

    public function catalogue()
    {
        return $this->belongsTo('App\Models\Catalogue', 'catalogue_id', 'id');
    }

}
