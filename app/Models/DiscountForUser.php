<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;

class DiscountForUser extends AbstractModel
{
    use HasTranslations, UrlUnique;

    public $timestamps = false;
    public $translatable = ['title'];
    protected $table = 'discount_for_user';

    private static function cacheKey()
    {
        return 'discountForUser';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }

    public static function getNews()
    {
        return Cache::rememberForever(self::cacheKey(), function () {
            return self::select('title', 'discount')->get();
        });
    }

    public static function adminList()
    {
        return self::select('id', 'title', 'discount')->get();
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

        merge_model($inputs, $model, ['title']);
        $model->discount = $inputs['discount'];

        return $model->save();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }


    public static function deleteItem($model)
    {
        self::clearCaches();

        return $model->delete();
    }
}
