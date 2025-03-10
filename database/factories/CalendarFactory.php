<?php

namespace Database\Factories;

use App\Models\Calendar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calendar>
 */
class CalendarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'color' => Calendar::getRandomColor(),
            'start' => fake()->dateTimeBetween('now')->format(DATE_ATOM),
            'end' => fake()->dateTimeBetween('now', '+2 week')->format(DATE_ATOM)
        ];
    }
}
