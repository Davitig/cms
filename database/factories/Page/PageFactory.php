<?php

namespace Database\Factories\Page;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page\Page>
 */
class PageFactory extends Factory
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
            'slug' => fake()->unique()->slug(),
            'position' => $this->position++,
            'visible' => rand(0, 1),
            'type' => 'page',
            'image' => fake()->imageUrl(),
            'collapse' => rand(0, 1),
        ];
    }

    /**
     * Indicates the menu ID.
     */
    public function menuId(int $menuId): static
    {
        return $this->state(fn (array $attributes) => [
            'menu_id' => $menuId,
        ]);
    }
}
