<?php

namespace Tests\Feature\Admin;

use App\Models\Note;

class AdminNotesTest extends TestAdmin
{
    public function test_admin_notes_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('notes.index'));

        $response->assertOk();
    }

    public function test_admin_notes_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('notes.save'), [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'content' => fake()->text()
        ]);

        $response->assertFound();
    }

    public function test_admin_notes_update()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('notes.save'), [
            'id' => (new Note)->valueOrFail('id'),
            'title' => fake()->word()
        ]);

        $response->assertFound();
    }

    public function test_admin_notes_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('notes.save'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_notes_destroy()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('notes.destroy'), [
            'id' => (new Note)->valueOrFail('id')
        ]);

        $response->assertFound();
    }
}
