<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path'       => 'https://picsum.photos/seed/' . fake()->uuid() . '/640/480',
            'is_primary' => false,
            'sort_order' => fake()->numberBetween(1, 5),
        ];
    }
}
