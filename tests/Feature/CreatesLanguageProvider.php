<?php

namespace Tests\Feature;

use App\Support\LanguageProvider;
use Database\Factories\LanguageFactory;

trait CreatesLanguageProvider
{
    /**
     * Create a language service.
     *
     * @param  int  $times
     * @return void
     */
    public function createLanguageProvider(int $times = 3): void
    {
        $envLanguages = $this->getEnvLanguageList();

        $envActiveLanguage = $this->getEnvActiveLanguage();

        $this->app->instance(LanguageProvider::class, new LanguageProvider(
            LanguageFactory::new()->when($envLanguages,
                fn ($factory) => $factory->languages($envLanguages, $envActiveLanguage),
                fn ($factory) => $factory->times($times)
            )->create(), $this->app['request']->path()
        ));
    }

    /**
     * Get the language list of an environment variable.
     *
     * @return array
     */
    public function getEnvLanguageList(): array
    {
        if (empty($langParams = getenv('lang_list'))) {
            return [];
        }

        $languages = [];

        foreach (explode(',', $langParams) as $lang) {
            $languages[] = $lang;
        }

        return $languages;
    }

    /**
     * Get the active language of an environment variable.
     *
     * @return string|null
     */
    public function getEnvActiveLanguage(): ?string
    {
        return getenv('lang_active');
    }
}
