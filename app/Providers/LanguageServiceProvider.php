<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * The list of application languages.
     *
     * @var array
     */
    protected array $languages = [];

    /**
     * The number of total application languages.
     *
     * @var int
     */
    protected int $languagesCount = 0;

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
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function boot(Request $request, Config $config): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $this->setLanguageConfig($request, $config);

        $this->makeLanguageUrls($request, $config);
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
        $this->segmentsCount = count($this->segments = $request->segments());

        $firstSegment = (string) current($this->segments);

        $languages = [];

        foreach ((new Language)->orderByDesc('main')->get() as $language) {
            $languages[strtolower($language->language)] = $language->getAttributes();
        }

        $config->set(['app.language' => key($languages)]);
        $config->set(['app.languages' => $this->languages = $languages]);

        $this->languagesCount = count($languages);

        // Set the current application language
        if ($this->languagesCount > 1 && array_key_exists($firstSegment, $this->languages)) {
            $config->set(['app.language' => $firstSegment]);
            $config->set(['language_in_url' => true]);

            $this->segmentsCount--;

            array_shift($this->segments);
        } else {
            $config->set(['language_in_url' => false]);
        }

        // Set URL segments and its count, without a language segment
        $config->set(['url_path_segments' => $this->segments]);
        $config->set(['url_path_segments_count' => $this->segmentsCount]);

        $cmsIsBooted = current($this->segments) == $config->get('cms.slug');

        $config->set(['cms_is_booted' => $cmsIsBooted]);

        if (! $cmsIsBooted) {
            $config->set(['app.locale' => $config->get('app.language')]);
        }
    }

    /**
     * Make current URL for all available languages of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    protected function makeLanguageUrls(Request $request, Config $config): void
    {
        if ($this->languagesCount > 1
            && (! isset($this->segments[0])
                || ! array_key_exists($this->segments[0], $this->languages)
            )
        ) {
            array_unshift($this->segments, $config->get('app.language'));
        }

        $query = query_string(
            $config->get('cms_is_booted') ? $request->except('lang') : $request->query()
        );

        foreach($this->languages as $key => $value) {
            if ($this->languagesCount > 1) {
                $this->segments[0] = $key;
            }

            $config->set([
                "app.languages.{$key}.url" => $request->root() . '/' .
                    implode('/', $this->segments) . $query
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
