<?php

namespace Antennaio\Clyde;

use Antennaio\Clyde\Contracts\Server;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Support\ServiceProvider;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;

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

        $this->app->singleton('laravel-clyde-server', function ($app) {
            $files = $app->make(Filesystem::class);

            return ServerFactory::create([
                'source' => $files->disk(config('clyde.source'))->getDriver(),
                'source_path_prefix' => config('clyde.source_path_prefix'),
                'cache' => $files->disk(config('clyde.cache'))->getDriver(),
                'cache_path_prefix' => config('clyde.cache_path_prefix'),
                'watermarks' => $files->disk(config('clyde.watermarks'))->getDriver(),
                'watermarks_path_prefix' => config('clyde.watermarks_path_prefix'),
                'driver' => config('clyde.driver'),
                'max_image_size' => config('clyde.max_image_size'),
                'presets' => config('clyde.presets'),
                'response' => new SymfonyResponseFactory(),
            ]);
        });

        $this->app->bind('laravel-clyde-image', function ($app) {
            return $app->make(ClydeImage::class);
        });

        $this->app->bind('laravel-clyde-upload', function ($app) {
            return $app->make(ClydeUpload::class);
        });
    }
}
