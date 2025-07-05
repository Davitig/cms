<?php

namespace Database\Factories;

use App\Models\CmsUser\CmsUserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CmsUser\CmsUserRole>
 */
class CmsUserRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = CmsUserRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->word()
        ];
    }

    /**
     * Indicates the role.
     */
    public function role(string $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role
        ]);
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

    /**
     * Indicates the custom access.
     */
    public function customAccess(): static
    {
        return $this->fullAccess(false);
    }
}
