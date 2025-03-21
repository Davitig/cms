<?php

namespace App\Providers;

use App\Auth\EloquentCmsUserProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['auth']->provider('cms', function (Application $app, array $config) {
            return new EloquentCmsUserProvider($app['hash'], $config['model']);
        });
    }
}
