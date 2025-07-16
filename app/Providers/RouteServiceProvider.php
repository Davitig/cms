<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
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
    public function boot(Request $request, Router $router): void
    {
        $langRouteName = $this->app['config']->get('language.route_name');

        $this->loadWebRoutes($router, $langRouteName);

        Route::middleware('web')->group(function (Router $router) use ($request, $langRouteName) {
            $this->loadCMSRoutes($router, $request, $langRouteName);
        });
    }

    /**
     * Load web routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  string  $langRouteName
     * @return void
     */
    protected function loadWebRoutes(Router $router, string $langRouteName): void
    {
        $router->middleware('web.lang')
            ->group(base_path('routes/web.php'));

        if (language()->count() > 1) {
            $router->middleware('web.lang')
                ->prefix("{{$langRouteName}}")
                ->name($langRouteName . '.')
                ->whereIn($langRouteName, language()->all()->keys()->toArray())
                ->group(base_path('routes/web.php'));
        }
    }

    /**
     * Load CMS routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $langRouteName
     * @return void
     */
    protected function loadCMSRoutes(Router $router, Request $request, string $langRouteName): void
    {
        if (language()->isEmpty()) {
            // routes without language prefix
            $router->prefix(cms_path())->name(cms_route_name())
                ->group(base_path('routes/cms.php'));
        } else {
            // redirector to language prefixed route
            $router->get(cms_path() . '/{any?}', function () use ($request) {
                return redirect(language()->active() . '/' . $request->path());
            })->where('any', '.*');

            // routes with language prefix
            $router->middleware('cms.lang')
                ->prefix("{{$langRouteName}}/" . cms_path())
                ->name($langRouteName . '.' . cms_route_name())
                ->whereIn($langRouteName, language()->all()->keys()->toArray())
                ->group(base_path('routes/cms.php'));
        }
    }
}
