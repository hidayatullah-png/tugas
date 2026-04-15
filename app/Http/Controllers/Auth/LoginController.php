<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect setelah login biasa (Email & Password)
     */
    protected function redirectTo()
    {
        if (Auth::user()->role_id == 1) {
            return route('dashboard.admin.index');
        }

        // TAMBAHAN: Redirect untuk Vendor
        if (Auth::user()->role_id == 3) {
            return route('vendor.makanan.index');
        }

        return route('dashboard.visitor.index');
    }

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'handleGoogleCallback'
        ]);

        $this->middleware('auth')->only([
            'logout'
        ]);
    }

    /**
     * Redirect ke Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if (!$user->id_google) {
                    $user->update([
                        'id_google' => $googleUser->getId(),
                        'email_verified_at' => now(),
                    ]);
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'id_google' => $googleUser->getId(),
                    'password' => Hash::make('password_google'),
                    'email_verified_at' => now(),
                    'role_id' => 2, // Default visitor jika daftar via Google
                ]);
            }

            session(['otp_user_id' => $user->id]);

            // Generate OTP
            $otp = rand(100000, 999999);
            $user->update(['otp' => $otp]);

            Mail::raw("Kode OTP login Anda adalah: $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode OTP Login Google');
            });

            return redirect()->route('otp.form');

        } catch (\Exception $e) {
            return redirect('/login')
                ->withErrors('Login dengan Google gagal.');
        }
    }

    /**
     * Verifikasi OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Session OTP tidak ditemukan.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect('/login')->with('error', 'User tidak ditemukan.');
        }

        if ($user->otp == $request->otp) {
            Auth::login($user);
            $user->update(['otp' => null]);
            session()->forget('otp_user_id');

            // TAMBAHAN: Logika redirect berdasarkan role setelah OTP sukses
            if ($user->role_id == 1) {
                $targetRoute = 'dashboard.admin.index';
            } elseif ($user->role_id == 3) {
                $targetRoute = 'vendor.makanan.index';
            } else {
                $targetRoute = 'dashboard.visitor.index';
            }

            return redirect()->route($targetRoute);
        }

        return back()->with('error', 'OTP salah!');
    }
}