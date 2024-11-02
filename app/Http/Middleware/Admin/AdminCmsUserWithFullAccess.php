<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminCmsUserWithFullAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->hasFullAccess()) {
            if ($request->expectsJson()) {
                return response()->json('Forbidden', 403);
            }

            throw new AccessDeniedHttpException('Forbidden');
        }

        return $next($request);
    }
}
