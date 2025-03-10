<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'main' => rand(0, 1),
            'title' => fake()->sentence(2),
            'description' => fake()->sentence()
        ];
    }

    /**
     * Indicates the main boolean.
     */
    public function main(int $value): static
    {
        return $this->state(fn (array $attributes) => [
            'main' => $value
        ]);
    }
}
