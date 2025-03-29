<?php

namespace App\Http\Middleware\Admin;

use App\Models\CmsUser;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($user = $request->user('cms'))) {
            if ($request->expectsJson()) {
                return response()->json('Unauthorized.', 401);
            }

            return redirect()->guest(cms_route('login'));
        }

        if ($user->blocked) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        if ($user->hasLockScreen()) {
            $redirect = redirect();

            $redirect->setIntendedUrl($request->fullUrl());

            return $redirect->to(cms_route('lockscreen'));
        }

        if (! ($route = $request->route()) instanceof Route) {
            throw new RouteNotFoundException;
        }

        $this->checkRoutePermission($user, $route->getName());

        return $next($request);
    }

    /**
     * Determine if the user has access to the given route.
     *
     * @param  \App\Models\CmsUser  $user
     * @param  string  $fullRouteName
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    private function checkRoutePermission(CmsUser $user, string $fullRouteName): void
    {
        if ($user->hasFullAccess()) {
            return;
        }

        $routeName = str($fullRouteName)->replaceFirst(
            language()->active() . '.' . cms_route_name(), ''
        )->toString();

        if ($routeName == $fullRouteName) {
            $routeName = str($fullRouteName)->replaceFirst(cms_route_name(), '')->toString();
        }

        $routeGroup = substr($routeName, 0, strpos($routeName, '.'));

        if (! in_array($routeGroup, Permission::$routeGroupsAllowed) &&
            ! in_array($routeName, Permission::$routeNamesAllowed) &&
            ! (new Permission)->roleId($user->cms_user_role_id)->hasAccess($routeName)) {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }
}
