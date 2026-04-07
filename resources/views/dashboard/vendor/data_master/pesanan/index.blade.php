@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-receipt"></i>
            </span> Pesanan Masuk (Siap Masak)
        </h3>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Pesanan Lunas</h4>
                    <p class="card-description">Semua pesanan di bawah ini sudah terbayar dan siap untuk disiapkan.</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th> No. Faktur </th>
                                    <th> Nama Customer </th>
                                    <th> Nama Barang </th>
                                    <th> Jumlah </th>
                                    <th> Tanggal Transaksi </th>
                                    <th> Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanan as $p)
                                    <tr>
                                        <td><label class="badge badge-gradient-info">{{ $p->nomor_faktur }}</label></td>
                                        <td> {{ $p->nama_customer }} </td>
                                        <td> {{ $p->nama_barang }} </td>
                                        <td> {{ $p->jumlah }} porsi </td>
                                        <td> {{ $p->tanggal_transaksi }} </td>
                                        <td>
                                            <span class="badge badge-gradient-success">
                                                <i class="mdi mdi-check-all"></i> Lunas
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="mdi mdi-food-off mdi-48px mb-2 d-block"></i>
                                            Belum ada pesanan lunas yang masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection