<?php

namespace App\Providers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use League\Glide\Server;
use League\Glide\ServerFactory;

class GlideServiceProvider extends ServiceProvider implements DeferrableProvider
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
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [Server::class];
    }
}
