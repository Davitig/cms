<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use Tests\TestCase;

class TestAdminResources extends TestCase
{
    /**
     * Create a new collection model.
     *
     * @param  string  $type
     * @return \App\Models\Collection
     */
    public function createCollectionModel(string $type): Collection
    {
        return (new Collection)->create([
            'title' => fake()->sentence(2),
            'type' => $type,
            'admin_order_by' => 'position',
            'admin_sort' => 'asc',
            'admin_per_page' => 20,
            'web_order_by' => 'created_at',
            'web_sort' => 'asc',
            'web_per_page' => 20
        ]);
    }

    /**
     * Get the collection model.
     *
     * @param  string|null  $id
     * @return \App\Models\Collection
     */
    public function getCollectionModel(?string $id = null): Collection
    {
        return (new Collection)->when($id, function ($object) use ($id) {
            return $object->findOrFail($id);
        }, function ($object) {
            return $object->firstOrFail();
        });
    }
}
