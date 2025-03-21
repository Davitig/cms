<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Page\Page;
use Database\Factories\CollectionFactory;
use Database\Factories\MenuFactory;
use Database\Factories\Page\PageFactory;
use Database\Factories\Page\PageLanguageFactory;
use Tests\Feature\Admin\TestAdmin;

class AdminPagesResourceTest extends TestAdmin
{
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
        [$menu, $pages] = $this->createPages(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('pages.index', [$menu->id]));

        $pages->map->delete();
        $menu->delete();

        $response->assertOk();
    }

    public function test_admin_pages_resource_create()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('pages.create', [$menu->id]));

        $menu->delete();

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
        )->post(cms_route('pages.store', [$menu->id]), [
            'title' => fake()->sentence(2),
            'type' => 'page'
        ]);

        (new Page)->menuId($menu->id)->firstOrFail()->delete();
        $menu->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_pages_resource_edit()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('pages.edit', [$menu->id, $page->id]));

        $page->delete();
        $menu->delete();

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
        )->put(cms_route('pages.update', [$menu->id, $page->id]), [
            'title' => fake()->sentence(2),
            'type' => 'page'
        ]);

        $page->delete();
        $menu->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_pages_resource_validate_title_required()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('pages.store', [$menu->id]), [
            'slug' => fake()->slug(2)
        ]);

        $menu->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_pages_resource_validate_slug_unique()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('pages.store', [$menu->id, $page->id]), [
            'slug' => $page->slug
        ]);

        $page->delete();
        $menu->delete();

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_pages_resource_visibility()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.visibility', [$page->id]));

        $page->delete();
        $menu->delete();

        $response->assertFound();
    }

    public function test_admin_pages_resource_update_position()
    {
        [$menu, $pages] = $this->createPages(3);

        $newData = $ids = [];

        foreach ($pages as $page) {
            $newData[] = ['id' => $ids[] = $page->id, 'pos' => $page->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.updatePosition'), ['data' => $newData]);

        $updatedData = (new Page)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $pages->map->delete();
        $menu->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_pages_resource_transfer()
    {
        [$menu, $page] = $this->createPages();

        $newMenu = MenuFactory::new()->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.transfer', [$menu->id]), [
            'id' => $page->id,
            'column' => 'menu_id',
            'column_value' => $newMenu->id
        ]);

        $updatedPageMenuId = (new Page)->whereKey($page->id)->value('menu_id');

        $page->delete();
        $menu->delete();
        $newMenu->delete();

        $this->assertSame($newMenu->id, $updatedPageMenuId);
    }

    public function test_admin_pages_resource_get_collection_types()
    {
        $collection = CollectionFactory::new()->articleType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('pages.getCollectionTypes', ['type' => 'articles']));

        $collection->delete();

        $this->assertArrayHasKey($collection->id, $response->json());
    }

    public function test_admin_pages_resource_collapse()
    {
        [$menu, $page] = $this->createPages();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('pages.collapse'), ['id' => $page->id]);

        $oldCollapse = $page->collapse;
        $newCollapse = (new Page)->whereKey($page->id)->value('collapse');

        $page->delete();
        $menu->delete();

        $this->assertNotSame($oldCollapse, $newCollapse);
    }

    public function test_admin_pages_resource_destroy()
    {
        [$menu, $page] = $this->createPages();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('pages.destroy', [$menu->id, $page->id]));

        $page->delete();
        $menu->delete();

        $response->assertFound();
    }
}
