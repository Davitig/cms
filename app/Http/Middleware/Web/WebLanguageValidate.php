<?php

namespace App\Http\Middleware\Web;

use App\Support\LanguageProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebLanguageValidate
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \App\Support\LanguageProvider  $languageProvider
     */
    public function __construct(
        protected Route $route, protected LanguageProvider $languageProvider
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $this->route->parameter($langRouteName = config('language.route_name'));

        if (! $this->languageProvider->isEmpty() &&
            ! is_null($response = $this->expectedLanguageRedirect($language, $request))) {
            return $response;
        }

        // remove lang parameter from being passed to controller
        $this->route->forgetParameter($langRouteName);

        return $next($request);
    }

    /**
     * Get a probably expected language redirect from the current request.
     *
     * @param  string|null  $language
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    protected function expectedLanguageRedirect(?string $language, Request $request)
    {
        if ($language) {
            if (! $this->languageProvider->visibleExists($language)) {
                throw new NotFoundHttpException;
            }

            if ($this->languageProvider->isMain($language)) {
                if ($this->languageProvider->getSettings('redirect_from_main')) {
                    return redirect(web_url(str($request->path())->replaceStart(
                        $language, ''
                    ), [], false));
                }

                if ($this->languageProvider->getSettings('disable_main_language_from_url') ||
                    $this->languageProvider->countVisible() === 1 &&
                    ! $this->languageProvider->getSettings('allow_single_language_in_url')) {
                    throw new NotFoundHttpException;
                }
            }
        } elseif (! $this->languageProvider->isSelected() &&
            $this->languageProvider->countVisible() &&
            ! $this->languageProvider->getSettings('disable_main_language_from_url') &&
            ! $this->languageProvider->getSettings('redirect_from_main') &&
            ($this->languageProvider->countVisible() !== 1 ||
                $this->languageProvider->getSettings('allow_single_language_in_url')) &&
            $this->languageProvider->getSettings('redirect_to_main')) {
            return redirect(web_url($request->path(), [], true));
        }
    }
}
