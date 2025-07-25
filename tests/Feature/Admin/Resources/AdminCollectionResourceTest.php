<?php

namespace Tests\Feature\Admin\Resources;

use Database\Factories\CollectionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminCollectionResourceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_collections_resource_index()
    {
        CollectionFactory::new()->times(5)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('collections.index'));

        $response->assertOk();
    }

    public function test_admin_collections_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('collections.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_collections_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('collections.store'), [
            'title' => 'List of articles',
            'type' => 'articles',
            'admin_order_by' => 'position',
            'admin_sort' => 'asc',
            'admin_per_page' => 20,
            'web_order_by' => 'created_at',
            'web_sort' => 'asc',
            'web_per_page' => 20
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_collections_resource_edit()
    {
        $collection = CollectionFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('collections.edit', [$collection->id]));

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
        )->put($this->cmsRoute('collections.update', [$collection->id]), [
            'title' => 'List of news',
            'admin_order_by' => 'position',
            'admin_sort' => 'desc',
            'admin_per_page' => 30,
            'web_order_by' => 'created_at',
            'web_sort' => 'desc',
            'web_per_page' => 30
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_collections_resource_validate_invalid_selection()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('collections.store'), [
            'admin_order_by' => 'type',
            'web_order_by' => 'title'
        ]);

        $response->assertFound()->assertSessionHasErrors(['admin_order_by', 'web_order_by']);
    }

    public function test_admin_collections_resource_destroy()
    {
        $collection = CollectionFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('collections.destroy', [$collection->id]));

        $response->assertFound();
    }
}
