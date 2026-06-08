@extends('layouts.shop')

@section('title', 'Order #' . $order->id)

@section('content')

{{-- Back --}}
<a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline mb-6 inline-block">
    ← Back to My Orders
</a>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Order #{{ $order->id }}</h1>

<div class="grid md:grid-cols-3 gap-6">

    {{-- Left: Items --}}
    <div class="md:col-span-2 space-y-4">

        {{-- Order Status --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $order->items->count() }} item(s)</p>
                </div>
                @php
                    $badge = match($order->status) {
                        'pending'    => 'bg-yellow-100 text-yellow-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'shipped'    => 'bg-purple-100 text-purple-700',
                        'delivered'  => 'bg-green-100 text-green-700',
                        'cancelled'  => 'bg-red-100 text-red-700',
                        default      => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $badge }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4">
                        {{-- Image --}}
                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 shrink-0">
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
                            <p class="font-medium text-gray-800 text-sm line-clamp-1">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-400">RM {{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</p>
                        </div>

                        {{-- Subtotal --}}
                        <span class="font-bold text-gray-800 shrink-0">
                            RM {{ number_format($item->subtotal(), 2) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right: Summary, Address, Payment --}}
    <div class="space-y-4">

        {{-- Order Summary --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Order Summary</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>RM {{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span>{{ $order->shipping > 0 ? 'RM ' . number_format($order->shipping, 2) : 'Free' }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 text-base border-t pt-2">
                    <span>Total</span>
                    <span class="text-indigo-600">RM {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-3">📍 Shipping Address</h2>
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
            <h2 class="font-bold text-gray-800 mb-3">💳 Payment</h2>
            <div class="text-sm text-gray-600 space-y-1">
                <div class="flex justify-between">
                    <span>Method</span>
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