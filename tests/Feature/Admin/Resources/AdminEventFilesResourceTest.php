<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Event\EventFile;
use Database\Factories\Event\EventFactory;
use Database\Factories\Event\EventFileFactory;
use Database\Factories\Event\EventFileLanguageFactory;
use Database\Factories\CollectionFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\Admin\TestAdmin;

class AdminEventFilesResourceTest extends TestAdmin
{
    /**
     * Create a new event files.
     *
     * @param  int|null  $times
     * @param  bool  $createFiles
     * @return array
     */
    public function createEventFiles(?int $times = null, bool $createFiles = true): array
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $event = EventFactory::new()->create(['collection_id' => $collection->id]);

        if ($createFiles) {
            $files = EventFileFactory::new()->count($times)->has(
                EventFileLanguageFactory::times(count(languages()))
                    ->state(new Sequence(...apply_languages([]))),
                'languages'
            )->create(['event_id' => $event->id]);
        } else {
            $files = null;
        }

        return array_merge([$collection, $event], ($files ? [$files] : []));
    }

    public function test_admin_event_files_resource_index()
    {
        list($collection, $event) = $this->createEventFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('events.files.index', [$event->id]));

        $event->delete();
        $collection->delete();

        $response->assertOk();
    }

    public function test_admin_event_files_resource_create()
    {
        list($collection, $event) = $this->createEventFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('events.files.create', [$event->id]));

        $event->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_event_files_resource_store()
    {
        list($collection, $event) = $this->createEventFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.files.store', [$event->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $event->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_event_files_resource_edit()
    {
        list($collection, $event, $file) = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('events.files.edit', [$event->id, $file->id]));

        $event->delete();
        $collection->delete();

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_event_files_resource_update()
    {
        list($collection, $event, $file) = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.files.update', [
            $event->id, $file->id
        ]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $event->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_event_files_resource_validate_required()
    {
        list($collection, $event) = $this->createEventFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.files.store', [
            $event->id
        ]), [
            'file' => fake()->imageUrl()
        ]);

        $event->delete();
        $collection->delete();

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_event_files_resource_visibility()
    {
        list($collection, $event, $file) = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.files.visibility', [$file->id]));

        $event->delete();
        $collection->delete();

        $response->assertFound();
    }

    public function test_admin_event_files_resource_update_position()
    {
        list($collection, $event, $files) = $this->createEventFiles(3);

        $newData = $ids = [];

        foreach ($files as $file) {
            $newData[] = ['id' => $ids[] = $file->id, 'pos' => $file->position + 1];
        }

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.files.updatePosition'), ['data' => $newData]);

        $updatedData = (new EventFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $event->delete();
        $collection->delete();

        $this->assertSame($newData, $updatedData);
    }

    public function test_admin_event_files_resource_destroy()
    {
        list($collection, $event, $file) = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('events.files.destroy', [
            $event->id, $file->id
        ]));

        $event->delete();
        $collection->delete();

        $response->assertFound();
    }
}
