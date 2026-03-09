<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function account()
    {
        $user = Auth::user();
        return view('account', compact('user'));
    }

    public function register(Request $request){
        $validated = $request->validate([
            'login' => 'required|string|min:3|unique:users,login',
            'password' => 'required|string|min:6',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
        ], [
            'email.unique' => 'Этот email уже занят.',
            'login.unique' => 'Этот login уже занят.'
        ]);
        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);

        Auth::login($user);
        return redirect('/');
    }

    public function login(Request $request){
        $crendl = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($crendl)){
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Неверный email или пароль'])->withInput();
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            'password'   => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only('first_name', 'last_name', 'email', 'phone', 'address');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('account')->with('success', 'Профиль успешно обновлён');
    }


    public function orders()
    {
        $orders = Order::with('items.product')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('account-orders', compact('orders'));
    }



}
