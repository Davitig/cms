<?php

namespace Database\Factories;

use App\Models\Gallery\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Gallery::class;

    /**
     * The position attribute increment value.
     *
     * @var int
     */
    protected int $position = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_keys((array) cms_config('listable.galleries.types'));

        $orderList = array_keys((array) cms_config('listable.galleries.order_by'));

        $sortList = array_keys((array) cms_config('listable.galleries.sort'));

        return [
            'slug' => fake()->unique()->slug(2),
            'position' => $this->position++,
            'visible' => 1,
            'type' => $types[array_rand($types)],
            'admin_order_by' => $orderList[array_rand($orderList)],
            'admin_sort' => $sortList[array_rand($orderList)],
            'admin_per_page' => rand(10, 50),
            'web_order_by' => $orderList[array_rand($orderList)],
            'web_sort' => $sortList[array_rand($sortList)],
            'web_per_page' => rand(10, 50)
        ];
    }

    /**
     * Indicates the collection ID.
     */
    public function collectionId(int $collectionId): static
    {
        return $this->state(fn (array $attributes) => [
            'collection_id' => $collectionId,
        ]);
    }

    /**
     * Indicates the type.
     */
    public function type(string $value): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $value
        ]);
    }

    /**
     * Indicate that the gallery is photos' type.
     */
    public function photoType(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'photos'
        ]);
    }

    /**
     * Indicate that the gallery is videos type.
     */
    public function videoType(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'videos'
        ]);
    }
}
