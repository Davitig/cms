<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use App\Models\Gallery\Gallery;
use App\Models\Video;

class AdminVideosResourceTest extends TestAdminResources
{
    public function test_admin_videos_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('videos.index', [
            $this->getGalleryModel('videos')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_videos_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('videos.create', [
            $this->getGalleryModel('videos')->id
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_videos_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('videos.store', [
            $this->getGalleryModel('videos')->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_videos_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('videos.edit', [
            $this->getGalleryModel('videos')->id, (new Video)->valueOrFail('id')
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_videos_resource_update()
    {
        $video = (new Video)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('videos.update', [
            $video->gallery_id, $video->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_videos_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('videos.store', [
            $this->getGalleryModel('videos')->id
        ]), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_videos_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('videos.visibility', [
            (new Video)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_videos_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('videos.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_videos_resource_destroy()
    {
        $video = (new Video)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('videos.destroy', [
            $video->gallery_id, $video->id
        ]));

        $response->assertFound();
    }
}
