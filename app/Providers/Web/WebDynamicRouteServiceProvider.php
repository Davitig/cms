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
        $langRouteName = $this->app['config']->get('language.route_name');

        // force language routes when testing.
        $languageList = ((array) $this->app['config']->get('_language.force_routes'))
            ?: language()->all()->keys()->toArray();

        $router->middleware('web', 'web.lang', 'web.dynamicRoute')
            ->group(static function (Router $router) use ($langRouteName, $languageList) {
                if (count($languageList) > 1) {
                    $router->any('{lang}/{any}', fn () => throw new NotFoundHttpException)
                        ->name($langRouteName . '.dynamic')
                        ->where('any', '.*')
                        ->whereIn($langRouteName, $languageList);
                }

                $router->any('{any}', fn () => throw new NotFoundHttpException)
                    ->name('dynamic')
                    ->where('any', '.*');
            });
    }
}
