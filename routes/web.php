<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StudyCaseController;
use App\Http\Controllers\KunjunganTokoController;


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

//sertif
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

    // CRUD Barang, Buku, Kategori
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);
    Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

    //studycase
    // Study Case
    Route::prefix('/study-case')->group(function () {
        Route::get('/tabel-biasa', [StudyCaseController::class, 'tabelBiasa'])
            ->name('study-case.barang.tabel-biasa');

        Route::get('/tabel-datatables', [StudyCaseController::class, 'tabelDataTables'])
            ->name('study-case.barang.tabel-datatables');

        Route::get('/select2-kota', [StudyCaseController::class, 'select2Kota'])
            ->name('study-case.select2-kota');

    });

    // AJAX & Axios Modules (Halaman Tampilan)
    Route::prefix('/modul-ajax')->group(function () {
        // Kasir
        Route::get('/kasir', [KasirController::class, 'index'])->name('modul_ajax.kasir');
        Route::get('/kasir-axios', [KasirController::class, 'indexAxios'])->name('modul_ajax.kasir-axios');

        // Wilayah
        Route::get('/wilayah', [WilayahController::class, 'wilayah'])->name('modul_ajax.wilayah');
        Route::get('/wilayah-axios', [WilayahController::class, 'wilayahAxios'])->name('modul_ajax.wilayah-axios');
    });

    // AJAX Endpoints (API Data)
    Route::get('/barang/search/{id}', [KasirController::class, 'search'])->name('barang.search');

    Route::prefix('/kasir')->group(function () {
        Route::post('/store', [KasirController::class, 'store'])->name('kasir.store');
    });

    // FIXED: Ditambahkan ->name('api.') agar sinkron dengan JavaScript
    Route::prefix('/wilayah')->name('api.')->group(function () {
        Route::post('/get-kota', [WilayahController::class, 'getKota'])->name('getKota');
        Route::post('/get-kecamatan', [WilayahController::class, 'getKecamatan'])->name('getKecamatan');
        Route::post('/get-kelurahan', [WilayahController::class, 'getKelurahan'])->name('getKelurahan');
    });

    Route::prefix('/wilayah')->name('api.')->group(function () {
        Route::post('/get-kota', [WilayahController::class, 'getKota'])->name('getKota');
        Route::post('/get-kecamatan', [WilayahController::class, 'getKecamatan'])->name('getKecamatan');
        Route::post('/get-kelurahan', [WilayahController::class, 'getKelurahan'])->name('getKelurahan');
    });
    //scanner
    Route::get('/scan-barang', [BarangController::class, 'scanner'])->name('admin.barang.scan');
    Route::get('/api/cari-barang/{id}', [BarangController::class, 'cariBarang'])->name('admin.api.cari-barang');
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

    // 3. Customer (Akses Kamera)
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
    //scanner
    Route::get('/scan-pesanan', [VendorPesananController::class, 'scanQR'])->name('vendor.pesanan.scan_qr');
    Route::get('/api/cek-pesanan/{id}', [VendorPesananController::class, 'cekPesanan'])->name('vendor.api.cek-pesanan');


});

/*
|--------------------------------------------------------------------------
| Kungjungan Routes
|--------------------------------------------------------------------------
*/
Route::get('/kunjungan-toko', [KunjunganTokoController::class, 'index'])->name('kunjungan.index');

Route::prefix('api/kunjungan')->name('api.kunjungan.')->group(function () {
    Route::get('/cek-toko/{barcode}', [KunjunganTokoController::class, 'cekToko'])->name('cek-toko');
    Route::post('/verifikasi', [KunjunganTokoController::class, 'verifikasiPosisi'])->name('verifikasi');
});