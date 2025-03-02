<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Event\Event;
use App\Models\Collection;

class AdminEventsResourceTest extends TestAdminResources
{
    public function test_admin_events_resource_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('events.index', [
            $this->getCollectionModel('events')->id
        ]));

        $response->assertOk();
    }

    public function test_admin_events_resource_create()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('events.create', [
            $this->getCollectionModel('events')->id
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_events_resource_store()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('events.store', [
            $this->getCollectionModel('events')->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_events_resource_edit()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->get(cms_route('events.edit', [
            $this->getCollectionModel('events')->id, (new Event)->valueOrFail('id')
        ]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_events_resource_update()
    {
        $event = (new Event)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('events.update', [
            $event->collection_id, $event->id
        ]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_events_resource_validate_title_required()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('events.store', [
            $this->getCollectionModel('events')->id
        ]), [
            'slug' => fake()->slug(2)
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_events_resource_validate_slug_unique()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->post(cms_route('events.store', [
            $this->getCollectionModel('events')->id
        ]), [
            'slug' => (new Event)->valueOrFail('slug')
        ]);

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_events_resource_visibility()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('events.visibility', [
            (new Event)->valueOrFail('id')
        ]));

        $response->assertFound();
    }

    public function test_admin_events_resource_update_position()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('events.updatePosition'));

        $response->assertFound();
    }

    public function test_admin_events_resource_transfer()
    {
        $event = (new Event)->firstOrFail();

        $collectionId = (new Collection)->whereKeyNot($event->collection_id)->value('id');

        if (is_null($collectionId)) {
            $collectionId = $this->createCollectionModel('events')->id;
        }

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->put(cms_route('events.transfer', [
            $event->collection_id
        ]), [
            'id' => $event->id,
            'column' => 'collection_id',
            'column_value' => $collectionId
        ]);

        $response->assertFound();
    }

    public function test_admin_events_resource_destroy()
    {
        $event = (new Event)->firstOrFail();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser()
        )->delete(cms_route('events.destroy', [
            $event->collection_id, $event->id
        ]));

        $response->assertFound();
    }
}
