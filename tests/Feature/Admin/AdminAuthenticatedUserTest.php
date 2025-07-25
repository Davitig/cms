<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser\CmsUserRole;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Feature\InteractsWithRouter;

class AdminAuthenticatedUserTest extends TestAdmin
{
    use RefreshDatabase, InteractsWithRouter;

    public function test_admin_user_authenticated()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('dashboard.index'));

        $response->assertOk();
    }

    public function test_admin_user_with_full_access()
    {
        // specified route expects full access.
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('permissions.index'));

        $response->assertOk();
    }

    public function test_admin_user_with_custom_access_cannot_access_non_permitted_route()
    {
        $routeName = head(Arr::flatten($this->getAllCMSRouteNames()));

        $response = $this->actingAs(
            $this->getCustomAccessCmsUser(), 'cms'
        )->get($this->cmsRoute($routeName));

        $response->assertForbidden();
    }

    public function test_admin_user_with_custom_access_can_access_permitted_route()
    {
        $routeName = head(Arr::flatten($this->getAllCMSRouteNames()));

        $roleId = (new CmsUserRole)->customAccess()->valueOrFail('id');

        if (! (new Permission)->roleId($roleId)->routeName($routeName)->exists()) {
            (new Permission)->create([
                'cms_user_role_id' => $roleId, 'route_name' => $routeName
            ]);
        }

        $response = $this->actingAs(
            $this->getCustomAccessCmsUser(), 'cms'
        )->get($this->cmsRoute($routeName));

        $response->assertOk();
    }

    public function test_admin_suspended_user_being_logged_out(): void
    {
        $user = $this->createCmsUser(false, function ($factory) {
            return $factory->suspended(true);
        });

        $response = $this->actingAs($user, 'cms')
            ->get($this->cmsRoute('dashboard.index'));

        $response->assertRedirect($this->cmsRoute('login'))
            ->assertSessionHas('alert.result', false);
    }
}
