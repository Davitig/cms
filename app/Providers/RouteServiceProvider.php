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

        // force language routes when testing.
        $forcedLanguages = ((array) $this->app['config']->get('language.force_routes'))
            ?: language()->all()->keys()->toArray();

        $this->loadWebRoutes($router, $langRouteName, $forcedLanguages);

        Route::middleware('web')->group(function (Router $router)
        use ($request, $langRouteName, $forcedLanguages) {
            $this->loadCMSRoutes($router, $request, $langRouteName, $forcedLanguages);
        });
    }

    /**
     * Load web routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  string  $langRouteName
     * @param  array  $forcedLanguages
     * @return void
     */
    protected function loadWebRoutes(
        Router $router, string $langRouteName, array $forcedLanguages
    ): void
    {
        $routes = $this->getWebRoutesFileList();

        // routes without language prefix.
        $router->middleware('web.lang')->group($routes);

        if (count($forcedLanguages) > 1) {
            // routes with language prefix.
            $router->middleware('web.lang')
                ->prefix("{{$langRouteName}}")
                ->name($langRouteName . '.')
                ->whereIn($langRouteName, $forcedLanguages)
                ->group($routes);
        }
    }

    /**
     * Get the required web routes file list.
     *
     * @return array
     */
    protected function getWebRoutesFileList(): array
    {
        return [
            base_path('routes/web.php'),
        ];
    }

    /**
     * Load CMS routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $langRouteName
     * @param  array  $forcedLanguages
     * @return void
     */
    protected function loadCMSRoutes(
        Router $router, Request $request, string $langRouteName, array $forcedLanguages
    ): void
    {
        $routes = $this->getCMSRoutesFileList();

        if (! count($forcedLanguages)) {
            // routes without language prefix.
            $router->prefix(cms_path())->name(cms_route_name())->group($routes);
        } else {
            // force redirect to language prefixed route.
            $router->get(cms_path('{any?}'), function () use ($request) {
                return redirect(language()->active() . '/' . $request->path());
            })->where('any', '.*');

            // routes with language prefix.
            $router->middleware('cms.lang')
                ->prefix("{{$langRouteName}}/" . cms_path())
                ->name($langRouteName . '.' . cms_route_name())
                ->group($routes);
        }
    }

    /**
     * Get the required CMS routes file list.
     *
     * @return array
     */
    protected function getCMSRoutesFileList(): array
    {
        return [
            base_path('routes/cms.php'),
            base_path('routes/cms_settings.php')
        ];
    }
}
