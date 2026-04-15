<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StudyCaseController;

// --- GUEST & VENDOR CONTROLLERS ---
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Vendor\VendorMakananController;
use App\Http\Controllers\Vendor\VendorPesananController;
use App\Http\Controllers\Vendor\VendorCustomerController;

// Admin
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    BukuController,
    KategoriController,
    BarangController,
    WilayahController,
    KasirController
};

// Visitor
use App\Http\Controllers\Visitor\{
    BukuController as VisitorBukuController,
    KategoriController as VisitorKategoriController
};

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi']);
Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (Auth::user()->role_id == '1')
        return redirect()->route('dashboard.admin.index');
    if (Auth::user()->role_id == '2')
        return redirect()->route('dashboard.visitor.index');
    if (Auth::user()->role_id == '3')
        return redirect()->route('vendor.makanan.index');
    abort(403);
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| OTP & Socialite
|--------------------------------------------------------------------------
*/

Route::get('/verify-otp', function () {
    if (!session('otp_user_id'))
        return redirect()->route('login');
    return view('verifications.index');
})->name('otp.form');

Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');

Route::prefix('auth/google')->group(function () {
    Route::get('/redirect', [LoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/callback', [LoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'isAdministrator'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin.index');

    // CRUD Barang, Buku, Kategori, dsb... (sesuai kode kamu sebelumnya)
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);

    Route::prefix('/wilayah')->name('api.')->group(function () {
        Route::post('/get-kota', [WilayahController::class, 'getKota'])->name('getKota');
        Route::post('/get-kecamatan', [WilayahController::class, 'getKecamatan'])->name('getKecamatan');
        Route::post('/get-kelurahan', [WilayahController::class, 'getKelurahan'])->name('getKelurahan');
    });
});

/*
|--------------------------------------------------------------------------
| Guest Order Routes
|--------------------------------------------------------------------------
*/

Route::prefix('order')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('guest.index');
    Route::get('/finish', [GuestController::class, 'finish'])->name('guest.finish');
    Route::get('/menu/{vendor_id}', [GuestController::class, 'getMenu'])->name('guest.getMenu');
    Route::post('/checkout', [GuestController::class, 'checkout'])->name('guest.checkout');
    Route::get('/payment/success', [VendorPesananController::class, 'selesai'])->name('payment.finish');
});

Route::post('/payment/callback', [GuestController::class, 'callback'])->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->middleware(['auth', 'isVendor'])->group(function () {

    // 1. Kelola Makanan
    Route::resource('makanan', VendorMakananController::class)->names('vendor.makanan');

    // 2. Pesanan Masuk
    Route::get('/pesanan', [VendorPesananController::class, 'index'])->name('vendor.pesanan.index');

    // 3. Studi Kasus 3: Customer (Akses Kamera)
    Route::prefix('customer')->group(function () {
        Route::get('/', [VendorCustomerController::class, 'index'])->name('vendor.customer.index');

        // Metode 1: Simpan sebagai BLOB (Database)
        Route::get('/create-blob', [VendorCustomerController::class, 'create1'])->name('vendor.customer.create1');
        Route::post('/store-blob', [VendorCustomerController::class, 'store1'])->name('vendor.customer.store1');

        // Metode 2: Simpan sebagai File Path (Folder)
        Route::get('/create-path', [VendorCustomerController::class, 'create2'])->name('vendor.customer.create2');
        Route::post('/store-path', [VendorCustomerController::class, 'store2'])->name('vendor.customer.store2');
        //destroy
        Route::delete('/destroy/{id}', [VendorCustomerController::class, 'destroy'])->name('vendor.customer.destroy');
    });

    Route::prefix('api')->name('vendor.api.')->group(function () {
        Route::get('/kota', [VendorCustomerController::class, 'getKota'])->name('kota');
        Route::get('/kecamatan', [VendorCustomerController::class, 'getKecamatan'])->name('kecamatan');
        Route::get('/kelurahan', [VendorCustomerController::class, 'getKelurahan'])->name('kelurahan');
    });
});