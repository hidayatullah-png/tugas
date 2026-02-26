<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use app\models\barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = barang::all();
        return view('dashboard.admin.data_master.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('dashboard.admin.data_master.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);

        Barang::create($request->all());

        return redirect()->route('admin.barang.index')->with('success','Barang berhasil ditambahkan.');
    }

    public function edit(int $id)
    { 
        $barang = Barang::findOrFail($id);
        return view('dashboard.admin.data_master.barang.edit', compact('barang'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('admin.barang.index')->with('success','Barang berhasil diperbarui.');
    }
    public function destroy(int $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success','Barang berhasil dihapus.');
    }
}
