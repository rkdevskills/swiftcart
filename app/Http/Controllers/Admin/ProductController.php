<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',   
        ]);

        $validated['slug'] = Str::slug($request->name);
        $product = Product::create($validated);

        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('products', 'public');

        //     ProductImage::create([
        //         'product_id' => $product->id,
        //         'path' => $path,
        //         'is_primary' => true,
        //     ]);
        // }

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => 'uploads/products/' . $filename,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('category', 'images')->findOrFail($id);
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::with('category', 'images')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',   
        ]);

        $validated['slug'] = Str::slug($request->name);
        $product->update($validated);

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);

            // Update existing or create new
            if ($product->primaryImage) {
                $product->primaryImage->update([
                    'path' => 'uploads/products/' . $filename,
                ]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => 'uploads/products/' . $filename,
                    'is_primary' => true,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete image files from folder
        foreach ($product->images as $image) {
            if (! str_starts_with($image->path, 'http')) {
                $fullPath = public_path($image->path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
