<?php

namespace App\Http\Middleware\Web;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class WebValidateLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $request->route()->parameter('lang');

        if (language()->visibleIsEmpty() || ! language()->visibleExists($language)) {
            throw new ServiceUnavailableHttpException;
        }

        $request->route()->forgetParameter('lang');

        return $next($request);
    }
}
