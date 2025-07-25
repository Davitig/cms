<?php

namespace Database\Factories;

use App\Models\CmsUser\CmsUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CmsUser\CmsUser>
 */
class CmsUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = CmsUser::class;

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
            'suspended' => 0,
            'password' => static::$password ??= bcrypt('password')
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
     * Indicates the full name.
     */
    public function fullName(string $firstName, string $lastName): static
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
    }

    /**
     * Indicates the suspended.
     */
    public function suspended(bool|int $value): static
    {
        return $this->state(fn (array $attributes) => [
            'suspended' => (bool) $value
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
