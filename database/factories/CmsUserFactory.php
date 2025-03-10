<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CmsUser>
 */
class CmsUserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'blocked' => 0,
            'password' => static::$password ??= Hash::make('password')
        ];
    }

    /**
     * Indicates the role ID.
     */
    public function role(int $roleId): static
    {
        return $this->state(fn (array $attributes) => [
            'cms_user_role_id' => $roleId,
        ]);
    }

    /**
     * Indicates the login parameters.
     */
    public function loginParams(string $email, string $password): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => $email,
            'password' => bcrypt($password)
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
