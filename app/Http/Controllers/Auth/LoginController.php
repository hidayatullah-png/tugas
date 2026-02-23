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

    protected function redirectTo()
    {
        if (Auth::user()->role_id == 1) {
            return '/dashboard-admin';
        }

        return '/dashboard-visitor';
    }

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'handleGoogleCallback',
            'verifyOtp'
        ]);

        $this->middleware('auth')->only([
            'logout',
            'verifyOtp'
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
                    'role_id' => 2, // otomatis visitor
                ]);
            }

            Auth::login($user);

            // =========================
            // OTP HANYA UNTUK GOOGLE
            // =========================

            $otp = rand(100000, 999999);

            $user->update([
                'otp' => $otp
            ]);

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
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = Auth::user();

        if ($user->otp == $request->otp) {

            $user->update(['otp' => null]);

            if ($user->role_id == 1) {
                return redirect('/dashboard-admin');
            } else {
                return redirect('/dashboard-visitor');
            }

        } else {
            return back()->with('error', 'OTP salah!');
        }
    }
}
