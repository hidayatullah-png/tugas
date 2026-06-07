@extends('layouts.admin.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map-marker-radius"></i>
            </span> Data Kunjungan Toko
        </h3>
        <a href="{{ route('kunjungan.tambah') }}" class="btn btn-gradient-primary btn-sm">
            <i class="mdi mdi-plus"></i> Tambah Toko
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">List Toko</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Nama Toko</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Accuracy (m)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($toko as $t)
                            <tr>
                                <td><span class="badge bg-dark">{{ $t->barcode }}</span></td>
                                <td>{{ $t->nama_toko }}</td>
                                <td><span class="badge" style="background-color: #3ea2c7;">{{ $t->latitude }}</span></td>
                                <td><span class="badge" style="background-color: #3ea2c7;">{{ $t->longitude }}</span></td>
                                <td><span class="badge text-dark" style="background-color: #ffdd57;">{{ $t->accuracy }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm text-white" style="background-color: #9c27b0;">Cetak QR
                                        Code</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title"><i class="mdi mdi-history"></i> Riwayat Kunjungan</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Sales ID</th>
                            <th>Barcode</th>
                            <th>Toko</th>
                            <th>Jarak</th>
                            <th>Threshold</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $r)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($r->waktu_kunjungan)->format('d/m/Y H:i') }}</td>
                                <td>Sales-{{ $r->user_id }}</td>
                                <td><span class="badge bg-dark">{{ $r->barcode }}</span></td>
                                <td>{{ $r->nama_toko }}</td>
                                <td>{{ $r->jarak_aktual }} m</td>
                                <td>{{ $r->threshold_efektif }} m</td>
                                <td>
                                    @if($r->status == 'DITERIMA')
                                        <span class="badge bg-success">DITERIMA</span>
                                    @else
                                        <span class="badge bg-danger">DITOLAK</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection