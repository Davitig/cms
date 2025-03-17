<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Photo;
use Database\Factories\CollectionFactory;
use Database\Factories\GalleryFactory;
use Database\Factories\Photo\PhotoFactory;
use Database\Factories\Photo\PhotoLanguageFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\Admin\TestAdmin;

class AdminPhotosResourceTest extends TestAdmin
{
    /**
     * Create a new photo files.
     *
     * @param  int|null  $times
     * @param  bool  $createPhotos
     * @return array
     */
    public function createPhotos(?int $times = null, bool $createPhotos = true): array
    {
        $collection = CollectionFactory::new()->galleryType()->create();
        $gallery = GalleryFactory::new()->collectionId($collection->id)->photoType()->create();

        if ($createPhotos) {
            $photos = PhotoFactory::new()->count($times)->has(
                PhotoLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create(['gallery_id' => $gallery->id]);
        } else {
            $photos = null;
        }

        return array_merge([$collection, $gallery], ($photos ? [$photos] : []));
    }

    public function test_admin_photos_resource_index()
    {
        [$collection, $gallery, $photos] = $this->createPhotos(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('photos.index', [$gallery->id]));

        $photos->map->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_photos_resource_create()
    {
        [$collection, $gallery] = $this->createPhotos(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('photos.create', [$gallery->id]));

        $gallery->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_photos_resource_store()
    {
        [$collection, $gallery] = $this->createPhotos(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('photos.store', [$gallery->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        (new Photo)->galleryId($gallery->id)->firstOrFail()->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_photos_resource_edit()
    {
        [$collection, $gallery, $photo] = $this->createPhotos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('photos.edit', [$gallery->id, $photo->id]));

        $photo->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_photos_resource_update()
    {
        [$collection, $gallery, $photo] = $this->createPhotos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('photos.update', [$gallery->id, $photo->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $photo->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_photos_resource_validate_required()
    {
        [$collection, $gallery] = $this->createPhotos(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('photos.store', [$gallery->id]), [
            'file' => fake()->imageUrl()
        ]);

        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_photos_resource_visibility()
    {
        [$collection, $gallery, $photo] = $this->createPhotos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('photos.visibility', [$photo->id]));

        $photo->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_photos_resource_update_position()
    {
        [$collection, $gallery, $photos] = $this->createPhotos(3);

        $newData = $ids = [];

        foreach ($photos as $photo) {
            $newData[] = ['id' => $ids[] = $photo->id, 'pos' => $photo->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('photos.updatePosition'), ['data' => $newData]);

        $updatedData = (new Photo)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $photos->map->delete();
        $gallery->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_photos_resource_destroy()
    {
        [$collection, $gallery, $photo] = $this->createPhotos();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('photos.destroy', [$gallery->id, $photo->id]));

        $photo->delete();
        $gallery->delete();
        $collection->delete();

        $response->assertFound();
    }
}
