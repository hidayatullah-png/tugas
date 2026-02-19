@extends('layouts.admin')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-book-open-page-variant"></i>
    </span> Data Buku
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title">Daftar Buku</h4>
            <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-icon-text">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Buku
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th> No </th>
                <th> Kode </th>
                <th> Judul Buku </th>
                <th> Pengarang </th>
                <th> Kategori </th>
                <th> Aksi </th>
              </tr>
            </thead>
            <tbody>
              @forelse($buku as $key => $item)
              <tr>
                <td> {{ $key + 1 }} </td>
                <td> 
                    <label class="badge badge-gradient-info">{{ $item->kode }}</label> 
                </td>
                <td> {{ $item->judul }} </td>
                <td> {{ $item->pengarang }} </td>
                <td> 
                    @if($item->nama_kategori)
                        {{ $item->nama_kategori }}
                    @else
                        <span class="text-muted">Tanpa Kategori</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('buku.edit', $item->idbuku) }}" class="btn btn-sm btn-inverse-warning btn-icon">
                        <i class="mdi mdi-pencil"></i>
                    </a>

                    <form action="{{ route('buku.destroy', $item->idbuku) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-inverse-danger btn-icon">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </form>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="6" class="text-center">Data buku masih kosong.</td>
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