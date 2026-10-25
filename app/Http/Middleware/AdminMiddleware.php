<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Hanya pengguna dengan role 'admin' yang diizinkan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Periksa role admin dari database, bukan dari input pengguna
        if (! $request->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
