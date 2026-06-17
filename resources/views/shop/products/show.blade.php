@extends('layouts.shop')

@section('title', $product->name)

@section('content')

{{-- Breadcrumb --}}
<nav class="text-sm text-gray-400 mb-6">
    <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
    <span class="mx-2">/</span>
    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
       class="hover:text-indigo-600">{{ $product->category->name }}</a>
    <span class="mx-2">/</span>
    <span class="text-gray-600">{{ $product->name }}</span>
</nav>

{{-- Product Detail --}}
<div class="bg-white rounded-2xl shadow-sm p-6 md:p-10">
    <div class="grid md:grid-cols-2 gap-10">

        {{-- Images --}}
        <div class="space-y-3">
            <div class="aspect-square rounded-xl overflow-hidden bg-gray-100">
                @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->url() }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover" id="main-image"/>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300 text-7xl">🖼️</div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
                <div class="flex gap-2">
                    @foreach($product->images as $image)
                        <img src="{{ $image->path }}"
                             alt="thumbnail"
                             onclick="document.getElementById('main-image').src = '{{ $image->path }}'"
                             class="w-16 h-16 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-indigo-400"/>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div>
            <p class="text-sm text-indigo-500 font-medium mb-2">{{ $product->category->name }}</p>
            <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>

            {{-- Rating --}}
            @if($product->reviews->count() > 0)
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-yellow-400 text-lg">⭐</span>
                    <span class="font-semibold">{{ $product->averageRating() }}</span>
                    <span class="text-sm text-gray-400">({{ $product->reviews->count() }} reviews)</span>
                </div>
            @endif

            <p class="text-3xl font-bold text-indigo-600 mb-4">
                £ {{ number_format($product->price, 2) }}
            </p>

            <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $product->description }}</p>

            {{-- Stock --}}
            <p class="text-sm mb-6 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                {{ $product->stock > 0 ? '✅ In Stock (' . $product->stock . ' left)' : '❌ Out of Stock' }}
            </p>

            {{-- Add to Cart --}}
            @if($product->isInStock())
                @auth
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}"/>
                        <div class="flex items-center gap-3 mb-4">
                            <label class="text-sm font-medium text-gray-700">Qty:</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"/>
                        </div>
                        <button type="submit"
                                class="w-full text-white bg-indigo-600 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            🛒 Add to Cart
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block text-center w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                        Login to Add to Cart
                    </a>
                @endauth
            @endif
        </div>
    </div>
</div>

{{-- Reviews --}}
@if($product->reviews->count() > 0)
    <div class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Customer Reviews</h2>
        <div class="space-y-4">
            @foreach($product->reviews as $review)
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-2">
                        
                        {{-- Reviewer name + rating --}}
                        <div>
                            <span class="font-semibold text-gray-700">{{ $review->user->name }}</span>
                            <span class="text-yellow-400 ml-2">{{ str_repeat('⭐', $review->rating) }}</span>
                        </div>

                        {{-- Delete button — only for review owner or admin --}}
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->id === $review->user_id)
                                <form method="POST" action="{{ route('reviews.destroy', $review) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-400 hover:text-red-600 text-xs"
                                            onclick="return confirm('Delete this review?')">
                                        🗑️ Delete
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <p class="text-sm text-gray-600">{{ $review->body }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(auth()->check())
    <div class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Write a Review</h2>
        <form method="POST" action="{{ route('reviews.store', $product) }}" class="bg-white rounded-xl shadow-sm p-5">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}"/>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating:</label>
                <select name="rating" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Select rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Review:</label>
                <textarea name="body" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
            </div>
            <button type="submit"
                    class="bg-indigo-600 text-white py-3 px-6 rounded-xl font-semibold hover:bg-indigo-700 transition">
                Submit Review
            </button>
        </form>
    </div>
@endif

{{-- Related Products --}}
@if($related->count() > 0)
    <div class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($related as $item)
                <x-product-card :product="$item" />
            @endforeach
        </div>
    </div>
@endif

@endsection