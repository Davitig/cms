<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Menu;
use Tests\Feature\Admin\TestAdmin;

class AdminMenusResourceTest extends TestAdmin
{
    public function test_admin_menus_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('menus.index'));

        $response->assertOk();
    }

    public function test_admin_menus_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('menus.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('menus.store'), [
            'title' => fake()->sentence(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('menus.edit', [(new Menu)->valueOrFail('id')]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('menus.update', [(new Menu)->valueOrFail('id')]), [
            'title' => 'List of Pages',
            'main' => 1
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('menus.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_set_main()
    {
        (new Menu)->create(['title' => 'Menu Title']);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('menus.setMain'), [
            'id' => (new Menu)->valueOrFail('id')
        ]);

        $response->assertOk()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_main_is_unique()
    {
        $this->assertEquals(1, (new Menu)->whereMain(1)->count());
    }

    public function test_admin_menus_set_main_validate_id_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('menus.setMain'), [
            // empty data
        ]);

        $response->assertUnprocessable();
    }

    public function test_admin_menus_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('menus.destroy', [
            (new Menu)->valueOrFail('id')
        ]));

        (new Menu)->newQuery()->delete();

        $response->assertFound();
    }
}
