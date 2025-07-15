<?php

namespace App\Providers;

use App\Support\LanguageProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(LanguageProvider::class, static function ($app) {
            return LanguageProvider::make(
                $app['request']->path(), (string) $app['request']->query(
                    $app['config']->get('language.query_string_key'),
                )
            );
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
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [LanguageProvider::class];
    }
}
