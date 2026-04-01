<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // Halaman Kasir (jQuery)
    public function index()
    {
        return view('dashboard.admin.data_master.modul_ajax.kasir');
    }

    // Halaman Kasir (Axios)
    public function indexAxios()
    {
        return view('dashboard.admin.data_master.modul_ajax.kasir-axios');
    }

    // Mencari barang berdasarkan id_barang (Ketentuan B)
    public function search($id)
    {
        $barang = DB::table('barang')->where('id_barang', $id)->first();

        if ($barang) {
            return response()->json([
                'status' => 'success',
                'data' => $barang
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }

    // Menyimpan Transaksi (Ketentuan I)
    public function store(Request $request)
    {
        // Validasi dasar agar tidak error jika keranjang kosong
        if (!$request->items || count($request->items) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong!'], 400);
        }

        DB::beginTransaction();
        try {
            $idPenjualan = DB::table('penjualan')->insertGetId([
                'timestamp' => now(),
                'total' => $request->total_bayar
            ]);

            foreach ($request->items as $item) {
                DB::table('penjualan_detail')->insert([
                    'id_penjualan' => $idPenjualan,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['qty'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Transaksi Berhasil!']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Berikan pesan error yang lebih spesifik untuk debug
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}