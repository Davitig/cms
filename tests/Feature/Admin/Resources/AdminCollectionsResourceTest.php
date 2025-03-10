<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use Database\Factories\CollectionFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminCollectionsResourceTest extends TestAdmin
{
    public function test_admin_collections_resource_index()
    {
        $collections = CollectionFactory::new()->times(5)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('collections.index'));

        $collections->map->delete();

        $response->assertOk();
    }

    public function test_admin_collections_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('collections.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_collections_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('collections.store'), [
            'title' => 'List of articles',
            'type' => 'articles',
            'admin_order_by' => 'position',
            'admin_sort' => 'asc',
            'admin_per_page' => 20,
            'web_order_by' => 'created_at',
            'web_sort' => 'asc',
            'web_per_page' => 20
        ]);

        (new Collection)->orderDesc()->firstOrFail()->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_collections_resource_edit()
    {
        $collection = CollectionFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('collections.edit', [$collection->id]));

        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_collections_resource_update()
    {
        $collection = CollectionFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('collections.update', [$collection->id]), [
            'title' => 'List of news',
            'admin_order_by' => 'position',
            'admin_sort' => 'desc',
            'admin_per_page' => 30,
            'web_order_by' => 'created_at',
            'web_sort' => 'desc',
            'web_per_page' => 30
        ]);

        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_collections_resource_validate_invalid_selection()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('collections.store'), [
            'admin_order_by' => 'id',
            'web_order_by' => 'title'
        ]);

        $response->assertFound()->assertSessionHasErrors(['admin_order_by', 'web_order_by']);
    }

    public function test_admin_collections_resource_destroy()
    {
        $collection = CollectionFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('collections.destroy', [$collection->id]));

        $collection->delete();

        $response->assertFound();
    }
}
