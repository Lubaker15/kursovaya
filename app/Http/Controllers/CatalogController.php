<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        foreach ($categories as $category) {
            if ($category->products->isNotEmpty()) {
                $randomProduct = $category->products->random();
                $category->random_image = $randomProduct->image_url;
            } else {
                $category->random_image = 'images/no_image.png';
            }
        }

        return view('catalog', compact('categories'));
    }


    public function category(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $query = Product::where('category_id', $id);

        if ($request->has('price_min') && $request->price_min !== null && $request->price_min !== '') {
            $query->where('unit_price', '>=', (float)$request->price_min);
        }
        if ($request->has('price_max') && $request->price_max !== null && $request->price_max !== '') {
            $query->where('unit_price', '<=', (float)$request->price_max);
        }

        if ($request->has('availability') && $request->availability !== '') {
            if ($request->availability === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('stock_quantity', '=', 0);
            }
        }

        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('unit_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('unit_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('product_name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc'); 
        }

        $products = $query->paginate(8)->withQueryString(); 

        return view('category', compact('category', 'products'));
    }
}