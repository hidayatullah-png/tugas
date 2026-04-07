@extends('layouts.vendor.vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-plus"></i>
        </span> Tambah Menu Baru
    </h3>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Input Makanan/Minuman</h4>
                <p class="card-description"> Masukkan detail menu yang ingin Anda jual. </p>
                
                <form class="forms-sample" action="{{ route('vendor.makanan.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama_barang">Nama Makanan / Minuman</label>
                        <input type="text" class="form-control" name="nama_barang" placeholder="Contoh: Nasi Goreng Gila" required>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" placeholder="Contoh: 15000" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok Awal</label>
                        <input type="number" class="form-control" name="stok" placeholder="Contoh: 50" required>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary me-2">Simpan Menu</button>
                    <a href="{{ route('vendor.makanan.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection