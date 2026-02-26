@extends('layouts.admin.admin')

@section('content')

{{-- 1. PAGE HEADER (Tetap kita taruh di luar karena ini judul halaman global) --}}
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
    
    {{-- 2. PANGGIL COMPONENT (Gantikan div card, card-body, dll) --}}
    <x-admin-table title="Daftar Buku Perpustakaan">

        {{-- SLOT 1: Header Tabel (Judul Kolom) --}}
        <x-slot:thead>
            <tr>
                <th> No </th>
                <th> Kode </th>
                <th> Judul Buku </th>
                <th> Pengarang </th>
                <th> Kategori </th>
            </tr>
        </x-slot:thead>

        {{-- SLOT 2: Body Tabel (Isi Data) --}}
        @forelse($buku as $key => $item)
        <tr>
            <td> {{ $key + 1 }} </td>
            <td> 
                {{-- Badge Kode Buku --}}
                <label class="badge badge-gradient-info">{{ $item->kode }}</label> 
            </td>
            <td> {{ $item->judul }} </td>
            <td> {{ $item->pengarang }} </td>
            <td> 
                {{-- Cek Null Safety untuk Kategori --}}
                @if($item->nama_kategori)
                    {{ $item->nama_kategori }}
                @else
                    <span class="text-muted text-small">Tanpa Kategori</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Data buku masih kosong.</td>
        </tr>
        @endforelse

    </x-admin-table>
</div>

@endsection