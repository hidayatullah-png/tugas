<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        // PERBAIKAN 1: Nama variabel disamakan dengan compact
        $kategori = DB::table('kategori')->get();

        // Pastikan view foldernya benar (resources/views/admin/kategori/index.blade.php)
        return view('dashboard.admin.data_master.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('dashboard.admin.data_master.kategori.create');
    }

    public function store(Request $request)
    {
        // PERBAIKAN 2: Validasi disesuaikan dengan nama kolom tabel (nama_kategori)
        // Asumsi: <input name="nama_kategori"> di form HTML kamu
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori|max:100',
        ]);

        // PERBAIKAN 3: Insert ke kolom yang benar
        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
            
            // HAPUS 'created_at' => now(), KARENA TABELMU TIDAK PUNYA KOLOM ITU
            
            'deleted_at' => null, 
        ]);

        // PERBAIKAN 4: Route name biasanya 'kategori.index' jika pakai Route::resource
        // Cek php artisan route:list untuk memastikan namanya
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function delete($id)
    {
        // PERBAIKAN 5: Pastikan kolom yang dihapus sesuai dengan nama kolom di tabel (id_kategori)
        DB::table('kategori')->where('id_kategori', $id)->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
?>