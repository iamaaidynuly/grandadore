<?php

namespace App\Http\Controllers\Site;


use App\Models\Banner;
use Illuminate\Support\Facades\Cookie;

class ExchangeController extends BaseController
{
    public function set($title)
    {
        $exchanges = collect(Banner::get('exchange')->exchange)->where('title', $title);
        if ($exchanges->isNotEmpty()) {
            $exchange = $exchanges->first();
            Cookie::queue('exchange', $exchange->title, 60 * 24 * 7);
            config(['exchange' => $exchange->title]);
        }

        return redirect()->back();
    }
}
