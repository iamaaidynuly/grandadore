<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    protected $table = 'items_order';

    public function item()
    {
        return $this->belongsTo(Items::class, 'items_id');
    }
}
