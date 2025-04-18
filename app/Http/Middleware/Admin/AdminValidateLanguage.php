<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminValidateLanguage
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
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $this->route->parameter('lang');

        if (language()->isEmpty() || ! language()->exists($language)) {
            throw new NotFoundHttpException;
        }

        $this->route->forgetParameter('lang');

        return $next($request);
    }
}
