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
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Mengambil semua role yang dimiliki user dan mencari yang aktif
        $activeRoleUser = $user->roles->firstWhere('pivot.status', 1);
        
        // Jika tidak ada role aktif atau role aktif bukan Administrator (idrole = 1)
        if (!$activeRoleUser || (int) $activeRoleUser->idrole !== 1) {
            // Logout user dan redirect ke halaman login dengan pesan error
            Auth::logout(); 
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Anda bukan Administrator.');
        }

        return $next($request);
    }
}