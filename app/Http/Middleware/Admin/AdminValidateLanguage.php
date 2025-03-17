<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminValidateLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $request->route()->parameter('lang');

        if (language()->isEmpty() || ! language()->exists($language)) {
            throw new NotFoundHttpException;
        }

        $request->route()->forgetParameter('lang');

        return $next($request);
    }
}
