<?php

namespace App\Providers;

use App\Support\LanguageProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\Routing;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class LanguageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(LanguageProvider::class, static function ($app) {
            return LanguageProvider::make(
                $app['request']->segment(1),
                (string) $app['request']->query(
                    $app['config']->get('language.query_string_key')
                )
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(LanguageProvider $languageProvider, Request $request): void
    {
        $this->setCmsRouteBooted($request);

        // force language routes when testing.
        if (! cms_booted()) {
            // forced language routes when testing.
            $forcedRoutes = count((array) $this->app['config']->get('_language.force_routes'));

            $this->app['events']->listen(Routing::class, static function()
            use ($languageProvider, $forcedRoutes) {
                if (! ($languageProvider->countVisible() || $forcedRoutes) &&
                    $languageProvider->getSettings('down_without_language')) {
                    throw new ServiceUnavailableHttpException;
                }
            });
        }
    }

    /**
     * Set CMS route is booted when the CMS slug has matched in the current request path.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function setCmsRouteBooted(Request $request): void
    {
        $segments = $request->segments();

        if (language()->isSelected()) {
            next($segments);
        }

        $this->app['config']->set('_cms.booted', current($segments) == cms_path());
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
