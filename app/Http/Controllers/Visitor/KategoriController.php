<?php

namespace App\Http\Controllers\Visitor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        // PERBAIKAN 1: Nama variabel disamakan dengan compact
        $kategori = DB::table('kategori')->get();

        // Pastikan view foldernya benar (resources/views/admin/kategori/index.blade.php)
        return view('dashboard.visitor.data_master.kategori.index', compact('kategori'));
    }
}
?>