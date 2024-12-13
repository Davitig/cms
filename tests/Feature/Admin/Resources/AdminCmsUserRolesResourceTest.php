<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\CmsUserRole;
use Tests\TestCase;

class AdminCmsUserRolesResourceTest extends TestCase
{
    public function test_admin_cms_user_roles_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('cmsUserRoles.index'));

        $response->assertOk();
    }

    public function test_admin_cms_user_roles_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('cmsUserRoles.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('cmsUserRoles.store'), [
            'role' => 'roleName',
            'full_access' => 0
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_edit()
    {
        $id = (new CmsUserRole)->orderDesc()->valueOrFail('id');

        $response = $this->actingAs($this->getUser())->get(cms_route('cmsUserRoles.edit', [$id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_user_roles_resource_update()
    {
        $id = (new CmsUserRole)->orderDesc()->valueOrFail('id');

        $response = $this->actingAs($this->getUser())->put(cms_route('cmsUserRoles.update', [$id]), [
            'role' => 'newRoleName'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_user_roles_resource_validate_role_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('cmsUserRoles.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['role']);
    }

    public function test_admin_cms_user_roles_resource_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('cmsUserRoles.destroy', [
            (new CmsUserRole)->orderDesc()->valueOrFail('id')
        ]));

        $response->assertFound();
    }
}
