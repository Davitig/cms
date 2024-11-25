<?php

namespace App\Illuminate\Foundation\Configuration;

use Closure;
use Illuminate\Foundation\Configuration\ApplicationBuilder as IlluminateApplicationBuilder;
use App\Illuminate\Foundation\Support\Providers\RouteServiceProvider as AppRouteServiceProvider;

class ApplicationBuilder extends IlluminateApplicationBuilder
{

    /**
     * Register the routing services for the application.
     *
     * @param  \Closure|null  $using
     * @param  array|string|null  $web
     * @param  array|string|null  $api
     * @param  string|null  $commands
     * @param  string|null  $channels
     * @param  string|null  $pages
     * @param  string|null  $health
     * @param  string  $apiPrefix
     * @param  callable|null  $then
     * @return $this
     */
    public function withRouting(
        ?Closure $using = null,
        array|string|null $web = null,
        array|string|null $api = null,
        ?string $commands = null,
        ?string $channels = null,
        ?string $pages = null,
        ?string $health = null,
        string $apiPrefix = 'api',
        ?callable $then = null)
    {
        if (is_null($using) && (is_string($web) || is_array($web) || is_string($api) || is_array($api) || is_string($pages) || is_string($health)) || is_callable($then)) {
            $using = $this->buildRoutingCallback($web, $api, $pages, $health, $apiPrefix, $then);
        }

        AppRouteServiceProvider::loadRoutesUsing($using);

        $this->app->booting(function () {
            $this->app->register(AppRouteServiceProvider::class, force: true);
        });

        if (is_string($commands) && realpath($commands) !== false) {
            $this->withCommands([$commands]);
        }

        if (is_string($channels) && realpath($channels) !== false) {
            $this->withBroadcasting($channels);
        }

        return $this;
    }
}
