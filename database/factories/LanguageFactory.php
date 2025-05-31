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
     * Indicates the language codes.
     */
    public function language(
        ?string $language = null, ?string $shortName = null, ?string $fullName = null
    ): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => $language ?: fake()->unique()->languageCode(),
            'short_name' => $shortName ?: $language,
            'full_name' => $fullName ?: $language
        ]);
    }

    /**
     * Indicates the language code sequences.
     */
    public function languages(array $languages, ?string $main = null): static
    {
        $sequence = [];

        foreach ($languages as $code => $name) {
            $code = is_string($code) ? $code : $name;

            $sequence[] = [
                'language' => $code, 'short_name' => $code, 'full_name' => $name
            ] + ($code == $main ? ['main' => 1] : []);
        }

        return $this->times(count($languages))->sequence(...$sequence);
    }

    /**
     * Indicates the main.
     */
    public function main(int $value = 1, ?string $language = null): static
    {
        return $this->state(function (array $attributes) use ($value, $language) {
            if (! is_null($language) && $language != $attributes['language']) {
                $value = $value == 1 ? 0 : 1;
            }

            return [
                'main' => $value
            ];
        });
    }

    /**
     * Indicates the visible.
     */
    public function visible(string|array|null $languages = null): static
    {
        if (is_null($languages)) {
            return $this->state(fn (array $attributes) => [
                'visible' => 1
            ]);
        }

        $languages = is_array($languages) ? $languages : func_get_args();

        return $this->state(fn (array $attributes) => [
            'visible' => in_array($attributes['language'], $languages) ? 1 : 0
        ]);
    }

    /**
     * Indicates the non-visible.
     */
    public function notVisible(string|array|null $languages = null): static
    {
        if (is_null($languages)) {
            return $this->state(fn (array $attributes) => [
                'visible' => 0
            ]);
        }

        $languages = is_array($languages) ? $languages : func_get_args();

        return $this->state(fn (array $attributes) => [
            'visible' => in_array($attributes['language'], $languages) ? 0 : 1
        ]);
    }
}
