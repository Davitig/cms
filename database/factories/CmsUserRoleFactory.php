<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CmsUser>
 */
class CmsUserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->word(),
            'full_access' => rand(0, 1),
        ];
    }

    /**
     * Indicates the full access.
     */
    public function fullAccess(bool $fullAccess = true): static
    {
        return $this->state(fn (array $attributes) => [
            'full_access' => (int) $fullAccess
        ]);
    }
}
