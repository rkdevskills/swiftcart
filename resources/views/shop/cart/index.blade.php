@extends('layouts.shop')

@section('title', 'Your Cart')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">🛒 Your Cart</h1>

@if($cartItems->isEmpty())
    <div class="text-center py-24 text-gray-400">
        <p class="text-6xl mb-4">🛒</p>
        <p class="text-lg font-medium mb-4">Your cart is empty</p>
        <a href="{{ route('products.index') }}"
           class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
            Start Shopping
        </a>
    </div>
@else
    <div class="grid md:grid-cols-3 gap-8">

        {{-- Cart Items --}}
        <div class="md:col-span-2 space-y-4">
            @foreach($cartItems as $item)
                <div class="bg-white rounded-xl shadow-sm p-4 flex gap-4 items-center">

                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                        @if($item->product->primaryImage)
                            <img src="{{ $item->product->primaryImage->path }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-2xl">🖼️</div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('products.show', $item->product->slug) }}"
                           class="font-semibold text-gray-800 hover:text-indigo-600 text-sm line-clamp-1">
                            {{ $item->product->name }}
                        </a>
                        <p class="text-indigo-600 font-bold mt-1">
                            £ {{ number_format($item->product->price, 2) }}
                        </p>
                    </div>

                    {{-- Quantity --}}
                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number"
                               name="quantity"
                               value="{{ $item->quantity }}"
                               min="1"
                               max="{{ $item->product->stock }}"
                               onchange="this.form.submit()"
                               class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:ring-2 focus:ring-indigo-400"/>
                    </form>

                    {{-- Subtotal --}}
                    <div class="text-right shrink-0">
                        <p class="font-bold text-gray-800">
                            £ {{ number_format($item->subtotal(), 2) }}
                        </p>
                    </div>

                    {{-- Remove --}}
                    <div x-data="{ open: false }">

                        {{-- Trigger button --}}
                        <button type="button"
                                @click="open = true"
                                class="text-red-400 hover:text-red-600 text-lg transition">
                            ✕
                        </button>

                        {{-- Form --}}
                        <form method="POST"
                            action="{{ route('cart.destroy', $item) }}"
                            x-ref="deleteForm">
                            @csrf
                            @method('DELETE')
                        </form>

                        {{-- Modal --}}
                        <div x-show="open"
                            x-transition
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl p-6 shadow-xl max-w-sm w-full mx-4">
                                <h3 class="font-bold text-gray-800 mb-2">Remove Item</h3>
                                <p class="text-sm text-gray-500 mb-6">Are you sure you want to remove this item?</p>
                                <div class="flex gap-3">
                                    <button type="button"
                                            @click="open = false"
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                                        Cancel
                                    </button>
                                    <button type="button"
                                            @click="$refs.deleteForm.submit()"
                                            class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Order Summary --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h2>

                <div class="space-y-3 text-sm text-gray-600 mb-6">
                    <div class="flex justify-between">
                        <span>Subtotal ({{ $cartItems->count() }} items)</span>
                        <span>£ {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between font-bold text-gray-800 text-base">
                        <span>Total</span>
                        <span class="text-indigo-600">£ {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}"
                   class="block text-center w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                    Proceed to Checkout →
                </a>

                <a href="{{ route('products.index') }}"
                   class="block text-center mt-3 text-sm text-gray-400 hover:text-indigo-600">
                    ← Continue Shopping
                </a>
            </div>
        </div>
    </div>
@endif
@endsection