<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event\Event>
 */
class ProductFactory extends Factory
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
            'price' => fake()->randomFloat(2, 1, 1000),
            'quantity' => fake()->numberBetween(1, 100),
            'visible' => 1,
            'image' => fake()->imageUrl()
        ];
    }
}
