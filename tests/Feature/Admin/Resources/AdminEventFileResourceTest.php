<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Event\EventFile;
use Database\Factories\CollectionFactory;
use Database\Factories\Event\EventFactory;
use Database\Factories\Event\EventFileFactory;
use Database\Factories\Event\EventFileLanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Admin\TestAdmin;
use Tests\Feature\CreatesLanguageProvider;

class AdminEventFileResourceTest extends TestAdmin
{
    use RefreshDatabase, CreatesLanguageProvider;

    /**
     * Create a new event files.
     *
     * @param  int|null  $times
     * @param  bool  $createFiles
     * @return array
     */
    protected function createEventFiles(?int $times = null, bool $createFiles = true): array
    {
        $collection = CollectionFactory::new()->eventType()->create();

        $event = EventFactory::new()->create(['collection_id' => $collection->id]);

        if ($createFiles) {
            $files = EventFileFactory::new()->count($times)->has(
                EventFileLanguageFactory::times(language()->count())
                    ->sequence(...apply_languages([])),
                'languages'
            )->create(['event_id' => $event->id]);
        } else {
            $files = null;
        }

        return array_merge([$event], ($files ? [$files] : []));
    }

    public function test_admin_event_files_resource_index()
    {
        [$event] = $this->createEventFiles(5);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('events.files.index', [$event->id]));

        $response->assertOk();
    }

    public function test_admin_event_files_resource_create()
    {
        [$event] = $this->createEventFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('events.files.create', [$event->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_event_files_resource_store()
    {
        [$event] = $this->createEventFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.files.store', [$event->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_event_files_resource_edit()
    {
        [$event, $file] = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->getJson(cms_route('events.files.edit', [$event->id, $file->id]));

        $response->assertOk()->assertJsonStructure(['result', 'view']);
    }

    /**
     * @throws \JsonException
     */
    public function test_admin_event_files_resource_update()
    {
        [$event, $file] = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.files.update', [$event->id, $file->id]), [
            'title' => fake()->sentence(2),
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasNoErrors();
    }

    public function test_admin_event_files_resource_validate_required()
    {
        [$event] = $this->createEventFiles(null, false);

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('events.files.store', [$event->id]), [
            'file' => fake()->imageUrl()
        ]);

        $response->assertFound()->assertSessionHasErrors(['title']);
    }

    public function test_admin_event_files_resource_visibility()
    {
        [$event, $file] = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.files.visibility', [$file->id]));

        $response->assertFound();
    }

    public function test_admin_event_files_resource_update_position()
    {
        [$event, $files] = $this->createEventFiles(5);

        $data = $ids = [];
        $startItem = $files->first();
        $endItem = $files->last();

        $data[] = ['id' => $ids[] = $startItem->id, 'pos' => $endItem->position];
        foreach ($files as $file) {
            if ($file->id == $startItem->id || $file->id == $endItem->id) {
                continue;
            }

            $data[] = ['id' => $ids[] = $file->id, 'pos' => $file->position - 1];
        }
        $data[] = ['id' => $ids[] = $endItem->id, 'pos' => $endItem->position - 1];

        $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->put(cms_route('events.files.positions'), [
            'start_id' => $startItem->id,
            'end_id' => $endItem->id,
            'foreign_key' => 'event_id'
        ]);

        $updatedData = (new EventFile)->whereKey($ids)
            ->get(['id', 'position as pos'])
            ->toArray();

        $this->assertSame($data, $updatedData);
    }

    public function test_admin_event_files_resource_destroy()
    {
        [$event, $file] = $this->createEventFiles();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->delete(cms_route('events.files.destroy', [
            $event->id, $file->id
        ]));

        $response->assertFound();
    }
}
