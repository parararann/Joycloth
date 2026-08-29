<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsolateAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya jalankan isolasi jika benar-benar di URL admin
        if ($request->is('admin') || $request->is('admin/*')) {
            config(['session.cookie' => 'joycloth_admin_session']);
        }

        return $next($request);
    }
}
