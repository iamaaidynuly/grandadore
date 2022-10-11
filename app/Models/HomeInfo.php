<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class HomeInfo extends AbstractModel
{
    use HasTranslations, UrlUnique;

    public $translatable = ['title'];

    private static function cacheKey()
    {
        return 'info';
    }

    private static function cacheKeyHome()
    {
        return 'home_info';
    }

    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
        Cache::forget(self::cacheKeyHome());
    }


    public static function adminList()
    {
        return self::select('id', 'title', 'active', 'created_at')->sort()->get();
    }

    public static function homeList()
    {
        return Cache::rememberForever(self::cacheKeyHome(), function () {
            return self::select('title', 'created_at', 'image', 'url')->where('active', 1)->sort()->limit(8)->get();
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

        $url = $inputs['url'];

        $model['url'] = $url;
        merge_model($inputs, $model, ['title']);
        $resizes = [
            [
                'width' => 50,
                'height' => 50,
                'upsize' => true,
            ]
        ];
        if ($image = upload_image('image', 'u/home_info/', $resizes, ($action == 'edit' && !empty($model->image)) ? $model->image : false)) $model->image = $image;

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

    public static function deleteItem($model)
    {
        self::clearCaches();
        $path = public_path('u/home_info/');
        if (!empty($model->image)) File::delete($path . $model->image);

        return $model->delete();
    }
}
