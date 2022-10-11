<?php


namespace App\Services\BasketService;


use App\Services\BasketService\Drivers\DatabaseDriver;
use App\Services\BasketService\Drivers\SessionDriver;

class BasketFactory
{
    public static function createDriver()
    {
        if (authUser()) {
            return new BasketService(
                new DatabaseDriver()
            );
        } else {
            return new BasketService(
                new SessionDriver()
            );
        }
    }
}
