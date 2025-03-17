<?php

namespace Tests;

use App\Models\Language;
use App\Services\LanguageService;
use Database\Factories\LanguageFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerLanguageService();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        (new Language)->newQuery()->delete();

        parent::tearDown();
    }

    /**
     * Register language service.
     *
     * @return void
     */
    protected function registerLanguageService(): void
    {
        $envLanguages = $this->getEnvLanguageList();

        $envActiveLanguage = $this->getEnvActiveLanguage();

        (new Language)->newQuery()->delete();

        DB::update('ALTER TABLE languages AUTO_INCREMENT = 1');

        $this->app->instance(LanguageService::class, new LanguageService(
            LanguageFactory::new()->when($envLanguages,
                fn ($factory) => $factory->languages($envLanguages, $envActiveLanguage),
                fn ($factory) => $factory->times(5)
            )->create(), $this->app['request']->path()
        ));
    }

    /**
     * Get the language list of an environment variable.
     *
     * @return array
     */
    protected function getEnvLanguageList(): array
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
    protected function getEnvActiveLanguage(): ?string
    {
        return getenv('lang_active');
    }
}
