<?php

namespace App\Http\Middleware\Admin;

use App\Models\CmsUser\CmsUser;
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
        // check if the specified guard is authenticated
        if (is_null($user = $request->user('cms'))) {
            if ($request->expectsJson()) {
                return response()->json('Unauthorized.', 401);
            }

            return redirect()->guest(cms_route('login'));
        }

        // check if user suspended
        if ($user->suspended) {
            if ($request->expectsJson()) {
                return response()->json('Account has been suspended.', 403);
            }

            return redirect(cms_route('login'))->with('alert', fill_data(
                false, 'Account has been suspended.'
            ));
        }

        // check user route permissions
        $this->checkRoutePermission($user, $request->route());

        return $next($request);
    }

    /**
     * Determine if the user has access to the given route.
     *
     * @param  \App\Models\CmsUser\CmsUser  $user
     * @param  \Illuminate\Routing\Route|null  $route
     * @return void
     *
     */
    private function checkRoutePermission(CmsUser $user, ?Route $route): void
    {
        if ($user->hasFullAccess()) {
            return;
        }

        if (is_null($route)) {
            throw new RouteNotFoundException;
        }

        $routeName = str($fullRouteName = $route->getName())->replaceFirst(
            config('language.route_name') . '.' . cms_route_name(), ''
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
