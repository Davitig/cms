<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Gallery\Gallery;
use Database\Factories\CollectionFactory;
use Database\Factories\GalleryFactory;
use Database\Factories\GalleryLanguageFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\Admin\TestAdmin;

class AdminGalleriesResourceTest extends TestAdmin
{
    /**
     * Create a new galleries.
     *
     * @param  int|null  $times
     * @return array
     */
    public function createGalleries(?int $times = null): array
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $galleries = GalleryFactory::new()->count($times)->collectionId($collection->id)->has(
            GalleryLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->create();

        return [$collection, $galleries];
    }

    public function test_admin_galleries_resource_index()
    {
        [$collection, $galleries] = $this->createGalleries(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('galleries.index', [$collection->id]));

        $galleries->map->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_galleries_resource_create()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('galleries.create', [$collection->id]));

        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_galleries_resource_store()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('galleries.store', [$collection->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'type' => 'photos',
            'admin_order_by' => 'position',
            'admin_sort' => 'asc',
            'admin_per_page' => 20,
            'web_order_by' => 'created_at',
            'web_sort' => 'asc',
            'web_per_page' => 20
        ]);

        (new Gallery)->collectionId($collection->id)->orderDesc()->firstOrFail()->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_galleries_resource_edit()
    {
        [$collection, $gallery] = $this->createGalleries();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('galleries.edit', [$collection->id, $gallery->id]));

        $gallery->delete();
        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_galleries_resource_update()
    {
        [$collection, $gallery] = $this->createGalleries();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('galleries.update', [$collection->id, $gallery->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'admin_order_by' => 'position',
            'admin_sort' => 'desc',
            'admin_per_page' => 30,
            'web_order_by' => 'created_at',
            'web_sort' => 'desc',
            'web_per_page' => 30
        ]);

        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_galleries_resource_validate_title_required()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('galleries.store', [$collection->id]), [
            'slug' => fake()->slug(2)
        ]);

        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_galleries_resource_validate_slug_unique()
    {
        [$collection, $gallery] = $this->createGalleries();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('galleries.store', [$collection->id]), [
            'slug' => $gallery->slug
        ]);

        $gallery->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_galleries_resource_validate_invalid_selection()
    {
        $collection = CollectionFactory::new()->galleryType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('galleries.store', [$collection->id]), [
            'admin_order_by' => 'id',
            'web_order_by' => 'title'
        ]);

        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['admin_order_by', 'web_order_by']);
    }

    public function test_admin_galleries_resource_visibility()
    {
        [$collection, $gallery] = $this->createGalleries();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('galleries.visibility', [$gallery->id]));

        $gallery->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_galleries_resource_update_position()
    {
        [$collection, $galleries] = $this->createGalleries(3);

        $newData = $ids = [];

        foreach ($galleries as $gallery) {
            $newData[] = ['id' => $ids[] = $gallery->id, 'pos' => $gallery->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('galleries.updatePosition'), ['data' => $newData]);

        $updatedData = (new Gallery)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $galleries->map->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_galleries_resource_transfer()
    {
        [$collection, $gallery] = $this->createGalleries();

        $newCollection = CollectionFactory::new()->articleType()->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('galleries.transfer', [$collection->id]), [
            'id' => $gallery->id,
            'column' => 'collection_id',
            'column_value' => $newCollection->id
        ]);

        $updatedGalleryCollectionId = (new Gallery)->whereKey($gallery->id)
            ->value('collection_id');

        $gallery->delete();
        $collection->delete();
        $newCollection->delete();

        $this->assertSame($newCollection->id, $updatedGalleryCollectionId);
    }

    public function test_admin_galleries_resource_destroy()
    {
        [$collection, $gallery] = $this->createGalleries();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('galleries.destroy', [$collection->id, $gallery->id]));

        $gallery->delete();
        $collection->delete();

        $response->assertFound();
    }
}
