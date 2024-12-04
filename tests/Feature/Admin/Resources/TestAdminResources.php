<?php

namespace Tests\Feature\Admin\Resources;

use App\Models\Collection;
use App\Models\Gallery\Gallery;
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
     * @param  string|null  $type
     * @param  int|null  $id
     * @return \App\Models\Collection
     */
    public function getCollectionModel(?string $type = null, ?int $id = null): Collection
    {
        return (new Collection)->when($type, function ($q, $value) {
            return $q->whereType($value);
        })->when($id, function ($object) use ($id) {
            return $object->findOrFail($id);
        }, function ($object) {
            return $object->firstOrFail();
        });
    }

    /**
     * Create a new gallery model.
     *
     * @param  string  $type
     * @return \App\Models\Gallery\Gallery
     */
    public function createGalleryModel(string $type): Gallery
    {
        return (new Gallery)->create([
            'collection_id' => $this->createCollectionModel('galleries')->id,
            'slug' => fake()->slug(2),
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
     * Get the gallery model.
     *
     * @param  string|null  $type
     * @param  int|null  $id
     * @return \App\Models\Gallery\Gallery
     */
    public function getGalleryModel(?string $type = null, ?int $id = null): Gallery
    {
        return (new Gallery)->when($type, function ($q, $value) {
            return $q->whereType($value);
        })->when($id, function ($object) use ($id) {
            return $object->findOrFail($id);
        }, function ($object) {
            return $object->firstOrFail();
        });
    }
}
