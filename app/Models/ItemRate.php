<?php

namespace App\Models;


class ItemRate extends AbstractModel
{

    protected $fillable = [
        'item_id',
        'user_id',
        'rating',
    ];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
