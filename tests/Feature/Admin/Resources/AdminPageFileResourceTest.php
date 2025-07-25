<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Page\PageFile;
use Database\Factories\MenuFactory;
use Database\Factories\Page\PageFactory;
use Database\Factories\Page\PageFileFactory;
use Database\Factories\Page\PageFileLanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminPageFileResourceTest extends TestAdmin
{
    use RefreshDatabase;

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

        return array_merge([$page], ($files ? [$files] : []));
    }

    public function test_admin_page_files_resource_index()
    {
        [$page] = $this->createPageFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('pages.files.index', [$page->id]));

        $response->assertOk();
    }

    public function test_admin_page_files_resource_create()
    {
        [$page] = $this->createPageFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('pages.files.create', [$page->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_page_files_resource_store()
    {
        [$page] = $this->createPageFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('pages.files.store', [$page->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_page_files_resource_edit()
    {
        [$page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson($this->cmsRoute('pages.files.edit', [$page->id, $file->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_page_files_resource_update()
    {
        [$page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.files.update', [$page->id, $file->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_page_files_resource_validate_required()
    {
        [$page] = $this->createPageFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('pages.files.store', [$page->id]), [
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_page_files_resource_visibility()
    {
        [$page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.files.visibility', [$file->id]));

        $response->assertFound();
    }

    public function test_admin_page_files_resource_update_position()
    {
        [$page, $files] = $this->createPageFiles(5);

        $data = $ids = [];
        $startItem = $files->first();
        $endItem = $files->last();

        $data[] = ['id' => $ids[] = $startItem->id, 'pos' => $endItem->position];
        foreach ($files as $file) {
            if ($file->id == $startItem->id || $file->id == $endItem->id) {
                continue;
            }

            $data[] = ['id' => $ids[] = $file->id, 'pos' => $file->position - 1];
        }
        $data[] = ['id' => $ids[] = $endItem->id, 'pos' => $endItem->position - 1];

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.files.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id,
            'foreign_key' => 'page_id'
        ]);

        $updatedData = (new PageFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_page_files_resource_destroy()
    {
        [$page, $file] = $this->createPageFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('pages.files.destroy', [
            $page->id, $file->id
        ]));

        $response->assertFound();
    }
}
