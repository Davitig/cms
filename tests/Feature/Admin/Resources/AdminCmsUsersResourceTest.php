<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\CmsUser;
use Database\Factories\CmsUserFactory;
use Database\Factories\CmsUserRoleFactory;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUsersResourceTest extends TestAdmin
{
    public function test_admin_cms_users_resource_index()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();
        CmsUserFactory::new()->times(5)->role($cmsUserRole->id)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUsers.index'));

        $response->assertOk();
    }

    public function test_admin_cms_users_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUsers.create'));

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
        )->post(cms_route('cmsUsers.store'), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => $cmsUserRole->id,
            'password' => $password = bcrypt(fake()->password(8, 8)),
            'password_confirmation' => $password
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_edit()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();
        $cmsUser = CmsUserFactory::new()->role($cmsUserRole->id)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('cmsUsers.edit', [$cmsUser->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_update()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();
        $cmsUser = CmsUserFactory::new()->role($cmsUserRole->id)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('cmsUsers.update', [$cmsUser->id]), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => $cmsUserRole->id
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_photo_upload()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();
        $cmsUser = CmsUserFactory::new()->role($cmsUserRole->id)->create();

        $filesystem = Storage::fake('cms_users');

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('cmsUsers.update', [$cmsUser->id]), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => $cmsUserRole->id,
            'photo' => UploadedFile::fake()->image('photo.png')
        ]);

        Storage::disk('cms_users')->assertExists(
            $filesystem->getPathUsingId($cmsUser->id, 'photos/photo.png')
        );
    }

    public function test_admin_cms_users_resource_photo_delete()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();

        $filesystem = Storage::fake('cms_users');

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('cmsUsers.store'), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => $cmsUserRole->id,
            'password' => $password = bcrypt(fake()->password(8, 8)),
            'password_confirmation' => $password,
            'photo' => UploadedFile::fake()->image('photo.png')
        ]);

        $cmsUser = (new CmsUser)->roleId($cmsUserRole->id)->firstOrFail();

        if (! $filesystem->exists($filesystem->getPathUsingId($cmsUser->id, 'photos/photo.png'))) {
            throw new Exception('Uploaded photo not found');
        }

        $filesystem->delete($filesystem->getPathUsingId($cmsUser->id, 'photos/photo.png'));

        $filesystem->assertMissing($filesystem->getPathUsingId($cmsUser->id, 'photos/photo.png'));
    }

    public function test_admin_cms_users_resource_validation()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('cmsUsers.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors([
            'email', 'first_name', 'last_name', 'cms_user_role_id', 'password'
        ]);
    }

    public function test_admin_cms_users_resource_destroy()
    {
        $cmsUserRole = CmsUserRoleFactory::new()->create();
        $cmsUser = CmsUserFactory::new()->role($cmsUserRole->id)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('cmsUsers.destroy', [$cmsUser->id]));

        $response->assertFound();
    }
}
