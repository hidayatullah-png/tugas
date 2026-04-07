<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VendorPesananController extends Controller
{
    public function index()
    {
        // 1. Ambil data vendor yang sedang login (Gaya penulisan persis seperti contohmu)
        $vendor = DB::table('vendors')
            ->where('user_id', auth()->id())
            ->first();

        // 2. Ambil pesanan yang khusus 'Lunas'
        $pesanan = DB::table('pembayaran')
            ->join('detail_pembayaran', 'pembayaran.id', '=', 'detail_pembayaran.pembayaran_id')
            ->join('makanan', 'detail_pembayaran.makanan_id', '=', 'makanan.id')
            ->where('makanan.vendor_id', $vendor->id)
            ->where('pembayaran.status_bayar', 'Lunas') // FOKUS: Hanya tampilkan yang lunas!
            ->select(
                'pembayaran.id',
                'pembayaran.nomor_faktur',
                'pembayaran.nama_customer',
                'pembayaran.total_harga',
                'pembayaran.tanggal_transaksi',
                'makanan.nama_barang',
                'detail_pembayaran.jumlah',
                'detail_pembayaran.subtotal'
            )
            ->get();

        // 3. Lempar ke View (Sesuaikan dengan path foldermu yang terbaru)
        return view('dashboard.vendor.data_master.pesanan.index', compact('pesanan'));
    }
}