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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! is_null($user = $request->user('cms')) && ! $user->hasLockScreen()) {
            if ($request->expectsJson()) {
                return response()->json('Forbidden', 403);
            }

            return redirect(cms_route('dashboard'));
        }

        return $next($request);
    }
}
