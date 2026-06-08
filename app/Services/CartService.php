<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    private function user(): User
    {
        return User::findOrFail(Auth::id());
    }

    public function getCartItems(): Collection
    {
        return $this->user()
            ->cartItems()
            ->with(['product.primaryImage'])
            ->get();
    }

    public function addItem(Product $product, int $quantity = 1): CartItem
    {
        $existing = CartItem::where('user_id', $this->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $newQty = min($existing->quantity + $quantity, $product->stock);
            $existing->update(['quantity' => $newQty]);
            return $existing;
        }

        return CartItem::create([
            'user_id'    => $this->user()->id,
            'product_id' => $product->id,
            'quantity'   => min($quantity, $product->stock),
        ]);
    }

    public function updateItem(CartItem $cartItem, int $quantity): void
    {
        $maxStock = $cartItem->product->stock;
        $cartItem->update(['quantity' => min($quantity, $maxStock)]);
    }

    public function removeItem(CartItem $cartItem): void
    {
        $cartItem->delete();
    }

    public function getTotal(): float
    {
        return $this->getCartItems()->sum(fn($item) => $item->subtotal());
    }

    public function getCount(): int
    {
        return $this->user()->cartItems()->sum('quantity');
    }

    public function clear(): void
    {
        $this->user()->cartItems()->delete();
    }
}