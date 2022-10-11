<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BistriZakaz extends Model
{
    protected $table='bistri_zakaz';

    public $timestamps = false;

  /*  public function itemsNew()
    {

        return $this->hasManyThrough(Items::class, BistriZakazUser::class, 'item_id', 'id')
            ;
    }*/

    public function item(){
        $this->belongsTo(Items::class,'item_id','id');
    }



}
