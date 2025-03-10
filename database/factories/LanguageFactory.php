<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
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
            'language' => $langCode = fake()->unique()->languageCode(),
            'visible' => 1,
            'position' => $this->position++,
            'main' => 0,
            'short_name' => $langCode,
            'full_name' => $langCode
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

    /**
     * Indicates the language code.
     */
    public function languageCode(?string $code = null): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => $code ??= fake()->unique()->languageCode(),
            'short_name' => $code,
            'full_name' => $code
        ]);
    }
}
