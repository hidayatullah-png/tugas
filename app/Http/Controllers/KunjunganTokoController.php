<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KunjunganTokoController extends Controller
{
    public function index()
    {
        return view('dashboard.kunjungan.index');
    }

    // 1. Ambil info toko berdasarkan scan barcode
    public function cekToko($barcode)
    {
        $toko = DB::table('lokasi_toko')->where('barcode', $barcode)->first();

        if ($toko) {
            return response()->json(['success' => true, 'data' => $toko]);
        }
        return response()->json(['success' => false, 'message' => 'Toko tidak terdaftar!'], 404);
    }

    // 2. Verifikasi posisi Sales dengan Formula Haversine & Batas Toleransi Akurasi
    public function verifikasiPosisi(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'sales_lat' => 'required|numeric',
            'sales_lng' => 'required|numeric',
            'sales_acc' => 'required|numeric',
        ]);

        // Ambil data acuan koordinat toko dari DB
        $toko = DB::table('lokasi_toko')->where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json(['success' => false, 'message' => 'Data toko tidak ditemukan'], 404);
        }

        // Hitung Jarak Aktual menggunakan fungsi Haversine (Lampiran 2)
        $jarak_aktual = $this->haversineFormula(
            $toko->latitude,
            $toko->longitude,
            $request->sales_lat,
            $request->sales_lng
        );

        // Aturan Ambang Batas (Threshold Efektif) sesuai Lampiran 3
        $threshold_minimal = 300; // Jarak dasar maksimum standar (dalam meter)
        $threshold_efektif = $threshold_minimal + $toko->accuracy + $request->sales_acc; // Perhitungan Lampiran 3

        // Evaluasi aturan sistem
        if ($jarak_aktual <= $threshold_efektif) {
            return response()->json([
                'success' => true,
                'status' => 'DITERIMA',
                'jarak' => round($jarak_aktual, 2),
                'threshold_efektif' => round($threshold_efektif, 2),
                'message' => '✓ Konfirmasi Berhasil: Sales berada di lokasi toko.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'DITOLAK',
                'jarak' => round($jarak_aktual, 2),
                'threshold_efektif' => round($threshold_efektif, 2),
                'message' => '✗ Kunjungan Ditolak: Posisi sales terlalu jauh dari target koordinat toko.'
            ], 400);
        }
    }

    // Implementasi Pseudocode Formula Haversine (Lampiran 2)
    private function haversineFormula($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c; // Mengembalikan hasil dalam satuan meter
    }
}