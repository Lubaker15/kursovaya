<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function edit(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,cancelled',
            'delivery_address' => 'required|string|max:255',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'delivery_address' => $request->delivery_address,
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Заказ обновлён');
    }
}