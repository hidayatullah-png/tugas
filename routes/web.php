<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;


// --- 2. THE NEW ROUTE FOR DATABASE CHECK ---
Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi']);


// --- EXISTING ROUTES BELOW ---

// Default Landing Page
Route::get('/', function () {
    return redirect('/dashboard'); // Auto-redirect to dashboard
});

// Auth Routes (Login, Register, Logout)
Auth::routes();

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::resource('buku', BukuController::class);

// --- TEMPLATE PAGES ---

// Forms
Route::get('/forms/basic', function () {
    return view('pages.forms.basic');
})->name('forms.basic');

// Charts
Route::get('/charts/chartjs', function () {
    return view('pages.charts.chartjs');
})->name('charts.chartjs');

// User Pages (Samples)
Route::get('/pages/blank', function () {
    return view('pages.samples.blank');
})->name('pages.blank');

Route::get('/pages/404', function () {
    return view('pages.samples.404');
})->name('pages.404');