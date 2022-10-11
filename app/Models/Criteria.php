<?php

namespace App\Models;

class Criteria extends AbstractModel
{
    protected $table = 'criteria';

    public function filter()
    {
        return $this->belongsTo('App\Models\Filter', 'id', 'filter_id');
    }

    public function item()
    {
        return $this->belongsToMany('App\Models\Items', 'criteria_relations', 'criteria_id', 'item_id');
    }

    public function addOrUpdate($filter_id, $request)
    {
        if ($request->has('criterion.old')) {
            foreach ($request->input('criterion.old') as $id => $criterion) {
                $updateData = $criterion;
                self::where(['id' => $id])->update($updateData);
            }
        }
        $criterions = self::where(['filter_id' => $filter_id])->delete();
        if ($request->has('criterion.new')) {
            $insertData = [];
            foreach ($request->input('criterion.new') as $criterion) {
                if ($criterion['name']) {
                    $insertData[] = [
                        'name' => $criterion['name'],
                        'filter_id' => $filter_id,
                    ];
                }
            }
            self::insert($insertData);
        }

        return true;
    }

    public static function updateCriteria($filter_id, $criteria)
    {
        $insertData = array();
        foreach ($criteria as $i => $v) {
            $oneCriteriaArray = json_decode($v);
            $response = self::where(['filter_id' => $filter_id, 'name_hy' => $oneCriteriaArray[0], 'name_ru']);
            $insertData[$i] = array(
                'name_hy' => $oneCriteriaArray[0],
                'name_ru' => $oneCriteriaArray[1],
                'name_en' => $oneCriteriaArray[2],
                'filter_id' => $filter_id
            );
        }
        $response = self::insert($insertData);

        return $response;
    }


    public static function deleteCriteria($filter_id)
    {
        return self::where('filter_id', $filter_id)->delete();
    }


}
