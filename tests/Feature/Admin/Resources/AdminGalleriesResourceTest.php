<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Gallery\Gallery;

class AdminGalleriesResourceTest extends TestAdminResources
{
    public function test_admin_galleries_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('galleries.index', [
            $this->createCollectionModel('galleries')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_galleries_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('galleries.create', [
            $this->getCollectionModel('galleries')->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_galleries_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('galleries.store', [
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
        $response = $this->actingAs($this->getUser())->get(cms_route('galleries.edit', [
            $this->getCollectionModel('galleries')->id, (new Gallery)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_galleries_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('galleries.update', [
            $this->getCollectionModel('galleries')->id, (new Gallery)->valueOrFail('id')
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

    public function test_admin_galleries_resource_destroy()
    {
        (new Gallery)->create([
            'collection_id' => $collectionId = $this->getCollectionModel('galleries')->id,
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

        $response = $this->actingAs($this->getUser())->delete(cms_route('galleries.destroy', [
            $collectionId, (new Gallery)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_galleries_resource_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_galleries_resource_validate_slug_unique()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
            'slug' => (new Gallery)->firstOrFail()->slug
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_collections_resource_validate_invalid_selection()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('galleries.store', [
            $this->getCollectionModel('galleries')->id
        ]), [
            'admin_order_by' => 'id',
            'web_order_by' => 'title'
        ]);

        $response->assertFound()->assertSessionHasErrors(['admin_order_by', 'web_order_by']);
    }

    public function test_admin_galleries_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('galleries.visibility', [
            (new Gallery)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_galleries_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('galleries.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_galleries_resource_transfer()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('galleries.transfer', [
            ($gallery = (new Gallery)->firstOrFail())->collection_id
        ]), [
            'id' => $gallery->id,
            'column' => 'collection_id',
            'column_value' => $this->createCollectionModel('galleries')->id
        ]);

        $gallery->delete();

        $response->assertFound();
    }
}
