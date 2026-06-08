<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        $orders = $user->orders()
        ->with(['items.product', 'payment'])
        ->latest()
        ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load(['items.product.primaryImage', 'payment', 'address']);

        return view('shop.orders.show', compact('order'));
    }
}
