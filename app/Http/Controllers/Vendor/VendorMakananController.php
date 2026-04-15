<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        // 1. Validasi dengan pesan kustom
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // 2. Handle Upload Gambar
            $nama_file = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $nama_file = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/menu', $nama_file);
            }

            // 3. Generate Kode Barang Otomatis (Solusi Error 1364)
            $kode_barang = 'MKN-' . strtoupper(Str::random(5));

            // 4. Simpan ke database
            DB::table('makanan')->insert([
                'vendor_id' => $this->getVendorId(),
                'kode_barang' => $kode_barang, // Sekarang sudah diisi!
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'foto' => $nama_file,
            ]);

            return redirect()->route('vendor.makanan.index')->with('success', 'Menu Berhasil Ditambahkan!');

        } catch (\Exception $e) {
            // Jika ada error database lainnya, kirim pesan error ke session
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $vendorId = $this->getVendorId();

        $request->validate([
            'nama_barang' => 'required|max:100',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $makanan = DB::table('makanan')->where('id', $id)->where('vendor_id', $vendorId)->first();
        $nama_file = $makanan->foto; // Ambil nama file lama sebagai default

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($makanan->foto) {
                Storage::delete('public/menu/' . $makanan->foto);
            }
            // Upload foto baru
            $file = $request->file('foto');
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/menu', $nama_file);
        }

        DB::table('makanan')
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->update([
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'foto' => $nama_file
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
        $makanan = DB::table('makanan')->where('id', $id)->where('vendor_id', $vendorId)->first();

        if ($makanan) {
            // Hapus file fisik dari storage
            if ($makanan->foto) {
                Storage::delete('public/menu/' . $makanan->foto);
            }

            DB::table('makanan')->where('id', $id)->delete();
            return redirect()->route('vendor.makanan.index')->with('success', 'Menu berhasil dihapus!');
        }

        return redirect()->route('vendor.makanan.index')->with('error', 'Gagal menghapus menu.');
    }
}