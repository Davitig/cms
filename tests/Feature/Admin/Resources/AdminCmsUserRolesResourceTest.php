<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\CmsUserRole;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserRolesResourceTest extends TestAdmin
{
    public function test_admin_cms_user_roles_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsUserRoles.index'));

        $response->assertOk();
    }

    public function test_admin_cms_user_roles_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsUserRoles.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('cmsUserRoles.store'), [
            'role' => 'Role name',
            'full_access' => 0
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsUserRoles.edit', [
            (new CmsUserRole)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('cmsUserRoles.update', [
            (new CmsUserRole)->valueOrFail('id')
        ]), [
            'role' => 'New role name'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_validate_role_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('cmsUserRoles.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['role']);
    }

    public function test_admin_cms_user_roles_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('cmsUserRoles.destroy', [
            (new CmsUserRole)->orderByDesc('id')->valueOrFail('id')
        ]));

        $response->assertFound();
    }
}
