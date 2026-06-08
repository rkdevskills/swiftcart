<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    private function user(): User
    {
        return User::findOrFail(Auth::id());
    }

    public function __construct(protected CartService $cartService) {}

    public function createOrder(int $addressId): Order
    {
        $cartItems = $this->cartService->getCartItems();
        $subtotal  = $this->cartService->getTotal();
        $shipping  = 0;
        $total     = $subtotal + $shipping;

        return DB::transaction(function () use ($cartItems, $addressId, $subtotal, $shipping, $total) {

            // 1. Create the order
            $order = Order::create([
                'user_id'    => $this->user()->id,
                'address_id' => $addressId,
                'status'     => 'pending',
                'subtotal'   => $subtotal,
                'shipping'   => $shipping,
                'total'      => $total,
            ]);

            // 2. Create order items (snapshot price at time of purchase)
            foreach ($cartItems as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                ]);

                // 3. Deduct stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // 4. Create pending payment record
            Payment::create([
                'order_id' => $order->id,
                'provider' => 'stripe',
                'status'   => 'pending',
                'amount'   => $total,
            ]);

            // 5. Clear the cart
            $this->cartService->clear();

            return $order;
        });
    }

    public function createStripeIntent(float $amount): object
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        return \Stripe\PaymentIntent::create([
            'amount'   => (int) round($amount * 100), // Stripe uses cents
            'currency' => 'myr',
        ]);
    }
}