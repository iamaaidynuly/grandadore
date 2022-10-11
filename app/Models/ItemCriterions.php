<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class ItemCriterions extends AbstractModel
{
    public $timestamps = false;
    protected $table = 'criteria_relations';

    private static function cacheKey()
    {
        return 'criteria_relations';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }


    public static function action($id, $data)
    {
        self::clearCaches();
        $itemCriterions = self::where(['item_id' => $id])->delete();
        $insertData = [];
        foreach ($data as $criterions) {
            $insertData[] = [
                'criteria_id' => $criterions,
                'item_id' => $id,
            ];
        }
        self::insert($insertData);
    }

    public static function getItemCriterions($id)
    {
        return self::where('item_id', $id)->get()->pluck('id')->toArray();
    }

    public static function actionImport($data)
    {
        self::clearCaches();
        $insertData = [];
        foreach ($data as $item) {
            $itemCriterions = self::where(['item_id' => $item['id']])->delete();
            if (!empty($item['criterions'])) {
                foreach ($item['criterions'] as $criteria) {
                    $insertData[] = [
                        'criteria_id' => $criteria,
                        'item_id' => $item['id'],
                    ];
                }
            }
        }
        self::insert($insertData);
    }


}
