<?php

namespace App\Services\Captcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static create($name, $params = [])
 * @method static verify($name, $value)
 */
class Captcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\Captcha\Captcha';
    }
}
