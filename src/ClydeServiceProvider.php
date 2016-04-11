<?php

namespace Antennaio\Clyde;

use Illuminate\Support\ServiceProvider;

class ClydeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/clyde.php' => config_path('clyde.php'),
        ]);

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clyde.php', 'clyde');

        $this->app->bind('laravel-clyde-image', function ($app) {
            return $app->make(ClydeImage::class);
        });

        $this->app->bind('laravel-clyde-upload', function ($app) {
            return $app->make(ClydeUpload::class);
        });
    }
}
