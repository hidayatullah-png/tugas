<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KunjunganTokoController extends Controller
{
    // ==========================================
    // 1. MANAJEMEN TOKO
    // ==========================================

    /**
     * Menampilkan daftar toko dan riwayat kunjungan.
     */
    public function index()
    {
        $toko = DB::table('lokasi_toko')->get();

        // Mengambil riwayat kunjungan beserta nama toko
        $riwayat = DB::table('kunjungan')
            ->join('lokasi_toko', 'kunjungan.barcode', '=', 'lokasi_toko.barcode')
            ->select('kunjungan.*', 'lokasi_toko.nama_toko')
            ->orderBy('kunjungan.waktu_kunjungan', 'desc')
            ->get();

        return view('kunjungan.index', compact('toko', 'riwayat'));
    }

    /**
     * Menampilkan form untuk menambahkan toko baru.
     */
    public function tambahTokoPage()
    {
        return view('kunjungan.tambah');
    }

    /**
     * Menyimpan data toko baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:50',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
        ]);

        // Generate Barcode otomatis sepanjang 8 karakter
        $barcode = Str::random(8);

        DB::table('lokasi_toko')->insert([
            'barcode' => $barcode,
            'nama_toko' => $request->nama_toko,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kunjungan.index')->with('success', 'Toko berhasil ditambahkan dengan Barcode: ' . $barcode);
    }

    // ==========================================
    // 2. PROSES KUNJUNGAN & GEOLOCATION
    // ==========================================

    /**
     * Menampilkan halaman scanner QR Code dan lokasi sales.
     */
    public function kunjungiPage()
    {
        return view('kunjungan.kunjungi');
    }

    /**
     * Endpoint API untuk mengambil detail toko berdasarkan barcode hasil scan.
     */
    public function cekToko($barcode)
    {
        $toko = DB::table('lokasi_toko')->where('barcode', $barcode)->first();

        if ($toko) {
            return response()->json(['success' => true, 'data' => $toko]);
        }

        return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.']);
    }

    /**
     * Endpoint API untuk memvalidasi jarak koordinat (Formula Haversine).
     */
    public function verifikasiPosisi(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'latitude_sales' => 'required|numeric',
            'longitude_sales' => 'required|numeric',
            'accuracy_sales' => 'required|numeric',
        ]);

        $toko = DB::table('lokasi_toko')->where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.']);
        }

        // 1. Terapkan Formula Haversine untuk menghitung Jarak Aktual [cite: 88, 135]
        $lat1 = $toko->latitude;
        $lng1 = $toko->longitude;
        $lat2 = $request->latitude_sales;
        $lng2 = $request->longitude_sales;

        $R = 6371000; // Radius bumi dalam meter [cite: 179]
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarak_aktual = round($R * $c, 2); // Hasil dalam meter [cite: 184]

        // 2. Hitung Threshold Efektif [cite: 135]
        $threshold_base = 300; // Batas toleransi jarak dasar [cite: 193]
        $threshold_efektif = $threshold_base + $toko->accuracy + $request->accuracy_sales; // Mengakomodasi ketidakakuratan GPS [cite: 194]

        // 3. Validasi Status [cite: 135]
        $status = ($jarak_aktual <= $threshold_efektif) ? 'DITERIMA' : 'DITOLAK';

        // 4. Simpan ke database kunjungan [cite: 135]
        DB::table('kunjungan')->insert([
            'barcode' => $toko->barcode,
            'user_id' => auth()->user()->id,
            'latitude_sales' => $lat2,
            'longitude_sales' => $lng2,
            'accuracy_sales' => $request->accuracy_sales,
            'jarak_aktual' => $jarak_aktual,
            'threshold_efektif' => $threshold_efektif,
            'status' => $status,
            'waktu_kunjungan' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'status' => $status,
            'jarak_aktual' => $jarak_aktual,
            'threshold_efektif' => $threshold_efektif,
            'message' => 'Kunjungan ' . $status
        ]);
    }
}