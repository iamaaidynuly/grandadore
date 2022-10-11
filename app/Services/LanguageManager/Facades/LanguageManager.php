<?php

namespace App\Services\LanguageManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getPrefix()
 * @method static middleware()
 * @method static getLocale()
 * @method static getCanonicalUrl()
 * @method static getUrlWithLocale()
 * @method static setLocaleWithoutPrefix()
 * @method static getDefaultLocale()
 */
class LanguageManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\LanguageManager\LanguageManager';
    }
}
