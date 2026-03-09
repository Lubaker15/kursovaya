<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $usersCount = User::count();
        $ordersCount = Order::count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'productsCount', 'categoriesCount', 'usersCount', 'ordersCount', 'recentOrders', 'admin',
        ));
    }
}