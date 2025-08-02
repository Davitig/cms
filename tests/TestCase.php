<?php

namespace Tests;

use App\Support\LanguageProvider;
use Database\Factories\LanguageFactory;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    /**
     * Indicates whether the language provider should be enabled.
     *
     * @var bool
     */
    protected bool $languageProviderEnabled = true;

    /**
     * Indicates whether the language provider should be created with default data.
     *
     * @var bool
     */
    protected bool $insertDefaultLanguageData = true;

    /**
     * The languages of an environment variable.
     *
     * @var array
     */
    protected array $envLanguages = [];

    /**
     * Active language of an environment variable.
     *
     * @var string|null
     */
    protected ?string $envActiveLanguage = null;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        if ($this->languageProviderEnabled) {
            $this->envLanguages = $this->getEnvLanguages();
        }

        $envLanguages = $this->envLanguages;

        $app = require Application::inferBasePath().'/bootstrap/app.php';

        $app->booting(static function () use ($app, $envLanguages) {
            $app['config']->set('language.force_routes', $envLanguages);
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->languageProviderEnabled && $this->insertDefaultLanguageData) {
            $this->envActiveLanguage = getenv('lang_active')
                ?: reset($this->envLanguages);

            $this->createLanguageProvider();
        }
    }

    /**
     * Generate a CMS URL to a named route.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  mixed|null  $language
     * @param  bool  $absolute
     * @return string
     */
    protected function cmsRoute(
        string $name,
        mixed  $parameters = [],
        mixed  $language = null,
        bool   $absolute = true
    ): string
    {
        if (is_null($language) && $this->envActiveLanguage) {
            $language = $this->envActiveLanguage;
        }

        return cms_route($name, $parameters, $language, $absolute);
    }

    /**
     * Generate a URL to a named route.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  mixed|null  $language
     * @param  bool  $absolute
     * @return string
     */
    protected function webRoute(
        string $name,
        mixed  $parameters = [],
        mixed  $language = null,
        bool   $absolute = true
    ): string
    {
        if (is_null($language) && $this->envActiveLanguage) {
            $language = $this->envActiveLanguage;
        }

        return web_route($name, $parameters, $language, $absolute);
    }

    /**
     * Generate a URL for the application.
     *
     * @param  mixed|null  $path
     * @param  mixed  $parameters
     * @param  mixed|null  $language
     * @param  bool|null  $secure
     * @return string
     */
    protected function webUrl(
        mixed $path = null,
        array $parameters = [],
        mixed $language = null,
        ?bool $secure = null
    ): string
    {
        if (is_null($language) &&
            $this->envActiveLanguage &&
            count($this->envLanguages) > 1) {
            $language = $this->envActiveLanguage;
        }

        return web_url($path, $parameters, $language, $secure);
    }

    /**
     * Create a language service.
     *
     * @return void
     */
    public function createLanguageProvider(): void
    {
        $this->app->instance(LanguageProvider::class, new LanguageProvider(
            $this->envLanguages ? LanguageFactory::new()->when($this->envLanguages,
                fn ($factory) => $factory->languages(
                    $this->envLanguages, $this->envActiveLanguage
                ),
            )->create() : new Collection, $this->app['request']->path()
        ));
    }

    /**
     * Get the languages of an environment variable.
     *
     * @return array
     */
    public function getEnvLanguages(): array
    {
        if (($langParams = getenv('lang_list')) === false) {
            return ['en', 'es', 'cn'];
        }

        $languages = [];

        foreach (array_filter(explode(',', $langParams)) as $lang) {
            $languages[] = $lang;
        }

        return $languages;
    }
}
