<?php

namespace App\Http\Middleware\Admin;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminAuthenticate
{
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
        if (! $request->user()) {
            if ($request->expectsJson()) {
                return response()->json('Unauthorized.', 401);
            }

            return redirect()->guest(cms_route('login'));
        }

        if ($request->user()->blocked) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        if ($request->user()->hasLockScreen()) {
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
        if ($request->user()->hasFullAccess()) {
            return;
        }

        $routeName = str_replace(
            cms_route_name_prefix(''), '', $request->route()->getName()
        );

        $routeGroup = substr($routeName, 0, strpos($routeName, '.'));

        if (! in_array($routeGroup, Permission::$routeGroupsAllowed)
            && ! in_array($routeName, Permission::$routeNamesAllowed)
            && ! (new Permission)->roleId($request->user()->cms_user_role_id)->hasAccess($routeName)
        ) {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }
}
