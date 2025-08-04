<?php

namespace App\Providers\Admin;

use App\Http\Controllers\Admin\AdminElfinderController;
use Barryvdh\Elfinder\ElfinderServiceProvider as ServiceProvider;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use ReflectionClass;

class ElfinderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        parent::register();
    }

    /**
     * Bootstrap services.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @throws \Throwable
     */
    public function boot(Router $router): void
    {
        $viewPath = dirname((new ReflectionClass($this))->getParentClass()->getFilename());
        $viewPath .= '/../resources/views';

        $this->loadViewsFrom($viewPath, 'elfinder');
        $this->publishes([
            $viewPath => base_path('resources/views/vendor/elfinder'),
        ], 'views');

        if (! defined('ELFINDER_IMG_PARENT_URL')) {
            define('ELFINDER_IMG_PARENT_URL', $this->app['url']->asset('packages/barryvdh/elfinder'));
        }

        $config = $this->app['config']->get('elfinder.route', []);

        $config['prefix'] = isset($config['prefix']) ? cms_path($config['prefix']) : cms_path();

        $config['middleware'][] = 'cms.auth';
        $config['as'] = cms_route_name();

        $forcedLanguages = ((array) $this->app['config']->get('_language.force_routes'))
            ?: language()->all()->keys()->toArray();

        if ($forcedLanguages) {
            $langRouteName = $this->app['config']->get('language.route_name');

            $config['middleware'][] = 'cms.lang';
            $config['prefix'] = "{{$langRouteName}}/" . $config['prefix'];
            $config['as'] = $langRouteName . '.' . $config['as'];

            $callback = function (Route $route) use ($langRouteName, $forcedLanguages) {
                $route->whereIn($langRouteName, $forcedLanguages);
            };
        } else {
            $callback = null;
        }

        $this->defineRoutes($router, $config, $callback);
    }

    /**
     * Define the elFinder routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  array  $config
     * @param  \Closure|null  $callback
     * @return void
     */
    protected function defineRoutes(Router $router, array $config, Closure $callback = null): void
    {
        $router->group($config, function(Router $router) use ($callback) {
            $router->get('index', [AdminElfinderController::class, 'showIndex'])
                ->name('file_manager.index')->when($callback);
            $router->any('connector', [AdminElfinderController::class, 'showConnector'])
                ->name('file_manager.connector')->when($callback);;
            $router->get('popup/{input_id}', [AdminElfinderController::class, 'showPopup'])
                ->name('file_manager.popup')->when($callback);;
            $router->get('tinymce5', [AdminElfinderController::class, 'showTinyMCE5'])
                ->name('file_manager.tinymce5')->when($callback);;
        });
    }
}
