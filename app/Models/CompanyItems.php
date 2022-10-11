<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class CompanyItems extends AbstractModel
{
    public $timestamps = false;
    protected $table = 'item_magazine';

    private static function cacheKey()
    {
        return 'item_magazine';
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
            'user_id' => $data,
        ];
        self::insert($insertData);
    }

    public static function actionImport($data)
    {
        self::clearCaches();
        $companyIds = User::query()->where('type', 1)->pluck('id')->toArray();
        $insertData = [];

        foreach ($data as $item) {
            if (!in_array($item['user_id'], $companyIds)) {
                continue;
            }
            self::query()->where(['item_id' => $item['item_id']])->delete();

            $insertData[] = [
                'item_id' => $item['item_id'],
                'user_id' => auth()->user()->id,
            ];
        }

        self::query()->insert($insertData);
    }

    public function items()
    {
        return $this->hasMany('App\Models\Items', 'id', 'item_id')->with('reviews');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
    }


}
