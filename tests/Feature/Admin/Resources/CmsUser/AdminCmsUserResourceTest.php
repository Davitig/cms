<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Database\Factories\CmsUserRoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserResourceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_cms_users_resource_index()
    {
        $this->createCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_users.index'));

        $response->assertOk();
    }

    public function test_admin_cms_users_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_users.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_store()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('cms_users.store'), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => $cmsUserRole->id,
            'password' => 'password1',
            'password_confirmation' => 'password1'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_edit()
    {
        $cmsUser = $this->createCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('cms_users.edit', [$cmsUser->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_update()
    {
        $cmsUser = $this->createCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('cms_users.update', [$cmsUser->id]), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => $cmsUser->cms_user_role_id
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_validation()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('cms_users.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors([
            'email', 'first_name', 'last_name', 'cms_user_role_id', 'password'
        ]);
    }

    public function test_admin_cms_users_resource_destroy()
    {
        $cmsUser = $this->createCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('cms_users.destroy', [$cmsUser->id]));

        $response->assertFound();
    }
}
