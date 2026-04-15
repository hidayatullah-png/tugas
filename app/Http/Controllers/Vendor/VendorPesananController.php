<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use LaravelQRCode\Facades\QRCode;

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
    /**
     * STUDI KASUS 2: Halaman Sukses Payment + QR Code
     * Method ini dipanggil saat Midtrans redirect kembali ke web kita
     */
    public function selesai(Request $request)
    {
        // 1. Ambil order_id dari URL (dikirim Midtrans)
        $orderId = $request->order_id;

        // 2. Cari data pembayaran berdasarkan nomor faktur atau ID
        $pembayaran = DB::table('pembayaran')
            ->where('nomor_faktur', $orderId) // Asumsi order_id Midtrans = nomor_faktur
            ->first();

        if (!$pembayaran) {
            return redirect('/')->with('error', 'Data pesanan tidak ditemukan.');
        }

        // 3. GENERATE QR CODE - simpan ke file dulu
        $filename = 'qrcode_' . $orderId . '.png';
        $path = public_path('qrcodes/' . $filename);

        // Buat folder jika belum ada
        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0755, true);
        }

        QRCode::text($orderId)->setSize(250)->setOutfile($path)->png();

        // 4. Kirim ke view guest
    return view('dashboard.guest.success', compact('pembayaran', 'filename'));
    }
}