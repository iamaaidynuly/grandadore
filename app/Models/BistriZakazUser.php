<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class BistriZakazUser extends Model
{
    protected $table='bistri_zakaz_user';

    public static function orderStatus() {
        $val=BistriZakazUser::where('status','=','3')->get();

        return $val->count();
    }

    public static function count(){
        $val=BistriZakazUser::where('id','>','0')->get();

        return $val->count() ;
    }

    public function items(){
        return $this->hasMany(BistriZakaz::class , 'user_id','id');
    }



    public function itemsNew()
    {

        return $this->hasManyThrough(Items::class, BistriZakaz::class,'user_id','id','id','item_id')
            ;
    }



}
