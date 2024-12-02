<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use App\Models\Menu;
use App\Models\Page\Page;
use Tests\TestCase;

class AdminPagesResourceTest extends TestCase
{
    /**
     * Get the menu id.
     *
     * @return int
     */
    protected function getMenuId(): int
    {
        return (new Menu)->valueOrFail('id');
    }

    public function test_admin_pages_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('pages.index', [
            $this->getMenuId()
        ]));

        $response->assertOk();
    }

    public function test_admin_pages_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('pages.create', [
            $this->getMenuId()
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_pages_resource_store()
    {
        (new Page)->create([
            'menu_id' => $this->getMenuId(),
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2),
            'type' => 'page'
        ]);

        $response = $this->actingAs($this->getUser())->post(cms_route('pages.store', [
            $this->getMenuId()
        ]), [
            'title' => fake()->sentence(2),
            'type' => 'page'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_pages_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('pages.edit', [
            $this->getMenuId(), (new Page)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_pages_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('pages.update', [
            $this->getMenuId(), (new Page)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'type' => 'page'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_pages_resource_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('pages.destroy', [
            $this->getMenuId(), (new Page)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_pages_resource_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('pages.store', [
            $this->getMenuId(), (new Page)->valueOrFail('id')
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_pages_resource_validate_slug_unique()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('pages.store', [
            $this->getMenuId(), (new Page)->valueOrFail('id')
        ]), [
            'slug' => (new Page)->firstOrFail()->slug
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_pages_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('pages.visibility', [
            (new Page)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_pages_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('pages.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_pages_resource_transfer()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('pages.transfer', [
            ($page = (new Page)->firstOrFail())->menu_id
        ]), [
            'id' => $page->id,
            'column' => 'menu_id',
            'column_value' => (new Menu)->create(['title' => fake()->sentence(2)])->id,
        ]);

        $response->assertFound();
    }

    public function test_admin_pages_resource_get_listable_types()
    {
        (new Collection)->newQuery()->delete();

        $collectionId = (new Collection)->create([
            'title' => 'Articles', 'type' => 'articles'
        ])->id;

        $response = $this->actingAs($this->getUser())->get(
            cms_route('pages.getListableTypes', ['type' => 'collections'])
        );

        $this->assertArrayHasKey($collectionId, $response->json());
    }

    public function test_admin_pages_resource_get_templates()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('pages.templates'));

        $response->assertOk()->assertJsonIsArray();
    }

    public function test_admin_pages_resource_collapse()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('pages.collapse'), [
            'id' => (new Page)->valueOrFail('id'),
        ]);

        $response->assertOk()->assertJson([]);
    }
}
