@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-group"></i>
            </span> Data Customer
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="card-title">Daftar Pelanggan Terdaftar</h4>
                        <div>
                            <a href="{{ route('vendor.customer.create1') }}" class="btn btn-sm btn-outline-primary">Tambah
                                (BLOB)</a>
                            <a href="{{ route('vendor.customer.create2') }}" class="btn btn-sm btn-primary">Tambah
                                (Path)</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th> Foto </th>
                                    <th> Nama </th>
                                    <th> Alamat / Wilayah </th>
                                    <th> Metode Simpan </th>
                                    <th> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $c)
                                    <tr>
                                        <td class="py-1">
                                            @if($c->foto_blob)
                                                <img src="data:image/png;base64,{{ base64_encode($c->foto_blob) }}"
                                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;"
                                                    alt="blob">
                                            @elseif($c->foto_path)
                                                <img src="{{ asset('storage/customers/' . $c->foto_path) }}"
                                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;"
                                                    alt="path">
                                            @else
                                                <label class="badge badge-secondary">No Photo</label>
                                            @endif
                                        </td>
                                        <td class="font-weight-bold"> {{ $c->nama_customer }} </td>
                                        <td class="small">
                                            {{ $c->alamat }} <br>
                                            <span class="text-muted">{{ $c->kota }}, {{ $c->provinsi }}</span>
                                        </td>
                                        <td>
                                            @if($c->foto_blob)
                                                <label class="badge badge-info">Database (BLOB)</label>
                                            @else
                                                <label class="badge badge-success">Folder (Path)</label>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('vendor.customer.destroy', $c->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-inverse-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
                                                    <i class="mdi mdi-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 1. Fungsi Utama Loader
        function showLoading() {
            Swal.fire({
                title: 'Memuat data...',
                html: 'Tunggu sebentar ya...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // 2. TRIGGER: Munculkan loader saat tombol Tambah diklik
        // Kita targetkan tombol dengan class btn-sm (tombol tambah)
        $('.btn-sm').on('click', function (e) {
            // Cek apakah itu tombol Tambah (bukan tombol aksi mata/eye)
            if ($(this).attr('href')) {
                showLoading();
            }
        });

        // 3. CLOSE: Tutup loader saat halaman SELESAI dimuat sempurna
        $(window).on('load', function () {
            // Kasih sedikit delay biar transisinya halus (opsional)
            setTimeout(() => {
                Swal.close();
            }, 500);
        });
    </script>
@endsection