<?php

namespace App\Models;

class DeliveryRegion extends AbstractModel
{
    public $timestamps = false;

    public static function adminList()
    {
        return self::withCount('cities')->sort()->get();
    }

    public static function action($model, $inputs)
    {
        if (!$model) {
            $model = new self;
        }
        $model['title'] = $inputs['title'];

        return $model->save();
    }

    public static function deleteItem($model)
    {
        return $model->delete();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function siteList()
    {
        return self::whereHas('cities')->with('cities')->get();
    }

//    public static function jsonForRegions($countries){
//        return $countries->mapWithKeys(function($item){
//            return [
//                $item->id => $item->regions->map(function($item){
//                    return ['id'=>$item->id, 'title'=>$item->title];
//                })
//            ];
//        });
//    }

    public function cities()
    {
        return $this->hasMany('App\Models\DeliveryCity', 'region_id', 'id')->sort();
    }

    public function scopeSort($q)
    {
        return $q->orderBy('title', 'asc');
    }
}
