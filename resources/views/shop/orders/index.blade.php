@extends('layouts.shop')

@section('title', 'My Orders')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">📦 My Orders</h1>

@if($orders->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">📦</p>
        <p class="text-lg font-medium">You have not placed any orders yet.</p>
        <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">
            Start Shopping
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
            <a href="{{ route('orders.show', $order) }}"
               class="block bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-semibold text-gray-800">Order #{{ $order->id }}</span>
                    <span class="text-sm text-gray-400">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        {{ $order->items->count() }} item(s)
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-indigo-600">RM {{ number_format($order->total, 2) }}</span>

                        {{-- Status Badge --}}
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
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
@endif
@endsection