<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Video;
use Database\Factories\CollectionFactory;
use Database\Factories\GalleryFactory;
use Database\Factories\Video\VideoFactory;
use Database\Factories\Video\VideoLanguageFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\Admin\TestAdmin;

class AdminVideosResourceTest extends TestAdmin
{
    /**
     * Create a new video files.
     *
     * @param  int|null  $times
     * @param  bool  $createVideos
     * @return array
     */
    protected function createVideos(?int $times = null, bool $createVideos = true): array
    {
        $collection = CollectionFactory::new()->galleryType()->create();
        $gallery = GalleryFactory::new()->collectionId($collection->id)->videoType()->create();

        if ($createVideos) {
            $videos = VideoFactory::new()->count($times)->has(
                VideoLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create(['gallery_id' => $gallery->id]);
        } else {
            $videos = null;
        }

        return array_merge([$collection, $gallery], ($videos ? [$videos] : []));
    }

    public function test_admin_videos_resource_index()
    {
        [$collection, $gallery, $videos] = $this->createVideos(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('videos.index', [$gallery->id]));

        $videos->map->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_videos_resource_create()
    {
        [$collection, $gallery] = $this->createVideos(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('videos.create', [$gallery->id]));

        $gallery->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_videos_resource_store()
    {
        [$collection, $gallery] = $this->createVideos(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('videos.store', [$gallery->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        (new Video)->galleryId($gallery->id)->firstOrFail()->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_videos_resource_edit()
    {
        [$collection, $gallery, $video] = $this->createVideos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('videos.edit', [$gallery->id, $video->id]));

        $video->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_videos_resource_update()
    {
        [$collection, $gallery, $video] = $this->createVideos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('videos.update', [$gallery->id, $video->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $video->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_videos_resource_validate_required()
    {
        [$collection, $gallery] = $this->createVideos(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('videos.store', [$gallery->id]), [
            'file' => fake()->imageUrl()
        ]);

        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_videos_resource_visibility()
    {
        [$collection, $gallery, $video] = $this->createVideos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('videos.visibility', [$video->id]));

        $video->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_videos_resource_update_position()
    {
        [$collection, $gallery, $videos] = $this->createVideos(3);

        $newData = $ids = [];

        foreach ($videos as $video) {
            $newData[] = ['id' => $ids[] = $video->id, 'pos' => $video->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('videos.updatePosition'), ['data' => $newData]);

        $updatedData = (new Video)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $videos->map->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_videos_resource_destroy()
    {
        [$collection, $gallery, $video] = $this->createVideos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('videos.destroy', [$gallery->id, $video->id]));

        $video->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound();
    }
}
