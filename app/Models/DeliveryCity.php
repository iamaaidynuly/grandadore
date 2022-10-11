<?php

namespace App\Models;

class DeliveryCity extends AbstractModel
{
    public $timestamps = false;

    protected $casts = [
        'price' => 'integer',
    ];

    public static function action($model, $inputs)
    {
        if (!$model) {
            $model = new self;
            $model['region_id'] = $inputs['region_id'];
        }
        $model['title'] = $inputs['title'];
        $model['price'] = $inputs['price'];
        $model['min_price'] = $inputs['min_price'];

        return $model->save();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public function region()
    {
        return $this->belongsTo('App\Models\DeliveryRegion', 'region_id', 'id');
    }

    public function scopeSort($q)
    {
        return $q->orderBy('title', 'asc');
    }

    public static function deleteItem($model)
    {
        return $model->delete();
    }
}
