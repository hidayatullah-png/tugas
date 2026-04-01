<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    /**
     * Menampilkan halaman Wilayah versi jQuery AJAX
     * Route: modul-ajax.wilayah
     */
    public function wilayah()
    {
        $response = Http::get("{$this->baseUrl}/provinces.json");
        $provinces = $response->json();

        return view('dashboard.admin.data_master.modul_ajax.wilayah', compact('provinces'));
    }

    /**
     * Menampilkan halaman Wilayah versi Axios
     * Route: modul-ajax.wilayah-axios
     */
    public function wilayahAxios()
    {
        $response = Http::get("{$this->baseUrl}/provinces.json");
        $provinces = $response->json();

        return view('dashboard.admin.data_master.modul_ajax.wilayah-axios', compact('provinces'));
    }

    /**
     * Endpoint API untuk mendapatkan Kota
     */
    public function getKota(Request $request)
    {
        $response = Http::get("{$this->baseUrl}/regencies/{$request->id}.json");
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }

    /**
     * Endpoint API untuk mendapatkan Kecamatan
     */
    public function getKecamatan(Request $request)
    {
        $response = Http::get("{$this->baseUrl}/districts/{$request->id}.json");
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }

    /**
     * Endpoint API untuk mendapatkan Kelurahan
     */
    public function getKelurahan(Request $request)
    {
        $response = Http::get("{$this->baseUrl}/villages/{$request->id}.json");
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }
}