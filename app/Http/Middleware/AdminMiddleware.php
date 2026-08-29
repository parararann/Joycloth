<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memastikan user yang mengakses adalah admin.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan memiliki role admin
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda bukan Administrator.');
        }

        return $next($request);
    }
}
