<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;

class OneTimePayment extends AbstractModel
{
    public $timestamps = false;
    use HasTranslations, UrlUnique;

    public $translatable = ['title'];

    private static function cacheKey()
    {
        return 'OneTimePayment';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }


    public static function adminList()
    {
        return self::select('id', 'title', 'price')->get();
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
        $model->price = $inputs['price'];
        merge_model($inputs, $model, ['title']);

        return $model->save();
    }


    public static function deleteItem($model)
    {
        self::clearCaches();

        return $model->delete();
    }
}
