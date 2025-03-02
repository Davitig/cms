<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use App\Models\Gallery\Gallery;

class AdminGalleriesResourceTest extends TestAdminResources
{
    public function test_admin_galleries_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('galleries.index', [
            $this->getCollectionModel('galleries')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_galleries_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('galleries.create', [
            $this->getCollectionModel('galleries')->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_galleries_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
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

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_galleries_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('galleries.edit', [
            $this->getCollectionModel('galleries')->id, (new Gallery)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_galleries_resource_update()
    {
        $gallery = (new Gallery)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('galleries.update', [
            $gallery->collection_id, $gallery->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'admin_order_by' => 'position',
            'admin_sort' => 'desc',
            'admin_per_page' => 30,
            'web_order_by' => 'created_at',
            'web_sort' => 'desc',
            'web_per_page' => 30
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_galleries_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_galleries_resource_validate_slug_unique()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
            'slug' => (new Gallery)->valueOrFail('slug')
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_galleries_resource_validate_invalid_selection()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
            'admin_order_by' => 'id',
            'web_order_by' => 'title'
        ]);

        $response->assertFound()->assertSessionHasErrors(['admin_order_by', 'web_order_by']);
    }

    public function test_admin_galleries_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('galleries.visibility', [
            (new Gallery)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_galleries_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('galleries.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_galleries_resource_transfer()
    {
        $gallery = (new Gallery)->firstOrFail();

        $collectionId = (new Collection)->whereKeyNot($gallery->collection_id)->value('id');

        if (is_null($collectionId)) {
            $collectionId = $this->createCollectionModel('galleries')->id;
        }

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('galleries.transfer', [
            $gallery->collection_id
        ]), [
            'id' => $gallery->id,
            'column' => 'collection_id',
            'column_value' => $collectionId
        ]);

        $response->assertFound();
    }

    public function test_admin_galleries_resource_destroy()
    {
        $gallery = (new Gallery)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('galleries.destroy', [
            $gallery->collection_id, $gallery->id
        ]));

        $response->assertFound();
    }
}
