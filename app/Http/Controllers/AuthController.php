<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard'); // Pastikan ini admin.dashboard
        }
        
        return view('internal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $key = Str::lower($request->input('username')).'|'.$request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'username' => [
                    "Terlalu banyak percobaan login. Silakan coba lagi dalam $seconds detik."
                ],
            ]);
        }

        $credentials = [
            'password' => $request->password
        ];

        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->username;
        } else {
            $credentials['name'] = $request->username;
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard')); // Pastikan ini admin.dashboard
        }

        RateLimiter::hit($key, 60);

        throw ValidationException::withMessages([
            'username' => ['Username atau password salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}