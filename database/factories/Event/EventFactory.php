<?php

namespace Database\Factories\Event;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event\Event>
 */
class EventFactory extends Factory
{
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
        return [
            'slug' => fake()->unique()->slug(2),
            'position' => $this->position++,
            'visible' => 1,
            'image' => fake()->imageUrl()
        ];
    }

    /**
     * Indicates the collection ID.
     */
    public function collectionId(int $value): static
    {
        return $this->state(fn (array $attributes) => [
            'collection_id' => $value,
        ]);
    }
}
