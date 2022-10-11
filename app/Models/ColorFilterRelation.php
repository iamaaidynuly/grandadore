<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorFilterRelation extends Model
{
    protected $table = 'color_filter_relations';

    public static function actionImport($data)
    {

        $all_filter = [];
        $this_filter = [];
//dd($data);
        $insertData = [];
        foreach ($data as $i => $item) {
            if ($item['filter_id']) {
                $filters = explode('.', $item['filter_id']);
//                $filters = explode(',', $item['filter_id']);

                foreach ($filters as $filter) {
                    $filter = (int)trim($filter);
                    if (!in_array($filter, $this_filter)) $this_filter[] = $filter;
                    if (!in_array($filter, $all_filter)) $all_criteria[] = $filter;
                }


            }

            $item['filter_id'] = $this_filter;


            if (self::where(['item_id' => $item['item_id']])->count() > 0) {
                $itemCategory = self::where(['item_id' => $item['item_id']])->delete();
            }

            if (count($filters) > 1) {
                foreach ($filters as $filter) {
                    array_push($insertData, [
                        'item_id' => $item['item_id'],
                        'filter_id' => $filter,
                    ]);
                }
            } else {
                $insertData[] = [
                    'item_id' => $item['item_id'],
                    'filter_id' => $filter,
                ];
            }

        }
//        dd($insertData);

        self::insert($insertData);
    }
}
