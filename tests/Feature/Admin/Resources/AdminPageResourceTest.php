<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Page\Page;
use Database\Factories\CollectionFactory;
use Database\Factories\MenuFactory;
use Database\Factories\Page\PageFactory;
use Database\Factories\Page\PageLanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminPageResourceTest extends TestAdmin
{
    use RefreshDatabase;

    /**
     * Create a new pages.
     *
     * @param  int|null  $times
     * @param  bool  $createPages
     * @return array
     */
    protected function createPages(?int $times = null, bool $createPages = true): array
    {
        $menu = MenuFactory::new()->create();

        if ($createPages) {
            $pages = PageFactory::new()->count($times)->menuId($menu->id)->has(
                PageLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create();
        } else {
            $pages = null;
        }

        return array_merge([$menu], ($pages ? [$pages] : []));
    }

    public function test_admin_pages_resource_index()
    {
        [$menu] = $this->createPages(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('pages.index', [$menu->id]));

        $response->assertOk();
    }

    public function test_admin_pages_resource_create()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('pages.create', [$menu->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_pages_resource_store()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('pages.store', [$menu->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'type' => 'page'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_pages_resource_edit()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('pages.edit', [$menu->id, $page->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_pages_resource_update()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.update', [$menu->id, $page->id]), [
            'title' => fake()->sentence(2),
            'type' => 'page'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_pages_resource_validate_title_required()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('pages.store', [$menu->id]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_pages_resource_validate_slug_unique()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('pages.store', [$menu->id, $page->id]), [
            'slug' => $page->slug
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_pages_resource_visibility()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.visibility', [$page->id]));

        $response->assertFound();
    }

    public function test_admin_pages_resource_update_position()
    {
        [$menu, $pages] = $this->createPages(5);

        $data = $ids = [];
        $startItem = $pages->first();
        $endItem = $pages->last();

        $data[] = ['id' => $ids[] = $startItem->id, 'pos' => $endItem->position];
        foreach ($pages as $file) {
            if ($file->id == $startItem->id || $file->id == $endItem->id) {
                continue;
            }

            $data[] = ['id' => $ids[] = $file->id, 'pos' => $file->position - 1];
        }
        $data[] = ['id' => $ids[] = $endItem->id, 'pos' => $endItem->position - 1];

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id
        ]);

        $updatedData = (new Page)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_pages_resource_transfer()
    {
        [$menu, $page] = $this->createPages();

        $newMenu = MenuFactory::new()->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put($this->cmsRoute('pages.transfer', [$menu->id]), [
            'id' => $page->id,
            'column' => 'menu_id',
            'column_value' => $newMenu->id
        ]);

        $updatedPageMenuId = (new Page)->whereKey($page->id)->value('menu_id');

        $this->assertSame($newMenu->id, $updatedPageMenuId);
    }

    public function test_admin_pages_resource_get_collection_types()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('pages.get_listable_types', ['type' => 'articles']));

        $this->assertArrayHasKey($collection->id, $response->json());
    }

    public function test_admin_pages_resource_destroy()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete($this->cmsRoute('pages.destroy', [$menu->id, $page->id]));

        $response->assertFound();
    }
}
