<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\CmsUser;
use App\Models\CmsUserRole;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUsersResourceTest extends TestAdmin
{
    public function test_admin_cms_users_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsUsers.index'));

        $response->assertOk();
    }

    public function test_admin_cms_users_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsUsers.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('cmsUsers.store'), [
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
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('cmsUsers.edit', [
            (new CmsUser)->orderDesc()->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_cms_users_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('cmsUsers.update', [
            (new CmsUser)->orderDesc()->valueOrFail('id')
        ]), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => (new CmsUserRole)->valueOrFail('id')
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_cms_users_resource_photo_upload()
    {
        $filesystem = Storage::fake('cms_users');

        $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('cmsUsers.update', [
            $id = (new CmsUser)->orderDesc()->valueOrFail('id')
        ]), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => (new CmsUserRole)->valueOrFail('id'),
            'photo' => UploadedFile::fake()->image('photo.png')
        ]);

        Storage::disk('cms_users')->assertExists(
            $filesystem->getPathUsingId($id, 'photos/photo.png')
        );
    }

    /**
     * @throws \Exception
     */
    public function test_admin_cms_users_resource_photo_delete()
    {
        $filesystem = Storage::fake('cms_users');

        $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('cmsUsers.store'), [
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cms_user_role_id' => (new CmsUserRole)->valueOrFail('id'),
            'password' => $password = bcrypt(fake()->password(8, 8)),
            'password_confirmation' => $password,
            'photo' => UploadedFile::fake()->image('photo.png')
        ]);

        $id = (new CmsUser)->orderDesc()->valueOrFail('id');

        if (! $filesystem->exists($filesystem->getPathUsingId($id, 'photos/photo.png'))) {
            throw new Exception('Uploaded photo not found');
        }

        $filesystem->delete($filesystem->getPathUsingId($id, 'photos/photo.png'));

        $filesystem->assertMissing($filesystem->getPathUsingId($id, 'photos/photo.png'));
    }

    public function test_admin_cms_users_resource_validation()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('cmsUsers.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors([
            'email', 'first_name', 'last_name', 'cms_user_role_id', 'password'
        ]);
    }

    public function test_admin_cms_users_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('cmsUsers.destroy', [
            (new CmsUser)->orderDesc()->valueOrFail('id')
        ]));

        $response->assertFound();
    }
}
