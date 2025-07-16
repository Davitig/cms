<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TranslationLanguage>
 */
class TranslationLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => fake()->word()
        ];
    }

    /**
     * Indicates the value.
     */
    public function value(string $value): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => $value
        ]);
    }
}
