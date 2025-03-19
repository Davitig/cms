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
            'slug' => fake()->unique()->slug(2),
            'position' => $this->position++,
            'visible' => 1,
            'type' => 'page',
            'image' => fake()->imageUrl()
        ];
    }

    /**
     * Indicates the menu ID.
     */
    public function menuId(int $value): static
    {
        return $this->state(fn (array $attributes) => [
            'menu_id' => $value,
        ]);
    }

    /**
     * Indicates the visible.
     */
    public function visible(int $value = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'visible' => $value
        ]);
    }

    /**
     * Indicates the parent ID.
     */
    public function parentId(int $value): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $value,
        ]);
    }

    /**
     * Indicates the type.
     */
    public function type(string $value): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $value,
        ]);
    }

    /**
     * Indicates the type ID.
     */
    public function typeId(int $value): static
    {
        return $this->state(fn (array $attributes) => [
            'type_id' => $value,
        ]);
    }
}
