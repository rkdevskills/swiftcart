<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body'   => 'nullable|string|max:1000',
        ]);

        $hasPurchased = $product->orderItems()
            ->whereHas('order', fn($q) => $q->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled'))
            ->exists();

        if (! $hasPurchased) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You must purchase this product before leaving a review.');
        }

        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'rating'     => $validated['rating'],
            'body'       => $validated['body'] ?? null,
            'is_approved'=> false,
        ]);

        return redirect()->route('products.show', $product)->with('success', 'Review submitted and awaiting approval.');
    }

    public function destroy(Review $review)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($review->user_id !== $user->id && ! $user->isAdmin()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this review.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
