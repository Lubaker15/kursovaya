<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('product_name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('category_name', 'like', "%{$query}%");
            })
            ->with('category')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id'    => $product->id,
                    'name'  => $product->product_name,
                    'price' => $product->unit_price,
                    'image' => asset($product->image_url ?? 'images/no_image.png'),
                    'category' => $product->category->category_name ?? '',
                ];
            });

        return response()->json($products);
    }
}