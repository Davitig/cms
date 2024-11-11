<?php

namespace App\View\Composers\Admin;

use App\Models\CmsUser;
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
     *
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
     * @param  \App\Models\CmsUser|null  $user
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

        $hasFullAccess = $user->hasFullAccess();

        return function (...$routeNames) use ($routeNamesAllowed, $hasFullAccess) {
            if ($hasFullAccess) {
                return true;
            }

            foreach ($routeNames as $routeName) {
                if (in_array($routeName, $routeNamesAllowed)) {
                    return true;
                }
            }

            return false;
        };
    }
}
