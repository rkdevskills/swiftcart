<?php

namespace App\Http\Controllers\Shop;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{
    public function __construct(
        protected CartService      $cartService,
        protected CheckoutService  $checkoutService,
    ) {}

    public function index()
    {
        $cartItems = $this->cartService->getCartItems();

        // Redirect back if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total     = $this->cartService->getTotal();
        $addresses = Auth::user()->addresses;

        // Create Stripe payment intent
        $intent = $this->checkoutService->createStripeIntent($total);

        return view('shop.checkout.index', compact('cartItems', 'total', 'addresses', 'intent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id'       => ['required', 'exists:addresses,id'],
            'payment_intent'   => ['required', 'string'],
        ]);

        $cartItems = $this->cartService->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Create the order
        $order = $this->checkoutService->createOrder($request->address_id);

        // Update payment with stripe intent id
        $order->payment->update([
            'transaction_id' => $request->payment_intent,
            'status'         => 'paid',
        ]);

        $order->update(['status' => 'processing']);

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }

    public function success(int $order)
    {
        $user = User::findOrFail(Auth::id());

        $order = $user->orders()
            ->with(['items.product', 'payment'])
            ->findOrFail($order);

        return view('shop.checkout.success', compact('order'));
    }
}
