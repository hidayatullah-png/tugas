@extends('layouts.admin.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title">Update Data Buku</h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Form Update Buku</h4>
                    <p class="card-description">Perbarui data buku dengan benar.</p>

                    <form id="formBuku" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Kode --}}
                        <div class="form-group">
                            <label>Kode Buku</label>
                            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                                value="{{ old('kode', $buku->kode) }}" required>

                            @error('kode')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Judul --}}
                        <div class="form-group">
                            <label>Judul Buku</label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $buku->judul) }}" required>

                            @error('judul')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Pengarang --}}
                        <div class="form-group">
                            <label>Pengarang</label>
                            <input type="text" name="pengarang"
                                class="form-control @error('pengarang') is-invalid @enderror"
                                value="{{ old('pengarang', $buku->pengarang) }}" required>

                            @error('pengarang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="idkategori" class="form-control @error('idkategori') is-invalid @enderror"
                                required>

                                <option value="">-- Pilih Kategori --</option>

                                @foreach($kategori as $k)
                                    <option value="{{ $k->idkategori }}" {{ old('idkategori', $buku->idkategori) == $k->idkategori ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach

                            </select>

                            @error('idkategori')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </form>

                    {{-- BUTTON --}}
                    <div class="mt-3">
                        <button id="btnSubmit" type="button" class="btn btn-gradient-warning me-2">
                            <span id="btnText">Update Buku</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                        <a href="{{ route('buku.index') }}" class="btn btn-light">
                            Batal
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const form = document.getElementById("formBuku");
            const btn = document.getElementById("btnSubmit");
            const text = document.getElementById("btnText");
            const spinner = document.getElementById("btnSpinner");

            if (form && btn && text && spinner) {
                btn.addEventListener("click", function () {

                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    btn.disabled = true;
                    text.classList.add("d-none");
                    spinner.classList.remove("d-none");

                    form.submit();
                });
            }

        });
    </script>
@endsection