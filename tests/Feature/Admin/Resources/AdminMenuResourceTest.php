<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Menu;
use Database\Factories\MenuFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;

class AdminMenuResourceTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_menus_resource_index()
    {
        MenuFactory::new()->times(5)->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('menus.index'));

        $response->assertOk();
    }

    public function test_admin_menus_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('menus.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('menus.store'), [
            'title' => fake()->sentence(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_resource_edit()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('menus.edit', [$menu->id]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_resource_update()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('menus.update', [$menu->id]), [
            'title' => 'List of Pages',
            'main' => 1
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('menus.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_menus_resource_destroy()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('menus.destroy', [$menu->id]));

        $response->assertFound();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_menus_update_main()
    {
        $menu = MenuFactory::new()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('menus.updateMain'), ['id' => $menu->id]);

        $response->assertOk()->assertSessionHasNoErrors();
    }

    public function test_admin_menus_main_is_unique()
    {
        $menus = MenuFactory::new()->times(2)->main(1)->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('menus.updateMain'), ['id' => $menus->first()->id]);

        $mainCount = (new Menu)->whereMain(1)->count();

        $this->assertEquals(1, $mainCount);
    }

    public function test_admin_menus_update_main_validate_id_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('menus.updateMain'), [
            // empty data
        ]);

        $response->assertUnprocessable();
    }
}
