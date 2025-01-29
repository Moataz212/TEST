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
            'category_id' => Category::factory(), // Automatically create a related category
            'name' => $this->faker->unique()->word(), // Single English-like word for the product name
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'description' => $this->faker->paragraph(), // Optional description
            'main_image' => 'product.jpg', // Default value
            'images' => json_encode([
                'product.jpg',
                'product.jpg',
                'product.jpg',
                'product.jpg',
            ]),
        ];
    }
}
