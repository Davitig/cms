<?php

namespace Tests\Feature\Admin;

use App\Models\Note;

class AdminNotesTest extends TestAdmin
{
    public function test_admin_notes_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('notes.index'));

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

        $response->assertFound();

        (new Note)->newQuery()->delete();
    }

    public function test_admin_notes_update()
    {
        $cmsUser = $this->getFullAccessCmsUser();

        $model = (new Note)->create([
            'cms_user_id' => $cmsUser->id,
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'content' => fake()->text()
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->put(cms_route('notes.save'), [
            'id'  => $model->id,
            'title' => fake()->word()
        ]);

        $response->assertFound();

        $model->delete();
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

        $model = (new Note)->create([
            'cms_user_id' => $cmsUser->id,
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'content' => fake()->text()
        ]);

        $response = $this->actingAs($cmsUser, 'cms')->delete(cms_route('notes.destroy'), [
            'id' => $model->id
        ]);

        $response->assertFound();
    }
}
