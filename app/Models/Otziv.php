<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otziv extends Model
{
    protected $table='otziv';


    public function item(){
        return $this->belongsTo(Items::class, 'item_id','id');
    }
}
