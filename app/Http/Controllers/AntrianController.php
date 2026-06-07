<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrianController extends Controller
{
    // ==========================================
    // 1. HALAMAN & LOGIKA GUEST (PENDAFTARAN)
    // ==========================================
    public function guestForm()
    {
        return view('antrian.guest'); // Akan kita buat view-nya nanti
    }

    public function submitGuest(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150'
        ]);

        $hariIni = Carbon::today()->toDateString();

        // Cari nomor antrian terakhir hari ini
        $antrianTerakhir = DB::table('antrians')
            ->where('tanggal', $hariIni)
            ->max('nomor_antrian');

        $nomorBaru = $antrianTerakhir ? $antrianTerakhir + 1 : 1;

        // Simpan ke database
        DB::table('antrians')->insert([
            'nomor_antrian' => $nomorBaru,
            'nama' => $request->nama,
            'status' => 'menunggu',
            'tanggal' => $hariIni,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with([
            'success' => 'Berhasil mendaftar antrian!',
            'nomor' => $nomorBaru,
            'nama' => $request->nama
        ]);
    }

    // ==========================================
    // 2. HALAMAN ADMIN & PAPAN ANTRIAN
    // ==========================================
    public function adminDashboard()
    {
        return view('dashboard.admin.data_master.antrian.admin');
    }

    public function papanAntrian()
    {
        return view('antrian.papan');
    }

    // ==========================================
    // 3. CORE SYSTEM: SERVER-SENT EVENTS (SSE)
    // ==========================================
    public function streamSSE(Request $request)
    {
        // Mencegah script berhenti karena timeout [cite: 60]
        set_time_limit(0);

        return response()->stream(function () {
            while (true) {
                $hariIni = Carbon::today()->toDateString();

                // Ambil semua data antrian hari ini yang relevan
                $menunggu = DB::table('antrians')->where('tanggal', $hariIni)->where('status', 'menunggu')->orderBy('nomor_antrian', 'asc')->get();
                $dipanggil = DB::table('antrians')->where('tanggal', $hariIni)->where('status', 'dipanggil')->orderBy('updated_at', 'desc')->first();
                $terlewat = DB::table('antrians')->where('tanggal', $hariIni)->where('status', 'terlewat')->orderBy('nomor_antrian', 'asc')->get();

                $data = [
                    'menunggu' => $menunggu,
                    'dipanggil' => $dipanggil,
                    'terlewat' => $terlewat
                ];

                // Format SSE [cite: 16, 25]
                echo "event: queue-update\n";
                echo "data: " . json_encode($data) . "\n\n";

                ob_flush();
                flush();

                // Hentikan loop jika browser ditutup/disconnect [cite: 25]
                if (connection_aborted()) {
                    break;
                }

                // Cek update setiap 1 detik [cite: 25]
                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no' // Penting untuk server Nginx [cite: 25, 60]
        ]);
    }

    // ==========================================
    // 4. LOGIKA TOMBOL ADMIN (PANGGIL / TERLEWAT / SELESAI)
    // ==========================================
    public function panggil(Request $request, $id)
    {
        // Ubah status yang saat ini 'dipanggil' menjadi 'selesai' terlebih dahulu (agar tidak ada antrian ganda di layar)
        DB::table('antrians')
            ->where('tanggal', Carbon::today()->toDateString())
            ->where('status', 'dipanggil')
            ->update(['status' => 'selesai', 'updated_at' => Carbon::now()]);

        // Panggil antrian yang baru
        DB::table('antrians')
            ->where('id', $id)
            ->update(['status' => 'dipanggil', 'updated_at' => Carbon::now()]);

        return response()->json(['success' => true, 'message' => 'Antrian dipanggil']);
    }

    public function tandaiTerlewat(Request $request, $id)
    {
        DB::table('antrians')
            ->where('id', $id)
            ->update(['status' => 'terlewat', 'updated_at' => Carbon::now()]);

        return response()->json(['success' => true, 'message' => 'Tamu ditandai terlewat']);
    }

    public function selesai(Request $request, $id)
    {
        DB::table('antrians')
            ->where('id', $id)
            ->update(['status' => 'selesai', 'updated_at' => Carbon::now()]);

        return response()->json(['success' => true, 'message' => 'Antrian selesai']);
    }
}