<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * The list of URL segments.
     *
     * @var array
     */
    protected array $segments = [];

    /**
     * The number of total URL segments.
     *
     * @var int
     */
    protected int $segmentsCount = 0;

    /**
     * Register the application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function boot(Request $request, Config $config): void
    {
        $this->segmentsCount = count($this->segments = $request->segments());

        $this->setLanguageConfig($request, $config);
    }

    /**
     * Set language config.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    protected function setLanguageConfig(Request $request, Config $config): void
    {
        if (! $this->app['db']->connection()->getSchemaBuilder()->hasTable('languages')) {
            return;
        }

        $languages = [];

        foreach ((new Language)->positionAsc()->get()->toArray() as $language) {
            $languages[strtolower($language['language'])] = $language;
        }

        $firstSegment = (string) current($this->segments);
        $langSelected = array_key_exists($firstSegment, $languages);

        $config->set(['_app.language_selected' => $langSelected]);

        if ($langSelected) {
            $config->set(['_app.language' => $activeLanguage = $firstSegment ?: null]);

            $this->segmentsCount--;

            array_shift($this->segments);
        } else {
            $config->set([
                '_app.language' => $activeLanguage = key(array_filter(
                    $languages, fn ($lang) => $lang['main']
                ) ?: $languages) ?: null
            ]);
        }

        $cmsActivated = current($this->segments) == $config->get('cms.slug');

        $config->set(['_cms.activated' => $cmsActivated]);

        if (! $cmsActivated && ! $this->app->runningInConsole()) {
            $config->set(['app.locale' => $activeLanguage]);
        }

        $config->set([
            '_app.languages' => $this->addAllLanguagesToActiveUrl($languages, $request)
        ]);
    }

    /**
     * Add all languages to the active URL.
     *
     * @param  array  $languages
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function addAllLanguagesToActiveUrl(array $languages, Request $request): array
    {
        // add all languages to the active URL.
        foreach ($languages as $language => $value) {
            $languages[$language]['url'] = trim(
                $request->root() . '/' . $language . '/' . implode('/', $this->segments),
                '/'
            );
        }

        return $languages;
    }
}
