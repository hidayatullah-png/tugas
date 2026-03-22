<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StudyCaseController;

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

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin.index');

    // Buku Routes
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Kategori Routes
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'delete'])->name('kategori.destroy');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

    // Study Case
    Route::prefix('/study-case')->group(function () {
        Route::get('/tabel-biasa', [StudyCaseController::class, 'tabelBiasa'])
            ->name('study-case.barang.tabel-biasa');

        Route::get('/tabel-datatables', [StudyCaseController::class, 'tabelDataTables'])
            ->name('study-case.barang.tabel-datatables');

        Route::get('/select2-kota', [StudyCaseController::class, 'select2Kota'])
            ->name('study-case.select2-kota');

    });
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