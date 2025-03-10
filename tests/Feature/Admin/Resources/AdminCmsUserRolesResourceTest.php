<?php

namespace Tests\Feature\Admin\Resources;

use Database\Factories\CmsUserRoleFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserRolesResourceTest extends TestAdmin
{
    public function test_admin_cms_user_roles_resource_index()
    {
        CmsUserRoleFactory::new()->times(5)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUserRoles.index'));

        $response->assertOk();
    }

    public function test_admin_cms_user_roles_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUserRoles.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('cmsUserRoles.store'), [
            'role' => 'Role name',
            'full_access' => 0
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_edit()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUserRoles.edit', [$cmsUserRole->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_update()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('cmsUserRoles.update', [$cmsUserRole->id]), [
            'role' => 'New role name'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_validate_role_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('cmsUserRoles.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['role']);
    }

    public function test_admin_cms_user_roles_resource_destroy()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('cmsUserRoles.destroy', [$cmsUserRole->id]));

        $response->assertFound();
    }
}
