<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $latestProducts = Product::with('category')->latest()->take(9)->get();

        return view('index', compact('latestProducts'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('product', compact('product'));
    }
}
