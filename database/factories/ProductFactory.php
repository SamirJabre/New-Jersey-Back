<?php

namespace Database\Factories;

use App\Models\Category;
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
        return [
            'category_id' => Category::factory(),
            'name' =>fake()->name(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 1, 100),
            'image' => fake()->imageUrl(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'color' => fake()->randomElement(['red', 'green', 'blue', 'yellow']),
            'stock' => fake()->numberBetween(1, 100),
        ];
    }
}
