<?php

namespace Tests;

use App\Models\Language;
use Database\Factories\LanguageFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

abstract class TestCase extends BaseTestCase
{
    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $envActiveLanguage = $this->getEnvActiveLanguage();

        $envLanguages = $this->getEnvLanguageList();

        (new Language)->newQuery()->delete();

        DB::update('ALTER TABLE languages AUTO_INCREMENT = 1');

        $languages = LanguageFactory::new()->when($envLanguages, function ($factory, $envLanguages) {
            return $factory->times(count($envLanguages))->sequence(...$envLanguages);
        }, fn ($factory) => $factory->times(5))->create()
            ->mapWithKeys(function (Language $language) {
                return [$language->language => $language->toArray() + ['url' => '']];
            })->toArray();

        $this->app['config']->set('_app.languages', $languages);

        if (! is_null($envActiveLanguage) && array_key_exists($envActiveLanguage, $languages)) {
            $this->activeLanguage = $envActiveLanguage;
        } else {
            $this->activeLanguage = key(array_filter(
                $languages, fn ($lang) => $lang['main']
            ) ?: $languages) ?: null;
        }

        $this->app['config']->set('_app.language', $this->activeLanguage);
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
     * Get the active language.
     *
     * @return string|null
     */
    protected function getActiveLanguage(): ?string
    {
        return $this->activeLanguage;
    }

    /**
     * Get the language list of an environment variable.
     *
     * @return array|null
     */
    protected function getEnvLanguageList(): ?array
    {
        if (empty($langParams = getenv('lang_list'))) {
            return null;
        }

        $languages = [];

        foreach (explode(',', $langParams) as $lang) {
            if (mb_strlen($lang) != 2) {
                throw new InvalidArgumentException(
                    'Language env variable value must be 2 characters'
                );
            }

            $languages[] = ['language' => $lang, 'short_name' => $lang, 'full_name' => $lang];
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
        if (empty($lang = getenv('lang_active'))) {
            return null;
        }

        if (mb_strlen($lang) != 2) {
            throw new InvalidArgumentException(
                'Active language env variable value must be 2 characters'
            );
        }

        return $lang;
    }
}
