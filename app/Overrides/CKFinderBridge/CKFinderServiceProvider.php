<?php

namespace CKSource\CKFinderBridge;

use CKSource\CKFinderBridge\Command\CKFinderDownloadCommand;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel;

class CKFinderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap.
     */
    private function real_dir()
    {
        return base_path() . '/vendor/ckfinder/ckfinder-laravel-package/src';
    }

    public function boot()
    {
        $real_dir = $this->real_dir();
        if ($this->app->runningInConsole()) {
            $this->commands([CKFinderDownloadCommand::class]);

            $this->publishes([
                $real_dir . '/config.php' => config_path('ckfinder.php'),
                $real_dir . '/../public' => public_path('js')
            ], 'ckfinder');

            return;
        }

//        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom($real_dir . '/../views', 'ckfinder');

        $this->app->bind('ckfinder.connector', function () {
            if (!class_exists('\CKSource\CKFinder\CKFinder')) {
                throw new \Exception(
                    "Couldn't find CKFinder conector code. " .
                    "Please run `artisan ckfinder:download` command first."
                );
            }

            $ckfinderConfig = config('ckfinder');

            if (is_null($ckfinderConfig)) {
                throw new \Exception(
                    "Couldn't load CKFinder configuration file. " .
                    "Please run `artisan vendor:publish --tag=ckfinder` command first."
                );
            }

            $ckfinder = new \CKSource\CKFinder\CKFinder($ckfinderConfig);

            if (Kernel::MAJOR_VERSION >= 4) {
                $this->setupForV4PlusKernel($ckfinder);
            }

            return $ckfinder;
        });
    }

    /**
     * Prepares CKFinder DI container to use version version 4+ of HttpKernel.
     *
     * @param \CKSource\CKFinder\CKFinder $ckfinder
     */
    protected function setupForV4PlusKernel($ckfinder)
    {
        $ckfinder['resolver'] = function () use ($ckfinder) {
            $commandResolver = new \CKSource\CKFinderBridge\Polyfill\CommandResolver($ckfinder);
            $commandResolver->setCommandsNamespace(\CKSource\CKFinder\CKFinder::COMMANDS_NAMESPACE);
            $commandResolver->setPluginsNamespace(\CKSource\CKFinder\CKFinder::PLUGINS_NAMESPACE);

            return $commandResolver;
        };

        $ckfinder['kernel'] = function () use ($ckfinder) {
            return new HttpKernel(
                $ckfinder['dispatcher'],
                $ckfinder['resolver'],
                $ckfinder['request_stack'],
                $ckfinder['resolver']
            );
        };
    }
}
