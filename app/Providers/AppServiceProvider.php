<?php

namespace App\Providers;

use App\Services\ExchangeRateDetector\ExchangeRateDetector;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExchangeRateDetector::class, function () {
            return new ExchangeRateDetector();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        date_default_timezone_set('Asia/Aqtobe');
    }
}
