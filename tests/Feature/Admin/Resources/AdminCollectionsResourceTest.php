<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;

class AdminCollectionsResourceTest extends TestAdminResources
{
    public function test_admin_collections_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('collections.index'));

        $response->assertOk();
    }

    public function test_admin_collections_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('collections.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_collections_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
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

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_collections_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('collections.edit', [(new Collection)->valueOrFail('id')]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_collections_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('collections.update', [
            (new Collection)->orderDesc()->valueOrFail('id')
        ]), [
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
            $this->getFullAccessCmsUser()
        )->post(cms_route('collections.store'), [
            'admin_order_by' => 'id',
            'web_order_by' => 'title'
        ]);

        $response->assertFound()->assertSessionHasErrors(['admin_order_by', 'web_order_by']);
    }

    public function test_admin_collections_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('collections.destroy', [
            $this->createCollectionModel('articles')->id
        ]));

        $response->assertFound();
    }
}
