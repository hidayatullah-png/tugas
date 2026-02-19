<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdministrator
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // 2. Cek Role ID langsung dari tabel users
        // Sesuai data dummy: 1 = Admin, 2 = Visitor/Member
        if ($user->role_id !== 1) {
            
            abort(403, 'Akses ditolak. Halaman ini khusus Administrator.');
        }

        // 3. Jika role_id == 1, izinkan lewat
        return $next($request);
    }
}
