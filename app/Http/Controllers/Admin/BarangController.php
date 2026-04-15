<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;


class BarangController extends Controller
{

    public function index()
    {
        $barang = DB::table('barang')->orderBy('created_at', 'desc')->get();
        return view('dashboard.admin.data_master.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('dashboard.admin.data_master.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|numeric'
        ]);

        // Generate unique id_barang (BR + 6 digit number)
        $lastBarang = DB::table('barang')->orderBy('id_barang', 'desc')->first();

        if ($lastBarang) {
            // Extract number from last id and increment
            $lastNum = (int) substr($lastBarang->id_barang, 2);
            $newNum = str_pad($lastNum + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNum = '000001';
        }

        $id_barang = 'BR' . $newNum;

        DB::table('barang')->insert([
            'id_barang' => $id_barang,
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = DB::table('barang')->where('id_barang', $id)->first();
        if (!$barang) {
            abort(404);
        }
        return view('dashboard.admin.data_master.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|numeric'
        ]);

        //update ke database
        DB::table('barang')->where('id_barang', $id)->update([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');

    }

    public function destroy($id)
    {
        DB::table('barang')->where('id_barang', $id)->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
    public function cetak(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'x' => 'required|integer|min:1|max:5',
            'y' => 'required|integer|min:1|max:8',
        ]);

        $x = $request->x;
        $y = $request->y;

        // Hitung posisi awal (offset)
        $offset = ($y - 1) * 5 + ($x - 1);

        $barang = DB::table('barang')
            ->whereIn('id_barang', $request->items)
            ->get()
            ->toArray();

        // Buat array kosong untuk melompati label yang sudah terpakai
        $data = array_fill(0, $offset, null);
        $data = array_merge($data, $barang);

        // Bagi per 40 label (standard label TnJ 108 biasanya 1 halaman isi 40 atau sesuai ukuran)
        $pages = array_chunk($data, 40);

        $pdf = PDF::loadView('dashboard.admin.data_master.barang.pdf', compact('pages'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Tag_Harga_Barcode.pdf');
    }
}