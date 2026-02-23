<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\CekKoneksiController;
use App\Http\Controllers\auth\LoginController;

//admin
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    BukuController,
    KategoriController
};

// Cek Koneksi Database
Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi']);

// Route bawaan Auth (Login, Register, Logout)
Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/verify-otp', function () {
        return view('verifications.index');
    })->name('otp.form');

    Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])
        ->name('verify.otp');

});

Route::get('/', function () {
    return view('auth.login');
})->name('login');


// Route bawaan Auth akan handle /login
Route::get('auth/google/redirect', [LoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');


Route::middleware(['auth', 'isAdministrator'])->group(function () {

    // --- Dashboard Admin ---
    Route::get('/dashboard-admin', [AdminDashboardController::class, 'index'])->name('dashboard-admin');

    // --- Master Data ---
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class);

    // --- Template Pages (Bawaan Purple Admin) ---
    // Kita masukkan ke sini agar pengunjung biasa tidak bisa intip tampilan admin

    Route::get('/forms/basic', function () {
        return view('pages.forms.basic');
    })->name('forms.basic');

    Route::get('/charts/chartjs', function () {
        return view('pages.charts.chartjs');
    })->name('charts.chartjs');

    Route::get('/pages/blank', function () {
        return view('pages.samples.blank');
    })->name('pages.blank');

    Route::get('/pages/404', function () {
        return view('pages.samples.404');
    })->name('pages.404');

});


// Route untuk pengunjung biasa (visitor)
Route::middleware(['auth', 'isVisitor'])->group(function () {

    Route::get('/dashboard-visitor', function () {
        return view('index');
    })->name('dashboard-visitor');

});