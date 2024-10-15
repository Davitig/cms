<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->app->booted(function ($app) {
            $this->filterRoutes($app['router'], $app['config']);
        });

        //
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * {@inheritDoc}
     */
    protected function loadCachedRoutes()
    {
        parent::loadCachedRoutes();

        $this->app->booted(function ($app) {
            $this->filterRoutes($app['router'], $app['config']);
        });
    }

    /**
     * Filter all routes by specified language and CMS slug.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Config\Repository  $config
     * @return void
     */
    protected function filterRoutes(Router $router, Config $config)
    {
        $language = null;

        if ($config->get('language_isset')) {
            $language = $config->get('app.language');
        }

        $cmsSlug = $config->get('cms.slug');

        foreach ($router->getRoutes() as $route) {
            if (! is_null($language)) {
                $route->prefix($language);
            }

            $prefix = $route->getPrefix();

            if (! is_null($prefix) && Str::contains($prefix, $cmsSlug)) {
                $route->name('.' . $cmsSlug);
            }
        }
    }
}
