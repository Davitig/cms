<?php

namespace App\Http\Middleware\Web;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class WebLanguageValidate
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Routing\Route  $route
     */
    public function __construct(protected Route $route) {}

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

        if ($language && ! language()->visibleExists($language)) {
            throw new NotFoundHttpException;
        }

        if (! language()->activeIsVisible()) {
            throw new ServiceUnavailableHttpException;
        }

        // remove lang parameter from being passed to controller
        $this->route->forgetParameter($langRouteName);

        return $next($request);
    }
}
