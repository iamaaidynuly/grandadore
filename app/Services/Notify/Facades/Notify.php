<?php

namespace App\Services\Notify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static success($message, $title = null, $this_page = false)
 * @method static error($message, $title = null, $this_page = false)
 * @method static info($message, $title = null, $this_page = false)
 * @method static warning($message, $title = null, $this_page = false)
 * @method static get($key, $this_page = false)
 * @method static render()
 * @method static clear()
 */
class Notify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\Notify\Notify';
    }
}
