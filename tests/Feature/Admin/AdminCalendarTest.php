<?php

namespace Tests\Feature\Admin;

use App\Models\Calendar;
use App\Models\CmsUser;
use Database\Factories\CalendarFactory;

class AdminCalendarTest extends TestAdmin
{
    public function test_admin_calendar_index()
    {
        $calendarList = CalendarFactory::new()->count(5)->create([
            'cms_user_id' => (new CmsUser)->valueOrFail('id')
        ]);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('calendar.index'));

        $calendarList->map->delete();

        $response->assertOk();
    }

    public function test_admin_calendar_events()
    {
        $calendarList = CalendarFactory::new()->count(5)->create([
            'cms_user_id' => (new CmsUser)->valueOrFail('id')
        ]);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('calendar.events'));

        $calendarList->map->delete();


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

        (new Calendar)->newQuery()->delete();

        $response->assertFound();
    }

    public function test_admin_calendar_update()
    {
        $cmsUser = $this->getFullAccessCmsUser();

        $calendar = CalendarFactory::new()->create([
            'cms_user_id' => $cmsUser->id
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->put(cms_route('calendar.save'), [
            'id'  => $calendar->id,
            'title' => fake()->word()
        ]);

        $calendar->delete();

        $response->assertFound();
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

        $calendar = CalendarFactory::new()->create([
            'cms_user_id' => $cmsUser->id
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->delete(cms_route('calendar.destroy'), [
            'id' => $calendar->id
        ]);

        $calendar->delete();

        $response->assertFound();
    }
}
