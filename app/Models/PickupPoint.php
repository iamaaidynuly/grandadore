<?php

namespace App\Models;

use App\Http\Traits\Sortable;

class PickupPoint extends AbstractModel
{
    use Sortable;

    protected $sortableDesc = false;

    public static function action($model, $inputs, $group_id = null)
    {
        if (!$model) {
            $model = new self;
            $model['sort'] = $model->sortValue();
        }
        $model['address'] = $inputs['address'];
        $model['lat'] = $inputs['lat'];
        $model['title'] = $inputs['title'];
        $model['phone'] = $inputs['phone'];
        $model['lng'] = $inputs['lng'];
        $model['active'] = (int)array_key_exists('active', $inputs);

        return $model->save();
    }

    public static function adminList()
    {
        return self::sort()->get();
    }

    public static function siteList()
    {
        return self::where('active', 1)->sort()->get();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function deleteItem($model)
    {
        return $model->delete();
    }
}
