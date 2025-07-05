<?php

namespace App\Providers;

use App\Services\LanguageService;
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
    public function boot(LanguageService $languageService): void
    {
        Blade::if('ifMainLanguage', function (?string $value, bool $force = false)
        use ($languageService) {
            if (! $force && ! $value || $value == $languageService->main()) {
                return true;
            }

            return false;
        });
    }
}
