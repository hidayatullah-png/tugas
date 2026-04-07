<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function index()
    {
        // Ambil data barang/makanan yang stoknya ada
        $makanan = DB::table('barang')->where('stok', '>', 0)->get();
        
        // Kirim data ke view
        return view('dashboard.visitor.index', compact('makanan'));
    }

    public function getDetail($id)
    {
        $barang = DB::table('barang')->where('id', $id)->first();
        return response()->json($barang);
    }

    // Nanti di sini kita tambah fungsi checkout() untuk Midtrans
}