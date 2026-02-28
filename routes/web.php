<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {return view('index');});

Route::get('/catalog', function () {return view('catalog');});

Route::get('/cart', function () {return view('cart');});

Route::get('/cart', function () {return view('cart');});

Route::get('/delivery', function () {return view('delivery');});

Route::get('/service', function () {return view('service');});

Route::get('/contacts', function () {return view('contacts');});

Route::get('/auth', function () {return view('auth');});

Route::get('/account', function () {return view('account');});



Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


