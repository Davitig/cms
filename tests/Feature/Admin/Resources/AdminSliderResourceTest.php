<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Slider;

class AdminSliderResourceTest extends TestAdminResources
{
    public function test_admin_slider_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('slider.index'));

        $response->assertOk();
    }

    public function test_admin_slider_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('slider.create'));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_slider_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('slider.store'), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_slider_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->getJson(cms_route('slider.edit', [
            (new Slider)->valueOrFail('id')
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_slider_resource_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('slider.update', [
            (new Slider)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_slider_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('slider.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_slider_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('slider.visibility', [
            (new Slider)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_slider_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('slider.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_slider_resource_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('slider.destroy', [
            (new Slider)->valueOrFail('id')
        ]));

        $response->assertFound();
    }
}
