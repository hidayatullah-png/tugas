<?php

namespace App\Http\Controllers\Admin;

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

        return view('dashboard.admin.data master.buku.index', compact('buku'));
    }

    // --- 2. CREATE (Form Tambah) ---
    public function create()
    {
        // Kita butuh data kategori untuk Dropdown Pilihan
        $kategori = DB::table('kategori')->whereNull('deleted_at')->get();
        
        return view('dashboard.admin.data master.buku.create', compact('kategori'));
    }

    // --- 3. STORE (Simpan Data Baru) ---
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'kode'       => 'required|unique:buku,kode|max:20',
            'judul'      => 'required|max:500',
            'pengarang'  => 'required|max:200',
            'idkategori' => 'required|exists:kategori,idkategori', // Pastikan ID ada di tabel kategori
        ]);

        // Insert ke Database
        DB::table('buku')->insert([
            'kode'       => $request->kode,
            'judul'      => $request->judul,
            'pengarang'  => $request->pengarang,
            'idkategori' => $request->idkategori,
            'deleted_at' => null,
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    // --- 4. EDIT (Form Edit) ---
    public function edit($id)
    {
        // Ambil data buku berdasarkan idbuku
        $buku = DB::table('buku')->where('idbuku', $id)->first();
        
        // Ambil data kategori untuk Dropdown
        $kategori = DB::table('kategori')->whereNull('deleted_at')->get();

        return view('dashboard.admin.data master.buku.edit', compact('buku', 'kategori'));
    }

    // --- 5. UPDATE (Simpan Perubahan) ---
    public function update(Request $request, $id)
    {
        $request->validate([
            // Unique tapi abaikan ID buku ini sendiri
            'kode'       => 'required|max:20|unique:buku,kode,'.$id.',idbuku',
            'judul'      => 'required|max:500',
            'pengarang'  => 'required|max:200',
            'idkategori' => 'required',
        ]);

        DB::table('buku')->where('idbuku', $id)->update([
            'kode'       => $request->kode,
            'judul'      => $request->judul,
            'pengarang'  => $request->pengarang,
            'idkategori' => $request->idkategori,
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    // --- 6. DELETE (Hapus Soft Delete) ---
    public function destroy($id)
    {
        DB::table('buku')->where('idbuku', $id)->update([
            'deleted_at' => now()
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}