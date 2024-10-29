<?php

namespace App\Http\Middleware\Admin;

use App\Models\Permission;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminAuthenticate
{
    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct(protected Guard $guard) {}

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
        if ($this->guard->guest()) {
            if ($request->expectsJson()) {
                return response()->json('Unauthorized.', 401);
            }

            return redirect()->guest(cms_route('login'));
        }

        if ($this->guard->user()->blocked) {
            throw new AccessDeniedHttpException('Forbidden');
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
     * Determine if the user has access to the given route.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    private function checkRoutePermission(Request $request): void
    {
        if ($this->guard->user()->isAdmin()) {
            return;
        }

        $routeName = str_replace(
            cms_route_name_prefix(''), '', $request->route()->getName()
        );

        $routeGroup = substr($routeName, 0, strpos($routeName, '.'));

        if (! in_array($routeGroup, Permission::$routeGroupsHidden)
            && ! in_array($routeName, Permission::$routeNamesHidden)
            && ! (new Permission)->role($this->guard->user()->role)->hasAccess($routeName)
        ) {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }
}
