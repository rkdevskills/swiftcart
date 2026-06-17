@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        {{-- Revenue --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-indigo-600">£ {{ number_format($sumRevenue, 2) }}</p>
        </div>

        {{-- Orders --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-1">Total Orders</p>
            <p class="text-2xl font-bold text-gray-800">{{ $orderCount }}</p>
        </div>

        {{-- Products --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-1">Total Products</p>
            <p class="text-2xl font-bold text-gray-800">{{ $productCount }}</p>
        </div>

        {{-- Customers --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-1">Total Customers</p>
            <p class="text-2xl font-bold text-gray-800">{{ $customersCount }}</p>
        </div>

    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-4">Recent Orders</h2>

        @if($lastFiveOrders->isEmpty())
            <p class="text-gray-400 text-sm">No orders yet.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-3">Order #</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($lastFiveOrders as $order)
                        <tr>
                            <td class="py-3 font-medium">#{{ $order->id }}</td>
                            <td class="py-3 text-gray-600">{{ $order->user->name }}</td>
                            <td class="py-3 font-medium text-indigo-600">£ {{ number_format($order->total, 2) }}</td>
                            <td class="py-3">
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
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-indigo-600 hover:underline text-xs font-medium">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection