<?php

namespace App\Models;


class Favorite extends AbstractModel
{
    protected $table = 'user_favorite';
    public $timestamps = false;

    protected $fillable = [
        'item_id'
    ];

    public static function add($user_id, $item_id)
    {
        $item = self::where('user_id', $user_id)->where('item_id', $item_id)->first();
        if (empty($item)) {
            $model = new self();
            $model->user_id = $user_id;
            $model->item_id = $item_id;
            if ($model->save()) {
                return 'red';
            }
        } else {
            $item->delete();

            return 'white';
        }

    }

    public static function deleteItem($model)
    {
        return $model->delete();
    }

    public function items()
    {
        return $this->hasMany('App\Models\Items', 'id', 'item_id');


    }

}
