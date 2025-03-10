<?php

namespace Tests\Feature\Admin;

use App\Models\Calendar;

class AdminCalendarTest extends TestAdmin
{
    public function test_admin_calendar_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('calendar.index'));

        $response->assertOk();
    }

    public function test_admin_calendar_events()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('calendar.events'));

        $response->assertOk()->assertJson([]);
    }

    public function test_admin_calendar_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('calendar.save'), [
            'title' => fake()->word(),
            'description' => fake()->text(),
            'content' => fake()->text()
        ]);

        $response->assertFound();

        (new Calendar)->newQuery()->delete();
    }

    public function test_admin_calendar_update()
    {
        $cmsUser = $this->getFullAccessCmsUser();

        $model = (new Calendar)->create([
            'cms_user_id' => $cmsUser->id,
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'start' => fake()->dateTimeBetween('now')->format(DATE_ATOM),
            'end' => fake()->dateTimeBetween('now', '+2 week')->format(DATE_ATOM)
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->put(cms_route('calendar.save'), [
            'id'  => $model->id,
            'title' => fake()->word()
        ]);

        $response->assertFound();

        $model->delete();
    }

    public function test_admin_calendar_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('calendar.save'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_calendar_destroy()
    {
        $cmsUser = $this->getFullAccessCmsUser();

        $model = (new Calendar)->create([
            'cms_user_id' => $cmsUser->id,
            'title' => fake()->word(),
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->delete(cms_route('calendar.destroy'), [
            'id' => $model->id
        ]);

        $response->assertFound();
    }
}
