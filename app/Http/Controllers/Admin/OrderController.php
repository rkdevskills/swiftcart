<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'address']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }
}
