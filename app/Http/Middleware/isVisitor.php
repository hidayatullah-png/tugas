<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek Login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // 2. Cek Role ID langsung (Sesuai database kamu)
        // Kita sepakat tadi: 1 = Admin, 2 = Visitor
        // Jadi di sini kita cek apakah role_id BUKAN 2
        if ($user->role_id !== 2) {
            
            // Jika user memaksa masuk tapi dia bukan Visitor (misal dia Admin)
            // Kita bisa redirect ke dashboard Admin atau tampilkan 403 Forbidden
            
            // Opsi A: Tampilkan Error 403 (Lebih aman)
            abort(403, 'Akses ditolak. Halaman ini khusus Visitor.');

            // Opsi B: Redirect paksa ke halaman login (seperti kodemu yang lama)
            /*
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Anda bukan Visitor.');
            */
        }

        return $next($request);
    }
}