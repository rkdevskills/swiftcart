<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->parentOnly()->with('children')->get();

        $products = Product::active()
            ->inStock()
            ->with(['primaryImage', 'category', 'reviews'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $categorySlug) {
                $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('shop.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $product->load([
            'images',
            'category', 
            'reviews' => fn($q) => $q->with('user')->latest()
            ]);

        $related = Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->take(4)
            ->latest()
            ->get();

        return view('shop.products.show', compact('product', 'related'));
    }
}
