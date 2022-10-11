<?php

namespace App\Registries;


use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserRegistry
{
    /**
     * @var
     */
    protected static $user;

    /**
     * @param null $guard
     * @param bool $newInstance
     * @return User|Authenticatable|null
     */
    public static function get($guard = null, $newInstance = false)
    {
        if (self::$user == null || $newInstance) {
            self::$user = Auth::guard($guard)->user();

            if (self::$user) {
                self::$user->loadMissing('basketItems');
            }
        }

        return self::$user;
    }
}
