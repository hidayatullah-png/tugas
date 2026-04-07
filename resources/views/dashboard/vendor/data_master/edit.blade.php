@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-warning text-white me-2">
                <i class="mdi mdi-pencil"></i>
            </span> Edit Menu Makanan
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Update Makanan/Minuman</h4>
                    <p class="card-description"> Ubah detail menu untuk <strong>{{ $makanan->nama_barang }}</strong> </p>

                    <form class="forms-sample" action="{{ route('vendor.makanan.update', $makanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" class="form-control" value="{{ $makanan->kode_barang }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nama_barang">Nama Makanan / Minuman</label>
                            <input type="text" class="form-control" name="nama_barang" value="{{ $makanan->nama_barang }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga (Rp)</label>
                            <input type="number" class="form-control" name="harga" value="{{ $makanan->harga }}" required>
                        </div>

                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" name="stok" value="{{ $makanan->stok }}" required>
                        </div>

                        <button type="submit" class="btn btn-gradient-warning me-2">Update Menu</button>
                        <a href="{{ route('vendor.makanan.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection