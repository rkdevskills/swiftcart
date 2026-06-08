<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $orderCount = Order::count();

        $sumRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        $productCount = Product::count();

        $customersCount = User::where('role', 'customer')->count();

        $lastFiveOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('orderCount', 'sumRevenue', 'productCount', 'customersCount', 'lastFiveOrders'));
    }
}
