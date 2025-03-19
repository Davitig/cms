<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Page\PageFile;
use Database\Factories\MenuFactory;
use Database\Factories\Page\PageFactory;
use Database\Factories\Page\PageFileFactory;
use Database\Factories\Page\PageFileLanguageFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminPageFilesResourceTest extends TestAdmin
{
    /**
     * Create a new page files.
     *
     * @param  int|null  $times
     * @param  bool  $createFiles
     * @return array
     */
    protected function createPageFiles(?int $times = null, bool $createFiles = true): array
    {
        $menu = MenuFactory::new()->create();

        $page = PageFactory::new()->create(['menu_id' => $menu->id]);

        if ($createFiles) {
            $files = PageFileFactory::new()->count($times)->has(
                PageFileLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create(['page_id' => $page->id]);
        } else {
            $files = null;
        }

        return array_merge([$menu, $page], ($files ? [$files] : []));
    }

    public function test_admin_page_files_resource_index()
    {
        [$menu, $page] = $this->createPageFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('pages.files.index', [$page->id]));

        $page->delete();
        $menu->delete();

        $response->assertOk();
    }

    public function test_admin_page_files_resource_create()
    {
        [$menu, $page] = $this->createPageFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('pages.files.create', [$page->id]));

        $page->delete();
        $menu->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_page_files_resource_store()
    {
        [$menu, $page] = $this->createPageFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('pages.files.store', [$page->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $page->delete();
        $menu->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_page_files_resource_edit()
    {
        [$menu, $page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('pages.files.edit', [$page->id, $file->id]));

        $page->delete();
        $menu->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_page_files_resource_update()
    {
        [$menu, $page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.files.update', [
            $page->id, $file->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $page->delete();
        $menu->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_page_files_resource_validate_required()
    {
        [$menu, $page] = $this->createPageFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('pages.files.store', [
            $page->id
        ]), [
            'file' => fake()->imageUrl()
        ]);

        $page->delete();
        $menu->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_page_files_resource_visibility()
    {
        [$menu, $page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.files.visibility', [$file->id]));

        $page->delete();
        $menu->delete();

        $response->assertFound();
    }

    public function test_admin_page_files_resource_update_position()
    {
        [$menu, $page, $files] = $this->createPageFiles(3);

        $newData = $ids = [];

        foreach ($files as $file) {
            $newData[] = ['id' => $ids[] = $file->id, 'pos' => $file->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.files.updatePosition'), ['data' => $newData]);

        $updatedData = (new PageFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $page->delete();
        $menu->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_page_files_resource_destroy()
    {
        [$menu, $page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('pages.files.destroy', [
            $page->id, $file->id
        ]));

        $page->delete();
        $menu->delete();

        $response->assertFound();
    }
}
