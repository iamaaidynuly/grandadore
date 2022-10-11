<?php

namespace App\Services\PageManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getHomePage()
 * @method static getUsedRoutes()
 * @method static inUsedRoutes($name)
 * @method static getStaticPages()
 * @method static getActiveStaticPages()
 * @method static isActive($page)
 * @method static getPages()
 * @method static getPage($static)
 * @method static action($value)
 */
class PageManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\PageManager\PageManager';
    }
}
