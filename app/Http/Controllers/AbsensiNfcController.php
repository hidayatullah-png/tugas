<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiNfcController extends Controller
{
    public function index()
    {
        return view('dashboard.absensi.scan_nfc');
    }

    public function prosesScan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string'
        ]);

        $serialNumber = $request->serial_number;
        $hariIni = Carbon::today()->toDateString();
        $waktuSekarang = Carbon::now();

        // 1. Cari kartu NFC dan join langsung dengan tabel USERS
        $dataKartu = DB::table('nfc_cards')
            ->join('users', 'nfc_cards.user_id', '=', 'users.id')
            ->where('nfc_cards.serial_number', $serialNumber)
            ->select(
                'nfc_cards.id as card_id',
                'nfc_cards.status as card_status',
                'users.id as user_id',
                'users.email',
                'users.name'
            )
            ->first();

        // Jika kartu tidak terdaftar
        if (!$dataKartu) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC tidak dikenali oleh sistem.'
            ], 404);
        }

        // Jika kartu diblokir / inactive
        if ($dataKartu->card_status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC ini sudah dinonaktifkan.'
            ], 403);
        }

        // 2. Cek absen ganda (Double-tap hari ini)
        $sudahAbsen = DB::table('attendance_logs')
            ->where('user_id', $dataKartu->user_id)
            ->where('attendance_date', $hariIni)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => $dataKartu->name . ' sudah berhasil melakukan absensi hari ini.'
            ], 400);
        }

        // 3. Catat Kehadiran
        try {
            DB::table('attendance_logs')->insert([
                'user_id' => $dataKartu->user_id,
                'nfc_card_id' => $dataKartu->card_id,
                'attendance_date' => $hariIni,
                'scan_time' => $waktuSekarang,
                'status' => 'present',
                'created_at' => $waktuSekarang,
                'updated_at' => $waktuSekarang,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat. User sudah melakukan absensi.'
            ], 400);
        }

        // 4. Kirim respons ke UI (Menggunakan email sebagai ganti NIM)
        return response()->json([
            'success' => true,
            'nama_mahasiswa' => $dataKartu->name,
            'nim' => $dataKartu->email, // Dilempar ke key 'nim' agar frontend Javascript tidak perlu diubah
            'waktu' => $waktuSekarang->format('H:i:s'),
            'message' => '✓ Kehadiran dicatat: ' . $dataKartu->name
        ]);
    }
}