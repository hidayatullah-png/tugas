<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsVendor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki role_id = 3 (Vendor)
        if (Auth::check() && Auth::user()->role_id == '3') {
            return $next($request);
        }

        // Jika bukan vendor, lemparkan error 403 Forbidden
        abort(403, 'Maaf, halaman ini khusus untuk Vendor Kantin.');
    }
}