<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use Illuminate\Support\Facades\Cache;

class Delivery extends AbstractModel
{
    use HasTranslations, Sortable;

    public $timestamps = false;
    protected $table = 'delivery';
    public $translatable = ['title'];
    protected $sortableDesc = false;

    private static function cacheKey()
    {
        return 'delivery';
    }

    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }

    public static function adminList()
    {
        return self::sort()->get();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function action($model, $inputs)
    {
        self::clearCaches();
        if (empty($model)) {
            $model = new self;
            $model['sort'] = $model->sortValue();
        }

        $model['active'] = (int)!empty($inputs['active']);

        merge_model($inputs, $model, ['title', 'price']);

        return $model->save();
    }

    public static function deleteItem($item)
    {
        self::clearCaches();

        return $item->delete();
    }

    public static function siteList()
    {
        return Cache::rememberForever(self::cacheKey(), function () {
            return self::where('active', 1)->sort()->get();
        });
    }

    public static function getItemSite($id)
    {
        return self::where(['id' => $id, 'active' => 1])->firstOrFail();
    }

}
