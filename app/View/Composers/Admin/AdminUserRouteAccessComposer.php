<?php

namespace App\View\Composers\Admin;

use App\Models\CmsUser\CmsUser;
use App\Models\Permission;
use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;

class AdminUserRouteAccessComposer
{
    /**
     * The user route access callback.
     *
     * @var \Closure
     */
    protected Closure $userRouteAccess;

    /**
     * Create a new view composer instance.
     */
    public function __construct(AuthManager $auth)
    {
        $this->userRouteAccess = $this->getUserRouteAccess($auth->guard('cms')->user());
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('userRouteAccess', $this->userRouteAccess);
    }

    /**
     * Get the user route access resolver.
     *
     * Singe-Routes: ('users.index', 'orders.store', ...)
     * Base-Routes: ('users', 'orders', ..., true) # last element with boolean true.
     * Single-Or-Base-Routes: (['users' => true, 'orders.store', ...]) # specific route name(s) with boolean true.
     *
     * NOTE: Boolean true in the array checks routes by base name.
     *
     * @param  \App\Models\CmsUser\CmsUser|null  $user
     * @return \Closure
     */
    protected function getUserRouteAccess(?CmsUser $user): Closure
    {
        if (is_null($user)) {
            return fn () => false;
        } elseif ($user->hasFullAccess()) {
            return fn () => true;
        }

        $routeNamesAllowed = array_merge(
            (new Permission)->roleId($user->cms_user_role_id)->pluck('route_name')->toArray(),
            Permission::$routeGroupsAllowed,
            Permission::$routeNamesAllowed
        );

        return function (...$routeNames) use ($routeNamesAllowed) {
            if (empty($routeNames)) {
                return false;
            }

            $baseName = false;

            if (count($routeNames) > 1 &&
                $baseName = is_bool($baseName = end($routeNames)) && $baseName) {
                array_pop($routeNames);

                array_walk($routeNamesAllowed, function (&$value) {
                    $value = str($value)->beforeLast('.')->toString();
                });
            }

            if (is_array($routeNames[0])) {
                $routeNames = $routeNames[0];
            }

            foreach ($routeNames as $key => $routeName) {
                if ($routeName === true) {
                    $routeName = $key;

                    foreach ($routeNamesAllowed as $allowedRouteName) {
                        if (! $baseName) {
                            $allowedRouteName = str($allowedRouteName)->beforeLast('.')->toString();
                        }

                        if ($allowedRouteName === $routeName) {
                            return true;
                        }
                    }
                } elseif (in_array($routeName, $routeNamesAllowed)) {
                    return true;
                }
            }

            return false;
        };
    }
}
