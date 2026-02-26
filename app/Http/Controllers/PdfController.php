<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

class PdfController extends Controller
{
    public function landscape()
    {
        $pdf = PDF::loadView('pdf.landscape')->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_Kegiatan.pdf');
    }

    public function potrait()
    {
        $pdf = PDF::loadView('pdf.potrait')->setPaper('a4', 'portrait');

        return $pdf->stream('Undangan_Fakultas.pdf');
    }
}