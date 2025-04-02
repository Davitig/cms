<?php

namespace App\Providers;

use App\Http\Controllers\Web\WebGlideServerController;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use League\Glide\Server;
use League\Glide\ServerFactory;

class GlideServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Server::class, function ($app) {
            $source = (array) $app['config']->get('elfinder.public');
            $source = key($source);

            return (new ServerFactory([
                'source'                 => public_path($source),
                'cache'                  => $app[Filesystem::class]->getDriver(),
                'cache_path_prefix'      => 'glide/cache',
                'group_cache_in_folders' => false
            ]))->getServer();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(Repository $config, Router $router): void
    {
        if ($config->get('web.enable_glide')) {
            $router->get($config->get('web.glide_base_url') . '/{path}', [
                WebGlideServerController::class, 'show'
            ])->name('glide')->where('path', '.+');
        }
    }
}
