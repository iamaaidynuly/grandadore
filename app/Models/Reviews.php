<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;

class Reviews extends AbstractModel
{
    use HasTranslations, UrlUnique;

    private static function cacheKey()
    {
        return 'reviews';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }

    public static function getReviews()
    {
        return Cache::rememberForever(self::cacheKey(), function () {
            return self::select('message', 'rating', 'created_at')->where('moderated', 1)->sort()->get();
        });
    }

    public static function adminList()
    {
        return self::select('id', 'message', 'moderated', 'rating', 'created_at')->sort()->get();
    }


    public function scopeSort($q)
    {
        return $q->orderBy('created_at', 'desc');
    }

    public static function action($model, $inputs)
    {
        self::clearCaches();
        $model = new self;
        $model->rating = $inputs['rating'];
        merge_model($inputs, $model, ['message']);

        if ($model->save()) {
            ReviewItem::action($model->id, $inputs);
        }

        return 'true';
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
