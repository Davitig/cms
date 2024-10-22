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
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Server::class, function ($app) {
            $filesystem = $app[Filesystem::class];

            $source = (array) $app['config']->get('elfinder.public');
            $source = key($source);

            return (new ServerFactory([
                'source'                 => public_path($source),
                'cache'                  => $filesystem->getDriver(),
                'cache_path_prefix'      => 'images/cache',
                'group_cache_in_folders' => false
            ]))->getServer();
        });
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
