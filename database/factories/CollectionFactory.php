<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_keys((array) cms_config('collections.types'));

        $orderList = array_keys((array) cms_config('collections.order_by'));

        $sortList = array_keys((array) cms_config('collections.sort'));

        return [
            'title' => fake()->sentence(2),
            'type' => $types[array_rand($types)],
            'admin_order_by' => $orderList[array_rand($orderList)],
            'admin_sort' => $sortList[array_rand($orderList)],
            'admin_per_page' => rand(10, 50),
            'web_order_by' => $orderList[array_rand($orderList)],
            'web_sort' => $sortList[array_rand($sortList)],
            'web_per_page' => rand(10, 50),
            'description' => fake()->sentence()
        ];
    }

    /**
     * Indicate that the collection is a random type.
     */
    public function randomType(): Factory
    {
        $types = (array) cms_config('collections.types');

        return $this->state(function (array $attributes) use ($types) {
            return [
                'type' => $types[array_rand($types)],
            ];
        });
    }

    /**
     * Indicate that the collection is articles' type.
     */
    public function articleType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'articles',
            ];
        });
    }

    /**
     * Indicate that the collection is events type.
     */
    public function eventType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'events',
            ];
        });
    }

    /**
     * Indicate that the collection is galleries type.
     */
    public function galleryType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'galleries',
            ];
        });
    }

    /**
     * Indicate that the collection is faq type.
     */
    public function faqType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'faq',
            ];
        });
    }
}
