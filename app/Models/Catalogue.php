<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;

class Catalogue extends AbstractModel
{
    use HasTranslations, Sortable, UrlUnique;

    protected $table = 'catalogue';
    protected $sortableDesc = false;
    public $translatable = ['title', 'seo_title', 'seo_description', 'seo_keywords'];

    public function scopeSort($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    private static function cacheKey()
    {
        return 'categories';
    }

    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
        HomeCatalogue::clearCaches();
    }

    public static function adminList()
    {
        return self::sort()->withCount('products')->get();
    }

    public static function siteList()
    {
        return Cache::rememberForever(self::cacheKey(), function () {
            return self::where('active', 1)->sort()->get();
        });
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function getItemSite($url)
    {
        return self::where(['url' => $url, 'active' => 1])->firstOrFail();
    }

    public static function forProducts()
    {

        return self::whereHas('products', function ($q) {
            $q->where('active', 1);
        })->sort()->pluck('id')->toArray();

//        return self::whereHas('products', function($q){
//            $q->where('active',1);
//        })->where('active', 1)->with(['products'=>function($q){
//            $q->where('active',1)->with(['options' => function($q){
//                $q->where('active', 1);
//            }])->sort();
//        }])->sort()->get();
    }

    public static function action($model, $inputs)
    {
        self::clearCaches();
        if (empty($model)) {
            $model = new self;
            $ignore = false;
            $model['sort'] = $model->sortValue();
        } else {
            $ignore = $model->id;
        }
        $model['active'] = (int)!empty($inputs['active']);
        if (!empty($inputs['generate_url'])) {
            $url = self::url_unique($inputs['generated_url'], $ignore);
        } else {
            $url = $inputs['url'];
        }
        $model['url'] = $url;
        merge_model($inputs, $model, ['title', 'seo_title', 'seo_description', 'seo_keywords']);

        return $model->save();
    }

    public static function deleteItem($model)
    {
        self::clearCaches();

        return $model->delete();
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'catalogue_id', 'id')->sort();
    }
}
