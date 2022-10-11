<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapAjaxController extends Controller
{
    public function map(Request $request){
        $exploade = explode('||',$request->x);

        $x = $exploade[0] ;
        $y = $exploade[1] ;
        return view('map.map',compact('x','y'));
    }
}
