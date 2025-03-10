<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser;
use App\Models\Note;
use Database\Factories\NoteFactory;

class AdminNotesTest extends TestAdmin
{
    public function test_admin_notes_index()
    {
        $notes = NoteFactory::new()->count(5)->create([
            'cms_user_id' => (new CmsUser)->valueOrFail('id')
        ]);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('notes.index'));

        $notes->map->delete();

        $response->assertOk();
    }

    public function test_admin_notes_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('notes.save'), [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'content' => fake()->text()
        ]);

        (new Note)->newQuery()->delete();

        $response->assertFound();
    }

    public function test_admin_notes_update()
    {
        $cmsUser = $this->getFullAccessCmsUser();

        $note = NoteFactory::new()->create([
            'cms_user_id' => (new CmsUser)->valueOrFail('id')
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->put(cms_route('notes.save'), [
            'id'  => $note->id,
            'title' => fake()->word()
        ]);

        $note->delete();

        $response->assertFound();
    }

    public function test_admin_notes_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('notes.save'), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_notes_destroy()
    {
        $cmsUser = $this->getFullAccessCmsUser();

        $note = NoteFactory::new()->create([
            'cms_user_id' => (new CmsUser)->valueOrFail('id')
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->delete(cms_route('notes.destroy'), [
            'id' => $note->id
        ]);

        $note->delete();

        $response->assertFound();
    }
}
