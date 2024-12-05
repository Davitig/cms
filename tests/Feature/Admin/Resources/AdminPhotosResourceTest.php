<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use App\Models\Gallery\Gallery;
use App\Models\Photo;

class AdminPhotosResourceTest extends TestAdminResources
{
    public function test_admin_photos_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('photos.index', [
            $this->createGalleryModel('photos')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_photos_resource_create()
    {
        $response = $this->actingAs($this->getUser())->getJson(cms_route('photos.create', [
            $this->getGalleryModel('photos')->id
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_photos_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('photos.store', [
            $this->getGalleryModel('photos')->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_photos_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->getJson(cms_route('photos.edit', [
            $this->getGalleryModel('photos')->id, (new Photo)->valueOrFail('id')
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_photos_resource_update()
    {
        $photo = (new Photo)->firstOrFail();

        $response = $this->actingAs($this->getUser())->put(cms_route('photos.update', [
            $photo->gallery_id, $photo->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_photos_resource_validate_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('photos.store', [
            $this->getGalleryModel('photos')->id
        ]), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_photos_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('photos.visibility', [
            (new Photo)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_photos_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('photos.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_photos_resource_destroy()
    {
        $photo = (new Photo)->firstOrFail();

        $response = $this->actingAs($this->getUser())->delete(cms_route('photos.destroy', [
            $photo->gallery_id, $photo->id
        ]));

        (new Gallery)->whereType('photos')->delete();

        (new Collection)->whereType('galleries')->delete();

        $response->assertFound();
    }
}
