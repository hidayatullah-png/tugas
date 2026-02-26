<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    // --- 1. READ (Tampilkan Data) ---
    public function index()
    {
        // Gabungkan tabel BUKU dan KATEGORI
        $buku = DB::table('buku')
            ->join('kategori', 'buku.idkategori', '=', 'kategori.idkategori')
            ->select(
                'buku.*',
                'kategori.nama_kategori' // Ambil nama kategori untuk ditampilkan
            )
            ->whereNull('buku.deleted_at') // Hanya ambil yang belum dihapus
            ->orderBy('buku.idbuku', 'desc')
            ->get();

        return view('dashboard.visitor.data_master.buku.index', compact('buku'));
    }
}

