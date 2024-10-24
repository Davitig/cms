<?php

namespace App\Providers\Admin;

use Barryvdh\Elfinder\ElfinderServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use ReflectionClass;

class ElfinderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
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

        if (language_in_url()) {
            $config['prefix'] = language() . '/' . $config['prefix'];
        }

        $config['namespace'] = 'App\Http\Controllers';
        $config['as'] = cms_slug() . '.';

        $router->group($config, function($router) {
            $router->get('index', ['as' => 'filemanager.index', 'uses' => 'ElfinderController@showIndex']);
            $router->any('connector', ['as' => 'filemanager.connector', 'uses' => 'ElfinderController@showConnector']);
            $router->get('popup/{input_id}', ['as' => 'filemanager.popup', 'uses' => 'ElfinderController@showPopup']);
            $router->get('tinymce4', ['as' => 'filemanager.tinymce4', 'uses' => 'ElfinderController@showTinyMCE4']);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();
    }
}
