<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyCaseController extends Controller
{
    public function tabelBiasa()
    {
        return view('dashboard.admin.data_master.study-case.barang.tabel-biasa');
    }

    public function tabelDataTables()
    {
        return view('dashboard.admin.data_master.study-case.barang.tabel-datatables');
    }
    public function select2Kota()
    {
        return view('dashboard.admin.data_master.study-case.select2-kota');
    }
}