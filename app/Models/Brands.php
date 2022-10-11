<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class Brands extends AbstractModel
{
    use HasTranslations, UrlUnique;

    public $translatable = ['title', 'description', 'seo_title', 'seo_description', 'seo_keywords'];

    private static function cacheKey()
    {
        return 'brands';
    }

    private static function cacheKeyHome()
    {
        return 'home_brands';
    }

    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
        Cache::forget(self::cacheKeyHome());
    }

    public static function getBrands()
    {
//        return Cache::rememberForever(self::cacheKey(), function () {
            return self::select('title', 'created_at', 'image', 'url')->where('active', 1)->sort()->get();
//        });
    }

    public static function adminList()
    {
        return self::select('id', 'title', 'active', 'created_at')->sort()->get();
    }

    public static function homeList()
    {
        return Cache::rememberForever(self::cacheKeyHome(), function () {
            return self::select('title', 'created_at', 'image', 'logo_image', 'url')->where('active', 1)->sort()->limit(16)->get();
        });
    }

    public function scopeSort($q)
    {
        return $q->orderBy('created_at', 'desc');
    }

    public static function action($model, $inputs)
    {

        self::clearCaches();
        if (empty($model)) {
            $model = new self;
            $action = 'add';
            $ignore = false;
        } else {
            $action = 'edit';
            $ignore = $model->id;
        }
        $model['active'] = (int)!empty($inputs['active']);
        if (!empty($inputs['generate_url'])) {
            $url = self::url_unique($inputs['generated_url'], $ignore);
        } else {
            $url = $inputs['url'];
        }
        $model['url'] = $url;
        merge_model($inputs, $model, ['title', 'description', 'seo_title', 'seo_description', 'seo_keywords']);
        $resizes = [
            [
                'width' => 754,
                'height' => 734,
                'method' => 'resize'
            ]
        ];
        if ($image = upload_image('image', 'u/brands/', $resizes, ($action == 'edit' && !empty($model->image)) ? $model->image : false)) $model->image = $image;

        $resizes_logo = [
            [
                'width' => 220,
                'height' => 90,
            ]
        ];
        if ($logo_image = upload_image('logo_image', 'u/brands/', $resizes_logo, ($action == 'edit' && !empty($model->logo_image)) ? $model->logo_image : false)) $model->logo_image = $logo_image;


        return $model->save();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function getItemSite($url)
    {
        return self::where(['url' => $url, 'active' => 1])->firstOrFail();
    }

    public function items()
    {
        return $this->hasMany('App\Models\BrandsItems', 'item_id')->with('items');
    }

    public static function deleteItem($model)
    {
        self::clearCaches();
        $path = public_path('u/brands/');
        if (!empty($model->image)) File::delete($path . $model->image);
        if (!empty($model->logo_image)) File::delete($path . $model->logo_image);
        Gallery::clear('brands_item', $model->id);
        VideoGallery::clear('brands_item', $model->id);

        return $model->delete();
    }
}
