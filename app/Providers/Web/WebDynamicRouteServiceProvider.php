<?php

namespace App\Providers\Web;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebDynamicRouteServiceProvider extends ServiceProvider
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
    public function boot(Router $router): void
    {
        $router->middleware('web')->group(function (Router $router) {
            $router->any('{any}', fn () => throw new NotFoundHttpException)
                ->where('any', '.*')
                ->middleware('web.lang', 'web.dynamicRoute');
        });
    }
}
