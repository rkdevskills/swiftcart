@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')

{{-- Header --}}
<div class="flex items-center justify-end mb-6">
    <a href="{{ route('admin.orders.index') }}"
       class="text-sm text-indigo-600 hover:underline">
        ← Back to Orders
    </a>
</div>

<div class="grid md:grid-cols-3 gap-6">

    {{-- Left: Items --}}
    <div class="md:col-span-2 space-y-4">

        {{-- Customer Info --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-3">Customer</h3>
            <p class="text-sm text-gray-600">{{ $order->user->name }}</p>
            <p class="text-sm text-gray-400">{{ $order->user->email }}</p>
        </div>

        {{-- Order Items --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Items</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                            @if($item->product->primaryImage)
                                <img src="{{ $item->product->primaryImage->url() }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">🖼️</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400">RM {{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</p>
                        </div>
                        <span class="font-bold text-sm">RM {{ number_format($item->subtotal(), 2) }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="border-t mt-4 pt-4 flex justify-between font-bold text-gray-800">
                <span>Total</span>
                <span class="text-indigo-600">RM {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Right: Status + Address --}}
    <div class="space-y-4">

        {{-- Update Status --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Update Status</h3>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                @csrf
                @method('PATCH')
                <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-3">
                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-3">📍 Shipping Address</h3>
            <div class="text-sm text-gray-600 space-y-1">
                <p>{{ $order->address->line1 }}</p>
                @if($order->address->line2)
                    <p>{{ $order->address->line2 }}</p>
                @endif
                <p>{{ $order->address->city }}, {{ $order->address->postcode }}</p>
                <p>{{ $order->address->country }}</p>
            </div>
        </div>

        {{-- Payment --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-3">💳 Payment</h3>
            <div class="text-sm text-gray-600 space-y-1">
                <div class="flex justify-between">
                    <span>Provider</span>
                    <span class="font-medium">{{ ucfirst($order->payment->provider) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Status</span>
                    <span class="font-medium {{ $order->payment->isPaid() ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection