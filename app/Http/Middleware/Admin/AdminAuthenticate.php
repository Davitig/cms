<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Models\Permission;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $guard;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $guard
     * @return void
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->guard->guest()) {
            if ($request->expectsJson()) {
                return response()->json('Unauthorized.', 401);
            }

            return redirect()->guest(cms_route('login'));
        }

        if ($this->guard->user()->blocked) {
            throw new AccessDeniedHttpException;
        }

        if ($this->guard->user()->hasLockScreen()) {
            $redirect = redirect();

            $redirect->setIntendedUrl($request->fullUrl());

            return $redirect->to(cms_route('lockscreen'));
        }

        $this->checkRoutePermission($request);

        return $next($request);
    }

    /**
     * Determine if the user has access to the given route
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    private function checkRoutePermission(Request $request)
    {
        if (! $this->guard->user()->isAdmin()) {
            $routeName = $request->route()->getName();

            $routeGroup = substr($routeName, 0, strpos($routeName, '.'));

            if (! in_array($routeGroup, Permission::$routeGroupsHidden)
                && ! (new Permission)->role($this->guard->user()->role)->hasAccess($routeName)
            ) {
                throw new AccessDeniedHttpException;
            }
        }
    }
}
