<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\CekKoneksiController;
use App\Http\Controllers\AuthController;

//admin
use App\Http\Controllers\Admin\{
    AdminDashboardController, 
    BukuController, 
    KategoriController};

// Cek Koneksi Database
Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi']);

// Redirect halaman awal ke login
Route::get('/', function () {
    return redirect('/login');
});

// Route bawaan Auth (Login, Register, Logout)
Auth::routes();


Route::middleware(['auth', 'isAdministrator'])->group(function () {

    // --- Dashboard Admin ---
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

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


/*
|--------------------------------------------------------------------------
| 3. VISITOR ROUTES (Nanti ditaruh sini)
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth', 'is_visitor'])->group(function () {
//     // Route::get('/katalog', ...);
// });