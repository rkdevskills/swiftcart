<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'       => Order::factory(),
            'provider'       => 'stripe',
            'transaction_id' => fake()->uuid(),
            'status'         => 'paid',
            'amount'         => fake()->randomFloat(2, 10, 500),
        ];
    }
}
