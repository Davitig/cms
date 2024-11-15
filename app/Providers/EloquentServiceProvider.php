<?php

namespace App\Providers;

use App\Providers\Admin\AdminEloquentUserProvider;
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
        $this->app['auth']->provider('cmsEloquent', function (Application $app, array $config) {
            return new AdminEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
