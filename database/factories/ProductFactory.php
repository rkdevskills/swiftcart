<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends Factory<Product>
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
        $name = fake()->unique()->words(3, true);
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory()->create()->id,
            'name' => ucwords($name), //$name,
            'slug' => str()->slug($name),
            'description' => fake()->paragraph(2, true),
            'price' => fake()->randomFloat(2, 5, 500),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
