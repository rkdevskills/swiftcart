@extends('layouts.shop')

@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-2xl mx-auto text-center py-12">
    <div class="text-6xl mb-4">🎉</div>
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Order Confirmed!</h1>
    <p class="text-gray-500 mb-8">Thank you for your purchase. Your order is being processed.</p>

    <div class="bg-white rounded-2xl shadow-sm p-6 text-left mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-800">Order #{{ $order->id }}</h2>
            <span class="bg-indigo-100 text-indigo-600 text-xs font-semibold px-3 py-1 rounded-full">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="space-y-3 mb-4">
            @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->product->name }} x{{ $item->quantity }}</span>
                    <span class="font-medium">RM {{ number_format($item->subtotal(), 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t pt-4 flex justify-between font-bold text-gray-800">
            <span>Total Paid</span>
            <span class="text-indigo-600">RM {{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <a href="{{ route('home') }}"
       class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
        Continue Shopping
    </a>
</div>
@endsection