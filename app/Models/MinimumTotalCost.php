<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\InsertOrUpdate;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;

class MinimumTotalCost extends AbstractModel
{
    use HasTranslations, UrlUnique, Sortable, InsertOrUpdate;

    protected $table = 'minimum_total_cost';
    public $timestamps = false;
    public $translatable = [
        'text'
    ];
    public static function adminList()
    {
        return self::withCount('cities')->sort()->get();
    }

    public static function action($model, $inputs)
    {

        if (!$model) {
            $model = new self;
        }
        $model['price'] = $inputs['price'];
//        dd($model['text']);
        $model['text'] = $inputs['text'];
//        merge_model($inputs, $model, ['text']);

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

//    public static function siteList()
//    {
//        return self::whereHas('cities')->with('cities')->get();
//    }

//    public static function jsonForRegions($countries){
//        return $countries->mapWithKeys(function($item){
//            return [
//                $item->id => $item->regions->map(function($item){
//                    return ['id'=>$item->id, 'title'=>$item->title];
//                })
//            ];
//        });
//    }
//
//    public function cities()
//    {
//        return $this->hasMany('App\Models\DeliveryCity', 'region_id', 'id')->sort();
//    }
//
//    public function scopeSort($q)
//    {
//        return $q->orderBy('title', 'asc');
//    }
}
