<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'address_id' => Address::factory(),
            'status'     => 'delivered',
            'subtotal'   => fake()->randomFloat(2, 10, 500),
            'shipping'   => 0,
            'total'      => fake()->randomFloat(2, 10, 500),
        ];
    }
}
