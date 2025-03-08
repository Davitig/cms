<?php

namespace App\Http\Middleware\Web;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class WebCheckForMaintenanceMode
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(protected Repository $config) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $visibleLanguages = languages(true);

        if (empty($visibleLanguages) || ! array_key_exists(language(), $visibleLanguages)) {
            throw new ServiceUnavailableHttpException;
        }

        return $next($request);
    }
}
