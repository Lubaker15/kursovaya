<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'login' => 'required|string|min:3|unique:users,login',
            'password' => 'required|string|min:6',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
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
            $request->session->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErorrs(['email' => 'Неверный email или пароль'])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()-regenerateToken();
        return redirect('/');
    }
}
