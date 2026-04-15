@extends('layouts.guest.guest') @section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-check-circle-outline"></i>
            </span> Pembayaran Berhasil
        </h3>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4 class="card-title">Terima kasih, {{ $pembayaran->nama_customer }}!</h4>
                    <p class="mb-4">Pesananmu sudah terverifikasi. Silakan tunjukkan QR Code ini ke vendor.</p>

                    <div class="bg-light p-4 d-inline-block mb-4" style="border-radius: 15px;">
                        <img src="{{ asset('qrcodes/' . $filename) }}" alt="QR Code Pesanan"
                            class="img-fluid border shadow-sm p-2 bg-white"
                            style="max-width: 250px; border-radius: 10px;" />
                        <p class="mt-3 mb-0"><strong>ID: {{ $pembayaran->nomor_faktur }}</strong></p>
                    </div>

                    <div class="mt-2">
                        <p class="text-muted">Total Bayar: <strong>Rp
                                {{ number_format($pembayaran->total_harga, 0, ',', '.') }}</strong></p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('guest.index') }}" class="btn btn-gradient-primary">
                            Kembali ke Menu Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection