@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-food-apple"></i>
            </span> Manajemen Menu Makanan
        </h3>
        <nav aria-label="breadcrumb">
            <a href="{{ route('vendor.makanan.create') }}" class="btn btn-gradient-primary btn-icon-text">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Menu
            </a>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Dagangan Anda</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th> Kode </th>
                                    <th> Nama Makanan </th>
                                    <th> Harga </th>
                                    <th> Stok </th>
                                    <th> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($makanan as $m)
                                    <tr>
                                        <td>
                                            <label class="badge badge-gradient-info">{{ $m->kode_barang }}</label>
                                        </td>
                                        <td> {{ $m->nama_barang }} </td>
                                        <td> Rp {{ number_format($m->harga, 0, ',', '.') }} </td>
                                        <td>
                                            @if($m->stok <= 5)
                                                <span class="text-danger font-weight-bold">{{ $m->stok }} (Hampir Habis)</span>
                                            @else
                                                {{ $m->stok }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vendor.makanan.edit', $m->id) }}"
                                                class="btn btn-inverse-warning btn-sm">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('vendor.makanan.destroy', $m->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-inverse-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Anda belum menambah menu makanan.
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