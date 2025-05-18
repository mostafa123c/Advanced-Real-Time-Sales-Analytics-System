<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Sports & Outdoors',
            'Summer Accessories',
            'winter wear',
            'cold drinks',
            'hot drinks',
            'ice creams'
        ];

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category' => $this->faker->randomElement($categories),
            'stock_quantity' => $this->faker->numberBetween(5, 100),
        ];
    }
}