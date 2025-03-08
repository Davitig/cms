<?php

namespace App\Providers;

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
    public function boot(): void
    {
        Route::middleware('web')->group(function (Router $router) {
            $this->loadWebRoutes($router);

            $this->loadCMSRoutes($router);
        });
    }

    /**
     * Load web routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function loadWebRoutes(Router $router): void
    {
        $router->middleware('web.maintenance')
            ->group(base_path('routes/web.php'));

        if (is_multilanguage()) {
            foreach (languages() as $lang => $value) {
                $router->middleware('web.maintenance')
                    ->prefix($lang)->name($lang . '.')->group(
                        base_path('routes/web.php')
                    );
            }
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

        if (is_multilanguage()) {
            $cmsSlug = cms_slug();

            foreach (languages() as $lang => $value) {
                $router->prefix($lang . '/' . $cmsSlug)->name(
                    $lang . '.' . cms_route_name()
                )->group(base_path('routes/cms.php'));
            }
        }
    }
}
