<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Database\Factories\CmsUser\CmsUserRoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserRoleResourceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_cms_user_roles_resource_index()
    {
        CmsUserRoleFactory::new()->times(5)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_user_roles.index'));

        $response->assertOk();
    }

    public function test_admin_cms_user_roles_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_user_roles.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('cms_user_roles.store'), [
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
        )->get($this->cmsRoute('cms_user_roles.edit', [$cmsUserRole->id]));

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
        )->put($this->cmsRoute('cms_user_roles.update', [$cmsUserRole->id]), [
            'role' => 'New role name'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_validate_role_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('cms_user_roles.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['role']);
    }

    public function test_admin_cms_user_roles_resource_destroy()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('cms_user_roles.destroy', [$cmsUserRole->id]));

        $response->assertFound();
    }
}
