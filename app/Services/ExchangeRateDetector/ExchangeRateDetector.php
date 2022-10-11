<?php

namespace App\Services\ExchangeRateDetector;

use App\Models\Banner;
use Stevebauman\Location\Facades\Location;

class ExchangeRateDetector
{
    protected $rate = 1;

    protected $country = 'KZ';

    public function detectCountry(string $ip)
    {
        $position = Location::get($ip);

//        if ($position->countryCode == 'RU') {
//            $banner = Banner::get('info');
//
//            $this->rate = $banner->rates[0]->{'ruble'} ?? 1;
//
//            $this->country = $position->countryCode;
//        }
    }

    public function getRate() : float
    {
        return (float) $this->rate;
    }

    public function getCountry() : string
    {
        return $this->country;
    }
}
