<?php

namespace App\Http\Middleware\Admin;

use App\Models\CmsUser;
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
        $user = $request->user('cms');

        if (is_null($user)) {
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

        $this->checkRoutePermission($user, $request->route()->getName());

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

        $routeName = str_replace(language() . '.' . cms_route_name(), '', $fullRouteName);

        if ($routeName == $fullRouteName) {
            $routeName = str_replace(cms_route_name(), '', $routeName);
        }

        $routeGroup = substr($routeName, 0, strpos($routeName, '.'));

        if (! in_array($routeGroup, Permission::$routeGroupsAllowed)
            && ! in_array($routeName, Permission::$routeNamesAllowed)
            && ! (new Permission)->roleId($user->cms_user_role_id)->hasAccess($routeName)
        ) {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }
}
