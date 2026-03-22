<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = DB::table('kategori')->get();

        return view('dashboard.admin.data_master.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('dashboard.admin.data_master.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori|max:100',
        ]);

        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
            'deleted_at' => null,
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = DB::table('kategori')->where('idkategori', $id)->first();

        if (!$kategori) {
            abort(404);
        }

        return view('dashboard.admin.data_master.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|max:100|unique:kategori,nama_kategori,' . $id . ',idkategori',
        ]);

        DB::table('kategori')->where('idkategori', $id)->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    // lebih baik pakai destroy (standard Laravel)
    public function destroy($id)
    {
        DB::table('kategori')->where('idkategori', $id)->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}

?>