<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <--- WAJIB TAMBAHKAN INI

class VendorMakananController extends Controller
{
    // Fungsi pembantu untuk mengambil ID Vendor dari user yang login
    private function getVendorId()
    {
        $vendor = DB::table('vendors')->where('user_id', Auth::id())->first();
        return $vendor ? $vendor->id : null;
    }

    public function index()
    {
        $vendorId = $this->getVendorId();

        $makanan = DB::table('makanan')
            ->where('vendor_id', $vendorId)
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.vendor.index', compact('makanan'));
    }

    public function create()
    {
        return view('dashboard.vendor.data_master.create');
    }

    public function store(Request $request)
    {
        $vendorId = $this->getVendorId();

        if (!$vendorId) {
            return redirect()->back()->with('error', 'Profil Vendor tidak ditemukan.');
        }

        $request->validate([
            'nama_barang' => 'required|max:100',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $kodeBarang = 'MKN-' . strtoupper(Str::random(6));

        // DISESUAIKAN: Menghapus created_at dan updated_at agar tidak error
        DB::table('makanan')->insert([
            'vendor_id' => $vendorId,
            'kode_barang' => $kodeBarang,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('vendor.makanan.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $vendorId = $this->getVendorId();

        $request->validate([
            'nama_barang' => 'required|max:100',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        // DISESUAIKAN: Menghapus updated_at
        DB::table('makanan')
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->update([
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga,
                'stok' => $request->stok,
            ]);

        return redirect()->route('vendor.makanan.index')->with('success', 'Menu berhasil diupdate!');
    }

    public function edit($id)
    {
        $vendorId = $this->getVendorId();

        $makanan = DB::table('makanan')
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->first();

        if (!$makanan) {
            abort(403, 'Akses ilegal!');
        }

        return view('dashboard.vendor.data_master.edit', compact('makanan'));
    }
    public function destroy($id)
    {
        $vendorId = $this->getVendorId();

        DB::table('makanan')
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->delete();

        return redirect()->route('vendor.makanan.index')->with('success', 'Menu berhasil dihapus!');
    }
}