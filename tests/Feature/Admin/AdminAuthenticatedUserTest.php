<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser;
use App\Models\CmsUserRole;
use App\Models\Permission;
use Illuminate\Support\Arr;
use Tests\TestCase;

class AdminAuthenticatedUserTest extends TestCase
{
    public function test_admin_user_authenticated()
    {
        $user = (new CmsUser)->firstOrFail();

        $response = $this->actingAs($user, 'cms')->get(cms_route('dashboard'));

        $response->assertOk();
    }

    public function test_admin_user_with_full_access()
    {
        $roleId = (new CmsUserRole)->fullAccess()->valueOrFail('id');

        $user = (new CmsUser)->roleId($roleId)->joinRole()->firstOrFail();

        // route needs full access
        $response = $this->actingAs($user, 'cms')->get(cms_route('permissions.index'));

        $response->assertOk();
    }

    public function test_admin_user_with_custom_access_cannot_access_non_permitted_route()
    {
        $roleId = (new CmsUserRole)->customAccess()->valueOrFail('id');

        $user = (new CmsUser)->roleId($roleId)->joinRole()->firstOrFail();

        // route needs full access
        $response = $this->actingAs($user, 'cms')->get(cms_route('permissions.index'));

        $response->assertForbidden();
    }

    public function test_admin_user_with_custom_access_can_access_permitted_route()
    {
        $routeName = current(Arr::flatten($this->getAllCMSRouteNames()));

        $roleId = (new CmsUserRole)->customAccess()->valueOrFail('id');

        if (! (new Permission)->roleId($roleId)->routeName($routeName)->exists()) {
            (new Permission)->create([
                'cms_user_role_id' => $roleId, 'route_name' => $routeName
            ]);
        }

        $user = (new CmsUser)->roleId($roleId)->joinRole()->firstOrFail();

        $response = $this->actingAs($user, 'cms')->get(cms_route($routeName));

        $response->assertOk();
    }

    protected function getAllCMSRouteNames(): array
    {
        $routeNames = [];

        $cmsSlug = cms_route_name();

        foreach ($this->app['router']->getRoutes()->getRoutesByName() as $name => $route) {
            if (! str_starts_with($name, $cmsSlug)
                || ! empty($route->parameterNames())
                || ! in_array('GET', $route->methods())
            ) {
                continue;
            }

            $name = str($name)->chopStart($cmsSlug)->toString();

            $baseRouteName = str($name)->before('.')->toString();

            if ($baseRouteName) {
                $routeNames[$baseRouteName][] = $name;
            } else {
                $routeNames[$name] ??= [];
                array_unshift($routeNames[$name], $name);
            }
        }

        return array_filter(array_diff_key(
            $routeNames,
            array_flip(Permission::$routeGroupsHidden),
            array_flip(Permission::$routeGroupsAllowed)
        ), function ($routes) {
            return array_filter($routes, function ($route) {
                return ! in_array($route, Permission::$routeNamesHidden)
                    && ! in_array($route, Permission::$routeNamesAllowed);
            });
        });
    }
}
