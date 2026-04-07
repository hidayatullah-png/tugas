<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class GuestController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $vendors = DB::table('vendors')->get();

        $makanan = DB::table('makanan')
            ->join('vendors', 'makanan.vendor_id', '=', 'vendors.id')
            ->select('makanan.*', 'vendors.nama_vendor')
            ->where('makanan.stok', '>', 0)
            ->get();

        return view('dashboard.index', compact('vendors', 'makanan'));
    }

    public function getMenu($vendor_id)
    {
        $menu = DB::table('makanan')
            ->where('vendor_id', $vendor_id)
            ->where('stok', '>', 0)
            ->get();

        return response()->json($menu);
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Generate Nama Guest & Order ID bergaya simpel (mirip contohmu)
            $count = DB::table('pembayaran')->count() + 1;
            $guestName = $request->nama_pembeli ?: 'Guest_' . str_pad($count, 7, '0', STR_PAD_LEFT);
            $orderId = 'INV-' . time();

            // 2. Simpan Pembayaran
            $pembayaranId = DB::table('pembayaran')->insertGetId([
                'nomor_faktur' => $orderId,
                'nama_customer' => $guestName,
                'total_harga' => $request->total_harga,
                'status_bayar' => 'Belum Lunas',
                'tanggal_transaksi' => now()
            ]);

            // 3. Simpan Detail Pembayaran (Gaya json_decode seperti contohmu)
            $items = json_decode(json_encode($request->items), true);
            foreach ($items as $item) {
                DB::table('detail_pembayaran')->insert([
                    'pembayaran_id' => $pembayaranId,
                    'makanan_id' => $item['id'],
                    'jumlah' => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);
                // Pemotongan stok TIDAK di sini agar aman kalau pembeli batal bayar
            }

            // 4. Parameter Midtrans yang jauh lebih simpel
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $request->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $guestName,
                ],
            ];

            // 5. Get Token & Update ke DB
            $snapToken = Snap::getSnapToken($params);
            DB::table('pembayaran')->where('id', $pembayaranId)->update(['snap_token' => $snapToken]);

            DB::commit();

            // Return response mirip contoh
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'order_id' => $pembayaranId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal simpan: ' . $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            $notif = new Notification();
            $transaction = $notif->transaction_status;
            $order_id = $notif->order_id;

            $pembayaran = DB::table('pembayaran')->where('nomor_faktur', $order_id)->first();

            if (!$pembayaran) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            // Jika Midtrans bilang Lunas (Berhasil bayar)
            if ($transaction == 'capture' || $transaction == 'settlement') {

                // Cek agar stok tidak dipotong dua kali kalau Midtrans ngirim notif dobel
                if ($pembayaran->status_bayar == 'Lunas') {
                    return response()->json(['status' => 'OK']);
                }

                // Ubah status Lunas
                DB::table('pembayaran')->where('nomor_faktur', $order_id)->update(['status_bayar' => 'Lunas']);

                // Pemotongan stok otomatis saat lunas
                $details = DB::table('detail_pembayaran')->where('pembayaran_id', $pembayaran->id)->get();
                foreach ($details as $item) {
                    DB::table('makanan')
                        ->where('id', $item->makanan_id)
                        ->decrement('stok', $item->jumlah);
                }

            } elseif ($transaction == 'pending') {
                DB::table('pembayaran')->where('nomor_faktur', $order_id)->update(['status_bayar' => 'Belum Lunas']);
            } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                DB::table('pembayaran')->where('nomor_faktur', $order_id)->update(['status_bayar' => 'Gagal']);
            }

            return response()->json(['status' => 'OK']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function finish(Request $request)
    {
        // Kamu bisa kirim data order_id kalau mau nampilin status di view
        return view('dashboard.guest.finish');
    }
}