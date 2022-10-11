<?php

namespace App\Services\FileManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static create($name, $params = [])
 * @method static verify($name, $value)
 * @method static uploadImage($key, $path, $sizes, $delete = false)
 * @method static uploadOriginalImage($key, $path, $delete = false)
 * @method static uploadFile($key, $path, $delete = false)
 */
class FileManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\FileManager\FileManager';
    }
}
