<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('cms');

        if (! is_null($user) && ! $user->hasLockScreen()) {
            if ($request->expectsJson()) {
                return response()->json('Forbidden', 403);
            }

            return redirect(cms_route('dashboard'));
        }

        return $next($request);
    }
}
