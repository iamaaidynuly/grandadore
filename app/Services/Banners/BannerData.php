<?php

namespace App\Services\Banners;

use Illuminate\Support\Facades\App;

class BannerData
{
    private $params;

    public function __construct($params)
    {
        $this->params = json_decode($params, true);
    }

    public function __isset($key)
    {
        if (!isset($this->params[$key])) return false;
        $result = $this->params[$key];
        if (is_array($result)) $result = $result[App::getLocale()] ?? null;

        return isset($result);
    }

    public function __get($key)
    {
        $result = $this->params[$key] ?? null;
        if (is_array($result)) return $result[App::getLocale()] ?? null;

        return $result;
    }

    public function __call($method, $params)
    {
        $result = $this->params[$method];
        if (!is_array($result)) return asset('u/banners/' . $result);
        else return null;
    }
//    public function toArray($allLocales=false){
//        if ($allLocales) return $this->params;
//        $result = [];
//        foreach ($this->params as $key=>$param) {
//            $result[$key] = $this->__get($key);
//        }
//        return $result;
//    }
    public function getKeys()
    {
        return array_keys($this->params);
    }

    public function t($param, $lang)
    {
        return $this->params[$param][$lang] ?? null;
    }
}
