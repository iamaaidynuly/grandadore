<?php

namespace App\Models;

class Basket extends AbstractModel
{
    protected $table = 'basket';
    public $timestamps = false;

    public static function getItem($item_id)
    {
        $user_id = auth()->user()->id;
        $result = self::where(['item_id' => $item_id, 'user_id' => $user_id])->first();

        return $result;
    }

    public static function getItemView($item_id)
    {
        $user_id = auth()->user()->id;
        $result = self::where(['item_id' => $item_id, 'user_id' => $user_id])->with('items')->first();

        return $result;
    }

    public static function getUserItems($skip_check = false)
    {
        return self::where('user_id', auth()->user()->id)->with('items')->get();
    }

    public static function getItemsForUser($id)
    {
        return self::where('user_id', $id)->with('items')->get();
    }

    public static function clear()
    {
        return self::where('user_id', auth()->user()->id)->delete();
    }

    public function items()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public function item()
    {
        return $this->items();
    }

    public function size()
    {
        return $this->belongsTo(ItemSizes::class, 'size_id');
    }

    public function color()
    {
        return $this->belongsTo(ColorFilter::class, 'color_id');
    }


}
