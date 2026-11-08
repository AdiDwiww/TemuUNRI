<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        $user = $request->user();

        // Pastikan pengguna sudah login
        if (! $user instanceof User) {
            return redirect()->route('login');
        }

        // Periksa role admin dari database, bukan dari input pengguna
        if (! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
