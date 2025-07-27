<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Admin\TestAdmin;

class AdminCmsUserImageTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_cms_users_resource_photo_upload()
    {
        $this->imageUpload('photo');
    }

    public function test_admin_cms_users_resource_photo_delete()
    {
        $this->imageDelete('photo');
    }

    public function test_admin_cms_users_resource_cover_upload()
    {
        $this->imageUpload('cover');
    }

    public function test_admin_cms_users_resource_cover_delete()
    {
        $this->imageDelete('cover');
    }

    public function imageUpload(string $type, ?string $name = null)
    {
        $name ??= $type;

        $cmsUser = $this->createCmsUser();

        $filesystem = Storage::fake('cms_users');

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('cms_users.image.store', [$cmsUser->id]), [
            'image_type' => $type,
            $type => UploadedFile::fake()->image($name . '.png')
        ]);

        $response->assertFound();

        Storage::disk('cms_users')->assertExists(
            $filesystem->getPathUsingId($cmsUser->id, 'photos/' . $name . '.png')
        );
    }

    public function imageDelete(string $type, ?string $name = null)
    {
        $name ??= $type;

        $cmsUser = $this->createCmsUser();

        $filesystem = Storage::fake('cms_users');

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('cms_users.image.store', [$cmsUser->id]), [
            'image_type' => $type,
            $type => UploadedFile::fake()->image($name . '.png')
        ]);

        $path = $filesystem->getPathUsingId($cmsUser->id, 'photos/' . $name . '.png');

        $filesystem->assertExists($path);

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('cms_users.image.destroy', [$cmsUser->id]), [
            'image_type' => $type,
            $type => UploadedFile::fake()->image($name . '.png')
        ]);

        $filesystem->delete($path);

        $filesystem->assertMissing($path);
    }
}
