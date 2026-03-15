<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/delivery', function () {return view('delivery');});
Route::get('/service', function () {return view('service');});
Route::get('/contacts', function () {return view('contacts');});
Route::get('/auth', function () { return view('auth'); })->name('login');;
Route::get('/account', function () {return view('account');});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/category/{id}', [CatalogController::class, 'category'])->name('category.show');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::post('/account/update', [AuthController::class, 'updateProfile'])->name('account.update');
    Route::get('/account/orders', [AuthController::class, 'orders'])->name('account.orders');

    Route::post('/order/quick', [OrderController::class, 'quickOrder'])->name('order.quick');
    Route::post('/order/pay/{id}', [OrderController::class, 'markAsPaid'])->name('order.pay');
    Route::post('/order/place', [OrderController::class, 'place'])->name('order.place');
    Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'edit', 'update']);
});


