@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
{{-- Header --}}
    @if($orders->isEmpty())
        <p class="text-gray-400 text-sm">No orders found.</p>
    @else
        <div class="bg-white rounded-xl shadow-sm p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-3">Order ID</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Created At</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                        <tr>
                            <td class="py-3 font-medium">{{ $order->id }}</td>
                            <td class="py-3 text-gray-600">{{ $order->user ? $order->user->name : 'Guest' }}</td>
                            <td class="py-3 text-gray-600">RM {{ number_format($order->total, 2) }}</td>
                            <td class="py-3">
                                @if($order->status == 'pending')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Processing</span>
                                @elseif($order->status == 'shipped')
                                    <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">Shipped</span>
                                @elseif($order->status == 'delivered')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Delivered</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Cancelled</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>            
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif

@endsection