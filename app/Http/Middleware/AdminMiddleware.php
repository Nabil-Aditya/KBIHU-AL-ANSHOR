<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Anda tidak memiliki hak akses admin.');
        }

        return $next($request);
    }
}