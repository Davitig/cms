<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use App\Models\Event\Event;
use App\Models\Event\EventFile;

class AdminEventFilesResourceTest extends TestAdminResources
{
    public function test_admin_event_files_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('events.files.index', [
            $this->createEventModel()->id
        ]));

        $response->assertOk();
    }

    public function test_admin_event_files_resource_create()
    {
        $response = $this->actingAs($this->getUser())->getJson(cms_route('events.files.create', [
            $this->getEventModel()->id
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_event_files_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('events.files.store', [
            $this->getEventModel()->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_event_files_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->getJson(cms_route('events.files.edit', [
            $this->getEventModel()->id, (new EventFile)->valueOrFail('id')
        ]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_event_files_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.files.update', [
            $this->getEventModel()->id, (new EventFile)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_event_files_resource_validate_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('events.files.store', [
            $this->getEventModel()->id
        ]), [
            // empty data
        ]);

        $response->assertFound()->assertSessionHasErrors(['title', 'file']);
    }

    public function test_admin_event_files_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.files.visibility', [
            (new EventFile)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_event_files_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.files.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_event_files_resource_destroy()
    {
        $response = $this->actingAs($this->getUser())->delete(cms_route('events.files.destroy', [
            $this->getEventModel()->id, (new EventFile)->valueOrFail('id')
        ]));

        (new Event)->newQuery()->delete();

        (new Collection)->newQuery()->delete();

        $response->assertFound();
    }
}
