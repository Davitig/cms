<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setFilesystemMacroGetPathUsingId();
    }

    /**
     * Set the filesystem macro to get directory using id.
     *
     * @return void
     */
    protected function setFilesystemMacroGetPathUsingId(): void
    {
        $this->app['filesystem']->macro('getPathUsingId',
            function (int|string $id, string $path = '') {
                return 'id_' . $id . ($path ? '/' . $path : $path);
            }
        );
    }
}
