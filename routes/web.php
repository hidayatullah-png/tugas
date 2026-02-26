<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\PdfController;

// Admin
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    BukuController,
    KategoriController,
    BarangController
};

// Visitor
use App\Http\Controllers\Visitor\{
    BukuController as VisitorBukuController,
    KategoriController as VisitorKategoriController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Cek Koneksi Database
Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi']);

// Route Auth bawaan Laravel
Auth::routes();

// Default redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard redirect berdasarkan role
Route::get('/dashboard', function () {

    if (Auth::user()->role_id == '1') {
        return redirect()->route('dashboard.admin.index');
    }

    if (Auth::user()->role_id == '2') {
        return redirect()->route('dashboard.visitor.index');
    }

    abort(403);

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
*/

// Form OTP
Route::get('/verify-otp', function () {

    if (!session('otp_user_id')) {
        return redirect()->route('login');
    }

    return view('verifications.index');

})->name('otp.form');

// Proses OTP
Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])
    ->name('verify.otp');


/*
|--------------------------------------------------------------------------
| Google Login
|--------------------------------------------------------------------------
*/

Route::prefix('auth/google')->group(function () {

    Route::get('/redirect', [LoginController::class, 'redirectToGoogle'])
        ->name('auth.google.redirect');

    Route::get('/callback', [LoginController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
});


/*
|--------------------------------------------------------------------------
| PDF Routes (optional: bisa diberi middleware auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/pdf/landscape', [PdfController::class, 'landscape'])
        ->name('pdf.sertifikat');

    Route::get('/pdf/potrait', [PdfController::class, 'potrait'])
        ->name('pdf.undangan');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'isAdministrator'])->group(function () {

    Route::get('/dashboard-admin', [AdminDashboardController::class, 'index'])
        ->name('dashboard.admin.index');

    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);

});


/*
|--------------------------------------------------------------------------
| Visitor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('visitor')->middleware(['auth', 'isVisitor'])->group(function () {

    Route::get('/dashboard-visitor', function () {
        return view('dashboard.index');
    })->name('dashboard.visitor.index');

    Route::get('/buku', [VisitorBukuController::class, 'index'])
        ->name('visitor.buku.index');

    Route::get('/kategori', [VisitorKategoriController::class, 'index'])
        ->name('visitor.kategori.index');

});