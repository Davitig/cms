<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Event\Event;

class AdminEventsResourceTest extends TestAdminResources
{
    public function test_admin_events_resource_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('events.index', [
            $this->createCollectionModel('events')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_events_resource_create()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('events.create', [
            $this->getCollectionModel('events')->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_events_resource_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('events.store', [
            $this->getCollectionModel('events')->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_events_resource_edit()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('events.edit', [
            $this->getCollectionModel('events')->id, (new Event)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_events_resource_update()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.update', [
            $this->getCollectionModel('events')->id, (new Event)->valueOrFail('id')
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_events_resource_destroy()
    {
        (new Event)->create([
            'collection_id' => $collectionId = $this->getCollectionModel('events')->id,
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response = $this->actingAs($this->getUser())->delete(cms_route('events.destroy', [
            $collectionId, (new Event)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_events_resource_validate_title_required()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('events.store', [
            $this->getCollectionModel('events')->id
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_events_resource_validate_slug_unique()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('events.store', [
            $this->getCollectionModel('events')->id
        ]), [
            'slug' => (new Event)->firstOrFail()->slug
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_events_resource_visibility()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.visibility', [
            (new Event)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_events_resource_update_position()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_events_resource_transfer()
    {
        $response = $this->actingAs($this->getUser())->put(cms_route('events.transfer', [
            ($event = (new Event)->firstOrFail())->collection_id
        ]), [
            'id' => $event->id,
            'column' => 'collection_id',
            'column_value' => $this->createCollectionModel('events')->id
        ]);

        $event->delete();

        $response->assertFound();
    }
}
