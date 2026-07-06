<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $user = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$user) {
            return redirect()->back()->withErrors([
                'auth' => 'Неверный логин или пароль',
            ]);
        }

        return redirect()->route('home');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
