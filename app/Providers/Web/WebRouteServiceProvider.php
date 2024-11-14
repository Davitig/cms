<?php

namespace App\Providers\Web;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WebRouteServiceProvider extends ServiceProvider
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
    public function boot(Repository $config): void
    {
        $prefix = '';

        if ($config->get('_app.language_selected')) {
            $prefix = $config->get('_app.language') . '/';
        }

        Route::middleware('web')->prefix($prefix)
            ->group(base_path('routes/web.php'));
    }
}
