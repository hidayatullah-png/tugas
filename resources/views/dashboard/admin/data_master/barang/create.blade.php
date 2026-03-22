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

                    <form id="formBarang" action="{{ route('barang.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" placeholder="Misal: Laptop" value="{{ old('nama') }}" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" placeholder="Misal: 5000000" value="{{ old('harga') }}" step="0.01" required>
                            @error('harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </form>

                    <div class="mt-3">
                        <button id="btnSubmit" type="button" class="btn btn-gradient-primary me-2">
                            <span id="btnText">Simpan Barang</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                        <a href="{{ route('barang.index') }}" class="btn btn-light">Batal</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const form = document.getElementById("formBarang");
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