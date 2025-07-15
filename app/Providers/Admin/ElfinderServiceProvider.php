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

        $config['prefix'] = isset($config['prefix']) ? cms_slug($config['prefix']) : cms_slug();

        $config['middleware'][] = 'cms.auth';
        $config['as'] = cms_route_name();

        $this->defineRoutes($router, $config);

        if (language()->count() > 1) {
            $config['middleware'][] = 'cms.lang';
            $config['prefix'] = '{lang}/' . $config['prefix'];
            $config['as'] = 'lang.' . $config['prefix'];
            $languages = language()->all()->keys()->toArray();

            $this->defineRoutes($router, $config, function (Route $route) use ($languages) {
                $route->whereIn('lang', $languages);
            });
        }
    }

    /**
     * Define the elFinder routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  array  $config
     * @param  \Closure|null  $values
     * @return void
     */
    protected function defineRoutes(Router $router, array $config, Closure $values = null): void
    {
        $router->group($config, function(Router $router) use ($values) {
            $router->get('index', [AdminElfinderController::class, 'showIndex'])
                ->name('fileManager.index')->when($values);
            $router->any('connector', [AdminElfinderController::class, 'showConnector'])
                ->name('fileManager.connector')->when($values);;
            $router->get('popup/{input_id}', [AdminElfinderController::class, 'showPopup'])
                ->name('fileManager.popup')->when($values);;
            $router->get('tinymce5', [AdminElfinderController::class, 'showTinyMCE5'])
                ->name('fileManager.tinymce5')->when($values);;
        });
    }
}
