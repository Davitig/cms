<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Event\Event;
use Database\Factories\CollectionFactory;
use Database\Factories\Event\EventFactory;
use Database\Factories\Event\EventLanguageFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\Admin\TestAdmin;

class AdminEventsResourceTest extends TestAdmin
{
    /**
     * Create a new events.
     *
     * @param  int|null  $times
     * @return array
     */
    public function createEvents(?int $times = null): array
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $events = EventFactory::new()->count($times)->has(
            EventLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->create(['collection_id' => $collection->id]);

        return [$collection, $events];
    }

    public function test_admin_events_resource_index()
    {
        [$collection, $events] = $this->createEvents(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('events.index', [$collection->id]));

        $events->map->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_events_resource_create()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('events.create', [$collection->id]));

        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_events_resource_store()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.store', [$collection->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        (new Event)->collectionId($collection->id)->firstOrFail()->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_events_resource_edit()
    {
        [$collection, $event] = $this->createEvents();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('events.edit', [$collection->id, $event->id]));

        $event->delete();
        $collection->delete();

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_events_resource_update()
    {
        [$collection, $event] = $this->createEvents();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.update', [$collection->id, $event->id]), [
            'title' => fake()->sentence(2),
            'slug' => fake()->slug(2)
        ]);

        $event->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_events_resource_validate_title_required()
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.store', [$collection->id]), [
            'slug' => fake()->slug(2)
        ]);

        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_events_resource_validate_slug_unique()
    {
        [$collection, $event] = $this->createEvents();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.store', [$collection->id]), [
            'slug' => $event->slug
        ]);

        $event->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['slug']);
    }

    public function test_admin_events_resource_visibility()
    {
        [$collection, $event] = $this->createEvents();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.visibility', [$event->id]));

        $event->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_events_resource_update_position()
    {
        [$collection, $events] = $this->createEvents(3);

        $newData = $ids = [];

        foreach ($events as $event) {
            $newData[] = ['id' => $ids[] = $event->id, 'pos' => $event->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.updatePosition'), ['data' => $newData]);

        $updatedData = (new Event)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $events->map->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_events_resource_transfer()
    {
        [$collection, $event] = $this->createEvents();

        $newCollection = CollectionFactory::new()->eventType()->create();

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.transfer', [$collection->id]), [
            'id' => $event->id,
            'column' => 'collection_id',
            'column_value' => $newCollection->id
        ]);

        $updatedEventCollectionId = (new Event)->whereKey($event->id)
            ->value('collection_id');

        $event->delete();
        $collection->delete();
        $newCollection->delete();

        $this->assertSame($newCollection->id, $updatedEventCollectionId);
    }

    public function test_admin_events_resource_destroy()
    {
        [$collection, $event] = $this->createEvents();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('events.destroy', [$collection->id, $event->id]));

        $event->delete();
        $collection->delete();

        $response->assertFound();
    }
}
