@extends('layouts.admin.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title">Tambah Data Barang</h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Tambah Barang</h4>
                    <p class="card-description">Isi data barang dengan lengkap dan benar.</p>

                    <form class="forms-sample" action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" placeholder="Misal: Laptop" value="{{ old('nama') }}">
                            @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" placeholder="Misal: 5000000" value="{{ old('harga') }}" step="0.01">
                            @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Simpan Barang</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
