<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product', 'user')
        ->where('is_approved', false)
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()->route('admin.reviews.index')->with('success', 'Review approved successfully.');
    }

    public function destroy(Review $review)
    {   
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
