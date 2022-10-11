<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;

class Packages extends AbstractModel
{
    public $timestamps = false;
    use HasTranslations, UrlUnique;

    public $translatable = ['title'];

    private static function cacheKey()
    {
        return 'packages';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }


    public static function adminList()
    {
        return self::select('id', 'title', 'package_price')->get();
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
        $model->title_company = 0;
        $model->check_city = 0;
        $model->show_in_home = 0;
        $model->stickers = 0;
        if (!empty($inputs['title_company'])) {
            $model->title_company = $inputs['title_company'];
        }
        if (!empty($inputs['check_city'])) {
            $model->check_city = $inputs['check_city'];
        }
        if (!empty($inputs['show_in_home'])) {
            $model->show_in_home = $inputs['show_in_home'];
        }
        if (!empty($inputs['stickers'])) {
            $model->stickers = $inputs['stickers'];
        }

        $model->count_images = $inputs['count_images'];
        $model->package_price = $inputs['package_price'];
        $model->count_products = $inputs['count_products'];
        merge_model($inputs, $model, ['title']);

        return $model->save();
    }


    public static function deleteItem($model)
    {
        self::clearCaches();

        return $model->delete();
    }
}
