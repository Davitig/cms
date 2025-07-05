<?php

namespace App\Providers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AdminConfigurationProvider extends ServiceProvider
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
        if (! cms_booted()) {
            return;
        }

        Paginator::defaultView('admin.-partials.pagination');
    }
}
