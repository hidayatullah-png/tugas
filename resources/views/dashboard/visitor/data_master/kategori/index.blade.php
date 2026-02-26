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
    <x-admin-table title="List Kategori Buku">

        {{-- SLOT 1: Header Tabel --}}
        <x-slot:thead>
            <tr>
                <th> # </th>
                <th> Nama Kategori </th>
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