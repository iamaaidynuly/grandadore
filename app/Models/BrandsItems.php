<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class BrandsItems extends AbstractModel
{
    public $timestamps = false;
    protected $table = 'item_brands';

    private static function cacheKey()
    {
        return 'item_brands';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }


    public static function action($id, $data)
    {
        self::clearCaches();
        $itemCategory = self::where(['item_id' => $id])->delete();
        $insertData[] = [
            'item_id' => $id,
            'brand_id' => $data,
        ];
        self::insert($insertData);
    }

//    public static function actionImport($data)
//    {
//        self::clearCaches();
//        $brandIds = Brands::query()->where('active', 1)->pluck('id')->toArray();
//        $insertData = [];
//
//        foreach ($data as $item) {
//            if (!in_array($item['brand_id'], $brandIds)) {
//                continue;
//            }
//
//            self::query()->where(['item_id' => $item['item_id']])->delete();
//
//            $insertData[] = [
//                'item_id' => $item['item_id'],
//                'brand_id' => $item['brand_id'],
//            ];
//        }
//
//        self::query()->insert($insertData);
//    }
    public static function actionImport($data)
    {
        self::clearCaches();
        $insertData = [];
        foreach ($data as $item) {
            if (self::where(['item_id' => $item['item_id']])->count() > 0) {
                $itemCategory = self::where(['item_id' => $item['item_id']])->delete();
            }
            if($item['brand_id']!= null) {
                $insertData[] = [
                    'item_id' => $item['item_id'],
                    'brand_id' => $item['brand_id'],
                ];
            }
//        }
        }
        self::insert($insertData);
    }

    public function items()
    {
        return $this->hasMany('App\Models\Items', 'id', 'item_id');
    }

    public function brands()
    {
        return $this->hasMany('App\Models\Brands', 'id', 'brand_id');
    }


}
