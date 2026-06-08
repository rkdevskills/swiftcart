<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'line1'      => fake()->streetAddress(),
            'line2'      => fake()->optional()->secondaryAddress(),
            'city'       => fake()->city(),
            'postcode'   => fake()->postcode(),
            'country'    => 'MY',
            'is_default' => false,
        ];
    }
}
