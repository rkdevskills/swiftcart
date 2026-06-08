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
        // Validate input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'nullable|string|max:1000',
        ]);

        //check if user has purchased the product
        $hasPurchased = $product->orderItems()
            ->whereHas('order', fn($q) => $q->where('user_id', Auth::id())->where('status', '!=', 'cancelled'))
            ->exists();

        abort_if(! $hasPurchased, 403);
        
        //check if user has already reviewed the product
        $hasReviewed = Review::where('user_id', Auth::id())->where('product_id', $product->id)->exists();

        abort_if($hasReviewed, 403);

        // Create review (pending approval)
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'body' => $validated['body'],
            'is_approved' => false, // Admin approval required
        ]);

        return redirect()->route('products.show', $product)->with('success', 'Review submitted and awaiting approval.');
    }

    public function destroy(Review $review)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Only allow users to delete their own reviews or admins to delete any review
        abort_if($review->user_id !== $user->id && ! $user->isAdmin(), 403);

        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
