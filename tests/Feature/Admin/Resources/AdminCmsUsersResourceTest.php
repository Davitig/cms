<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\CmsUser;
use App\Models\CmsUserRole;
use Tests\TestCase;

class AdminCmsUsersResourceTest extends TestCase
{
    public function test_admin_cms_users_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('cmsUsers.index'));

        $response->assertOk();
    }

    public function test_admin_cms_users_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('cmsUsers.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('cmsUsers.store'), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => (new CmsUserRole)->valueOrFail('id'),
            'password' => $password = bcrypt(fake()->password(8, 8)),
            'password_confirmation' => $password
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_edit()
    {
        $id = (new CmsUser)->orderDesc()->valueOrFail('id');

        $response = $this->actingAs($this->getUser())->get(cms_route('cmsUsers.edit', [$id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_update()
    {
        $id = (new CmsUser)->orderDesc()->valueOrFail('id');

        $response = $this->actingAs($this->getUser())->put(cms_route('cmsUsers.update', [$id]), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => (new CmsUserRole)->valueOrFail('id')
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_validation()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('cmsUsers.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors([
            'email', 'first_name', 'last_name', 'cms_user_role_id', 'password'
        ]);
    }

    public function test_admin_cms_users_resource_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('cmsUsers.destroy', [
            (new CmsUser)->orderDesc()->valueOrFail('id')
        ]));

        $response->assertFound();
    }
}
