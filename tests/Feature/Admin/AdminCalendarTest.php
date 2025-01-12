<?php

namespace Tests\Feature\Admin;

use App\Models\Calendar;
use Tests\TestCase;

class AdminCalendarTest extends TestCase
{
    public function test_admin_calendar_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('calendar.index'));

        $response->assertOk();
    }

    public function test_admin_calendar_events()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('calendar.events'));

        $response->assertOk()->assertJson([]);
    }

    public function test_admin_calendar_store()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('calendar.save'), [
            'title' => fake()->word(),
            'description' => fake()->paragraph(),
            'content' => fake()->text()
        ]);

        $response->assertFound();
    }

    public function test_admin_calendar_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('calendar.save'), [
            'id' => (new Calendar)->valueOrFail('id'),
            'title' => fake()->word()
        ]);

        $response->assertFound();
    }

    public function test_admin_calendar_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('calendar.save'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_calendar_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('calendar.destroy'), [
            'id' => (new Calendar)->valueOrFail('id')
        ]);

        $response->assertFound();
    }
}
