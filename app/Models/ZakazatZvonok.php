<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class ZakazatZvonok extends Model
{
    protected $table='zakazatzvonok';


    public static function allZakazat(){
       return ZakazatZvonok::where('id','>',0)->get();
    }

}
