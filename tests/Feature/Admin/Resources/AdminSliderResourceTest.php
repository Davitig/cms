<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Slider;
use Database\Factories\SliderFactory;
use Database\Factories\SliderLanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\Admin\TestAdmin;

class AdminSliderResourceTest extends TestAdmin
{
    /**
     * Create a new slider.
     *
     * @param  int|null  $times
     * @return \App\Models\Slider|\Illuminate\Database\Eloquent\Collection
     */
    public function createSlider(?int $times = null): Slider|Collection
    {
        return SliderFactory::new()->count($times)->has(
            SliderLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])), 'languages'
        )->create();
    }

    public function test_admin_slider_resource_index()
    {
        $sliderList = $this->createSlider(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('slider.index'));

        $sliderList->map->delete();

        $response->assertOk();
    }

    public function test_admin_slider_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('slider.create'));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_slider_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('slider.store'), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        (new Slider)->orderDesc()->firstOrFail()->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_slider_resource_edit()
    {
        $slider = $this->createSlider();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('slider.edit', [$slider->id]));

        $slider->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_slider_resource_update()
    {
        $slider = $this->createSlider();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('slider.update', [$slider->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $slider->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_slider_resource_validate_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('slider.store'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_slider_resource_visibility()
    {
        $slider = $this->createSlider();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('slider.visibility', [$slider->id]));

        $slider->delete();

        $response->assertFound();
    }

    public function test_admin_slider_resource_update_position()
    {
        $sliderList = $this->createSlider(3);

        $newData = $ids = [];

        foreach ($sliderList as $slider) {
            $newData[] = ['id' => $ids[] = $slider->id, 'pos' => $slider->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('slider.updatePosition'), ['data' => $newData]);

        $updatedData = (new Slider)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $sliderList->map->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_slider_resource_destroy()
    {
        $slider = $this->createSlider();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('slider.destroy', [$slider->id]));

        $slider->delete();

        $response->assertFound();
    }
}
