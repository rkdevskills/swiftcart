<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Services\CartService;
use App\Models\Product;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $cartItems = $this->cartService->getCartItems();
        $total     = $this->cartService->getTotal();

        return view('shop.cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if (! $product->isInStock()) {
            return back()->with('error', 'Sorry, this product is out of stock.');
        }

        $this->cartService->addItem($product, $request->quantity);

        return back()->with('success', '🛒 ' . $product->name . ' added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cartService->updateItem($cartItem, $request->quantity);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);

        $this->cartService->removeItem($cartItem);

        return back()->with('success', 'Item removed from cart.');
    }
}
