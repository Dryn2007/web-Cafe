<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user sesuai dengan yang diminta route
        if (Auth::user()->role !== $role) {
            // Jika Admin mencoba masuk halaman User, atau User coba masuk halaman Admin:
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin ke halaman ini.');
        }

        return $next($request);
    }
}
