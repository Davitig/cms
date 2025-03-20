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
    public function boot(Request $request): void
    {
        $this->setCmsRouteActivated($request);

        Route::middleware('web')->group(function (Router $router) {
            $this->loadWebRoutes($router);

            $this->loadCMSRoutes($router);
        });
    }

    /**
     * Set CMS route is activated if the slug has matched in a request path.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function setCmsRouteActivated(Request $request): void
    {
        $segments = $request->segments();

        if (language()->isSelected()) {
            next($segments);
        }

        $this->app['config']->set('_cms.activated', current($segments) == cms_slug());
    }

    /**
     * Load web routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function loadWebRoutes(Router $router): void
    {
        $router->middleware('web.lang')
            ->group(base_path('routes/web.php'));

        if (language()->containsMany()) {
            $router->middleware('web.lang')
                ->prefix('{lang}')
                ->name('lang.')
                ->whereIn('lang', language()->all()->keys()->toArray())
                ->group(base_path('routes/web.php'));
        }
    }

    /**
     * Load CMS routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function loadCMSRoutes(Router $router): void
    {
        $router->prefix(cms_slug())->name(cms_route_name())
            ->group(base_path('routes/cms.php'));

        if (language()->containsMany()) {
            $router->middleware('cms.lang')
                ->prefix('{lang}/' . cms_slug())
                ->name('lang.')
                ->whereIn('lang', language()->all()->keys()->toArray())
                ->group(base_path('routes/cms.php'));
        }
    }
}
