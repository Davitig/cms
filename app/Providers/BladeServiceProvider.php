<?php

namespace App\Providers;

use App\Support\LanguageProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
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
    public function boot(LanguageProvider $languageProvider): void
    {
        Blade::if('ifMainLanguage', static function (?string $value) use ($languageProvider) {
            if (! $value || $value == $languageProvider->main()) {
                return true;
            }

            return false;
        });
    }
}
