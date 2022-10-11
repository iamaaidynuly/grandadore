<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class ItemCategories extends AbstractModel
{
    public $timestamps = false;
    protected $table = 'items_categories';

    private static function cacheKey()
    {
        return 'itemCategories';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }


    public static function action($id, $data)
    {
        self::clearCaches();
        $itemCategory = self::where(['item_id' => $id])->delete();
//        foreach ($data as $category_id) {
        $insertData[] = [
            'category_id' => $data,
            'item_id' => $id,
        ];
//        }
        self::insert($insertData);
    }

    public static function actionImport($data)
    {
        self::clearCaches();
        $insertData = [];
        foreach ($data as $item) {
            $itemCategory = self::where(['item_id' => $item['id']])->delete();
            $insertData[] = [
                'category_id' => $item['category_id'],
                'item_id' => $item['id'],
            ];
//        }
        }
        self::insert($insertData);
    }

    public function items()
    {
        return $this->belongsTo('App\Models\Items', 'item_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id')->with('childrens');
    }


}
