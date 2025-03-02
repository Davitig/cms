<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Menu;
use App\Models\Page\Page;
use App\Models\Page\PageFile;

class AdminPageFilesResourceTest extends TestAdminResources
{
    public function test_admin_page_files_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('pages.files.index', [
            $this->getPageModel()->id
        ]));

        $response->assertOk();
    }

    public function test_admin_page_files_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('pages.files.create', [
            $this->getPageModel()->id
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_page_files_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('pages.files.store', [
            $this->getPageModel()->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_page_files_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('pages.files.edit', [
            $this->getPageModel()->id, (new PageFile)->valueOrFail('id')
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_page_files_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('pages.files.update', [
            $this->getPageModel()->id, (new PageFile)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_page_files_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('pages.files.store', [
            $this->getPageModel()->id
        ]), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_page_files_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('pages.files.visibility', [
            (new PageFile)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_page_files_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('pages.files.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_page_files_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('pages.files.destroy', [
            $this->getPageModel()->id, (new PageFile)->valueOrFail('id')
        ]));

        (new Page)->newQuery()->delete();

        (new Menu)->newQuery()->delete();

        $response->assertFound();
    }
}
