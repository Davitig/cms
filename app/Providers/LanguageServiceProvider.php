<?php

namespace App\Providers;

use App\Models\Language;
use Exception;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

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
        $languages = [];

        try {
            $items = (new Language)->positionAsc()->get();
        } catch (Exception) {
            // throw new ServiceUnavailableHttpException(null, 'Languages not found');
            return;
        }

        foreach ($items as $language) {
            $languages[strtolower($language->language)] = $language->getAttributes();
        }

        // Set the active language data
        $config->set(['_app.language' => key($languages)]);

        $firstSegment = (string) current($this->segments);

        if (count($languages) > 1 && array_key_exists($firstSegment, $languages)) {
            $config->set(['_app.language' => $firstSegment]);
            $config->set(['_app.language_selected' => true]);

            $this->segmentsCount--;

            array_shift($this->segments);
        } else {
            $config->set(['_app.language_selected' => false]);
        }

        $cmsActivated = current($this->segments) == $config->get('cms.slug');

        $config->set(['_cms.activated' => $cmsActivated]);

        if (! $cmsActivated) {
            $config->set(['app.locale' => $config->get('_app.language')]);
        }

        $queryString = query_string(
            $cmsActivated ? $request->except('lang') : $request->query()
        );

        // Set url for each language.
        foreach ($languages as $language => $value) {
            $languages[$language]['url'] = trim(
                $request->root() . '/' . $language . '/' . implode('/', $this->segments),
                '/'
                ) . $queryString;
        }

        $config->set(['_app.languages' => $languages]);

        $this->checkServiceAvailability($cmsActivated, $languages);
    }

    /**
     * Check service availability.
     *
     * @param  bool  $cmsActivated
     * @param  array  $languages
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException
     */
    protected function checkServiceAvailability(bool $cmsActivated, array $languages): void
    {
        if (! $cmsActivated
            && $this->segmentsCount
            && ! array_key_exists(language(), array_filter($languages, function ($language) {
                return $language['visible'];
            }))
        ) {
            throw new ServiceUnavailableHttpException;
        }
    }
}
