<?php

namespace App\Http\Middleware\Admin;

use App\Models\Calendar;
use App\Models\CmsUser;
use App\Models\Menu;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminViewData
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
        $this->shareSettings();

        $this->shareMenus();

        $this->shareCalendar();

        $this->shareRouteMatches();

        $this->shareUserRouteAccess($request->user());

        return $next($request);
    }

    /**
     * Share the CMS settings.
     *
     * @return void
     */
    protected function shareSettings(): void
    {
        $settings = app('db')->table('cms_settings')
            ->where('cms_user_id', Auth::guard('cms')->id())
            ->first();

        if (! is_null($settings)) {
            $settings->body = "$settings->sidebar_direction $settings->layout_boxed $settings->skin_sidebar $settings->skin_user_menu $settings->skin_horizontal";
            $settings->body = preg_replace('/\s+/', ' ', trim($settings->body));
        }

        view()->share(['cmsSettings' => new Collection($settings)]);
    }

    /**
     * Share the menus.
     *
     * @return void
     */
    protected function shareMenus(): void
    {
        $menus = (new Menu)->get();

        view()->composer([
            'admin._partials.sidebar_menu',
            'admin._partials.horizontal_menu',
            'admin.menus.index',
            'admin.pages.index'
        ], function($view) use ($menus) {
            $view->with('menus', $menus);
        });
    }

    /**
     * Share the calendar.
     *
     * @return void
     */
    protected function shareCalendar(): void
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+7 days', strtotime($start)));

        $calendar = (new Calendar)->active($start, $end)->get();

        view()->composer([
            'admin._partials.user',
            'admin._partials.horizontal_menu',
            'admin.dashboard.index'
        ], function($view) use ($calendar) {
            $view->with('calendarEvents', $calendar);
        });
    }

    /**
     * Share if the current route matches the specified routes names.
     *
     * @return void
     */
    protected function shareRouteMatches(): void
    {
        $currentRouteName = str_replace(
            cms_route_name_prefix(''), '', app('router')->current()->getName()
        );
        $currentRouteIndexParam = current(app('router')->current()->parameters());

        $resourceMethod = null;

        foreach (resource_names('') as $value) {
            if (str_contains($currentRouteName, $value)) {
                $currentRouteName = str_replace($value, '', $currentRouteName);

                $resourceMethod = $value;
            }
        }

        view()->composer('admin._partials.menu',
            function($view) use ($currentRouteName, $currentRouteIndexParam, $resourceMethod) {
                $view->with('routeMatches', function (
                    $routeNames, $routeParam = null, $byResource = true
                ) use ($currentRouteName, $currentRouteIndexParam, $resourceMethod) {
                    if (! $byResource) {
                        $currentRouteName .= $resourceMethod;
                    }

                    foreach ((array) $routeNames as $key => $value) {
                        if (is_string($key)) {
                            $routeName = $key;
                            $currentRouteIndexParam = $value;
                        } else {
                            $routeName = $value;
                        }

                        if (! $routeParam
                            && $routeName == $currentRouteName
                            || $routeParam == $currentRouteIndexParam
                            && $routeName == $currentRouteName
                        ) {
                            return true;
                        }
                    }

                    return false;
                });
            }
        );
    }

    /**
     * Share view function which indicates if a user has access to the specified routes.
     *
     * @param  \App\Models\CmsUser|null  $user
     * @return void
     */
    protected function shareUserRouteAccess(?CmsUser $user = null): void
    {
        if (is_null($user)) {
            $this->userRouteAccess(function () {
                return false;
            });

            return;
        }

        $routeNamesAllowed = array_merge(
            (new Permission)->roleId($user->cms_user_role_id)->pluck('route_name')->toArray(),
            Permission::$routeGroupsAllowed,
            Permission::$routeNamesAllowed
        );

        $hasFullAccess = $user->hasFullAccess();

        $this->userRouteAccess(function (...$routeNames) use ($routeNamesAllowed, $hasFullAccess) {
            if ($hasFullAccess) {
                return true;
            }

            foreach ($routeNames as $routeName) {
                if (in_array($routeName, $routeNamesAllowed)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Function which indicates if a user has access to the specified routes.
     *
     * @param  \Closure  $callback
     * @return void
     */
    protected function userRouteAccess(Closure $callback): void
    {
        view()->composer(['admin.*'], function($view) use ($callback) {
            $view->with('userRouteAccess', $callback);
        });
    }
}
