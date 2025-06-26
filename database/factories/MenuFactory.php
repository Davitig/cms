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
            'main' => 0,
            'title' => fake()->sentence(2),
            'description' => fake()->sentence()
        ];
    }

    /**
     * Indicates the main.
     */
    public function main(int $value = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'main' => $value
        ]);
    }

    /**
     * Indicates the title.
     */
    public function title(string $title, ?string $desc = null): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $title
        ] + ($desc ? ['description' => $desc] : []));
    }
}
