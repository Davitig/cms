<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('cms');

        if ($guard->check() && ! $guard->user()->hasLockScreen()) {
            if ($request->expectsJson()) {
                return response()->json('Forbidden', 403);
            }

            return redirect(cms_route('dashboard'));
        }

        return $next($request);
    }
}
