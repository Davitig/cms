<?php

namespace App\Illuminate\Foundation\Support\Providers;

use App\Support\DynamicRouteRegistrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * @mixin \Illuminate\Routing\Router
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Load the cached routes for the application.
     *
     * @return void
     */
    protected function loadCachedRoutes()
    {
        parent::loadCachedRoutes();

        $this->app->booted(function ($app) {
            (new DynamicRouteRegistrar($app))->boot();
        });
    }

    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        parent::loadRoutes();

        (new DynamicRouteRegistrar($this->app))->boot();
    }
}
