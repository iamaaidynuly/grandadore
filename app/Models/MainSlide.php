<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class MainSlide extends AbstractModel
{


    use HasTranslations, Sortable;

    public $translatable = [];

    public static function adminList()
    {
        return self::select('id', 'image', 'url', 'active')->sort()->get();
    }

    private static function cacheKey()
    {
        return 'main_slide';
    }

    private static function cacheKey_second()
    {
        return 'main_slide_second';
    }

    private static function clearCaches()
    {
        Cache::forget(self::cacheKey());
        Cache::forget(self::cacheKey_second());
    }

    public static function getHeaderSlides()
    {
        return self::where('active', 1)->sort()->get();
    }

    public static function getSecondSlides()
    {
        return Cache::rememberForever(self::cacheKey_second(), function () {
            return self::where('active', 1)->where('slider_type', 0)->sort()->get();
        });
    }

    public static function getItem($id)
    {
        $result = self::where('id', $id)->first();
        if (!$result) abort(404);

        return $result;
    }

    public static function action($model, $inputs)
    {
        self::clearCaches();
        if (empty($model)) {
            $model = new self;
            $action = 'add';
        } else {
            $action = 'edit';
        }
        $model['url'] = $inputs['url'];
        $model['active'] = !empty($inputs['active']) ? 1 : 0;
        $resizes = [
            [
                'width' => 1920,
                'height' => 688,
                'method' => 'original',
            ]
        ];
        if ($image = upload_image('image', 'u/main_slider/', $resizes, ($action == 'edit' && !empty($model->image)) ? $model->image : false)) $model->image = $image;

        return $model->save();
    }

    public static function deleteItem($model)
    {
        $path = public_path('u/main_slider/');
        if (!empty($model->image)) File::delete($path . $model->image);

        return $model->delete();
    }
}
