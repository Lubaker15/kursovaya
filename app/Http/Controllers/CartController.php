<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->unit_price * $quantity
                ];
                $total += $product->unit_price * $quantity;
            } else {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
        }

        return view('cart', compact('products', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $product = Product::findOrFail($productId);

        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($request->quantity > $product->stock_quantity) {
                return back()->with('error', 'Недостаточно товара на складе');
            }
            $cart[$id] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Количество обновлено');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Товар удален из корзины');
    }
}