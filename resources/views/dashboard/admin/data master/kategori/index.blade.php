@extends('layouts.admin.admin') 

@section('content')

{{-- 1. PAGE HEADER --}}
<div class="page-header">
    <h3 class="page-title"> Master Data Kategori </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
        </ol>
    </nav>
</div>

<div class="row">
    
    {{-- 2. PANGGIL COMPONENT --}}
    <x-admin-table title="List Kategori Buku" 
                   button-label="Tambah Kategori" 
                   button-link="{{ route('kategori.create') }}">

        {{-- SLOT 1: Header Tabel --}}
        <x-slot:thead>
            <tr>
                <th> # </th>
                <th> Nama Kategori </th>
                <th class="text-center"> Aksi </th>
            </tr>
        </x-slot:thead>

        {{-- SLOT 2: Body Tabel (Isi Data) --}}
        @forelse($kategori as $item)
        <tr>
            <td class="py-1">
                {{ $loop->iteration }}
            </td>
            <td> 
                {{ $item->nama_kategori }} 
            </td>
            <td class="text-center">
                
                {{-- Tombol Edit --}}
                <a href="{{ route('kategori.edit', $item->idkategori) }}" class="btn btn-sm btn-inverse-warning btn-icon d-inline-flex align-items-center justify-content-center" title="Edit">
                    <i class="mdi mdi-pencil"></i>
                </a>

                {{-- Tombol Hapus --}}
                <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" 
                      action="{{ route('kategori.destroy', $item->idkategori) }}" 
                      method="POST" 
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </form>

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center text-muted">
                Data kategori masih kosong.
            </td>
        </tr>
        @endforelse

    </x-admin-table>

</div>
@endsection