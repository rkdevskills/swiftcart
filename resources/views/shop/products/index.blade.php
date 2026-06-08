@extends('layouts.shop')

@section('title', 'All Products')

@section('content')
    <div class="flex gap-8">

        {{-- Sidebar: Categories --}}
        <aside class="hidden md:block w-56 shrink-0">
            <h2 class="font-semibold text-gray-700 mb-3">Categories</h2>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('products.index') }}"
                        class="block px-3 py-2 rounded-lg text-sm {{ !request('category') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        All Products
                    </a>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <p class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wide mt-3">
                            {{ $category->name }}
                        </p>
                        @foreach ($category->children as $child)
                            <a href="{{ route('products.index', ['category' => $child->slug]) }}"
                                class="block px-3 py-2 rounded-lg text-sm {{ request('category') === $child->slug ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ request('search') ? 'Results for "' . request('search') . '"' : 'All Products' }}
                </h1>
                <p class="text-sm text-gray-400">{{ $products->total() }} products found</p>
            </div>

            {{-- Products Grid --}}
            @if ($products->isEmpty())
                <div class="text-center py-20 text-gray-400">
                    <p class="text-5xl mb-4">😕</p>
                    <p class="text-lg font-medium">No products found</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">
                        Clear filters
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
