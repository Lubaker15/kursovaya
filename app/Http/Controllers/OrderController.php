<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function place(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $user = auth()->user();

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'delivery_address' => $request->address,
                'order_date' => now(),
                'payment_status' => 'pending',
            ]);

            $total = 0;

            foreach ($cart as $productId => $quantity) { 
                $product = Product::find($productId);

                if (!$product || $product->stock_quantity < $quantity) {
                    throw new \Exception("Товар '{$product->product_name}' недоступен в нужном количестве");
                }

                $itemTotal = $product->unit_price * $quantity;
                $total += $itemTotal;

                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->unit_price,
                    'total_price' => $itemTotal,
                ]);

                $product->decrement('stock_quantity', $quantity);
            }

            $order->update(['total' => $total]);

            session()->forget('cart');

            DB::commit();

            return redirect()->route('cart.index')->with([
                'order_success' => true,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('order-success', compact('order'));
    }

    public function markAsPaid($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        if ($order->payment_status !== 'paid') {
            $order->payment_status = 'paid';
            $order->save();
            return back()->with('success', 'Заказ отмечен как оплаченный');
        }
        return back()->with('error', 'Заказ уже оплачен');
    }


    public function quickOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'address'    => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($product->stock_quantity < $quantity) {
            return redirect()->back()->with('error', 'Недостаточно товара на складе');
        }

        $user = auth()->user();
        $total = $product->unit_price * $quantity;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'          => $user->id,
                'delivery_address' => $request->address,
                'order_date'       => now(),
                'payment_status'   => 'pending',
                'total'            => $total,
            ]);

            OrderItems::create([
                'order_id'    => $order->id,
                'product_id'  => $product->id,
                'quantity'    => $quantity,
                'unit_price'  => $product->unit_price,
                'total_price' => $total,
            ]);

            $product->decrement('stock_quantity', $quantity);

            DB::commit();

            return redirect()->route('product.show', $product->id)->with('order_success', true)->with('order_id', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при оформлении заказа');
        }
    }
}