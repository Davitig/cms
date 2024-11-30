<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Menu;
use Tests\TestCase;

class AdminMenusResourceTest extends TestCase
{
    public function test_admin_menus_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('menus.index'));

        $response->assertOk();
    }

    public function test_admin_menus_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('menus.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_store()
    {
        $this->seed();

        $response = $this->actingAs($this->getUser())->post(cms_route('menus.store'), [
            'title' => 'List of Pages'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_resource_edit()
    {
        $id = (new Menu)->firstOrFail()->id;

        $response = $this->actingAs($this->getUser())->get(cms_route('menus.edit', [$id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_update()
    {
        $id = (new Menu)->firstOrFail()->id;

        $response = $this->actingAs($this->getUser())->put(cms_route('menus.update', [$id]), [
            'title' => 'List of Pages'
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_destroy()
    {
        $id = (new Menu)->firstOrFail()->id;

        $response = $this->actingAs($this->getUser())->delete(cms_route('menus.destroy', [$id]));

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_resource_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('menus.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_set_main()
    {
        $id = (new Menu)->firstOrFail()->id;

        $response = $this->actingAs($this->getUser())->post(cms_route('menus.setMain'), [
            'id' => $id,
        ]);

        $response->assertOk()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_set_main_is_unique()
    {
        $this->assertEquals(1, (new Menu)->whereMain(1)->count());
    }

    public function test_admin_menus_set_main_validate_id_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('menus.setMain'), [
            // empty data
        ]);

        $response->assertUnprocessable();
    }
}
