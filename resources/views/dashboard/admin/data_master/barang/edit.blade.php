@extends('layouts.admin.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title">Update Data Barang</h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Form Update Barang</h4>
                    <p class="card-description">Perbarui data barang dengan benar.</p>

                    <form id="formBarang" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama', $barang->nama) }}" required>

                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" value="{{ old('harga', $barang->harga) }}" step="0.01" required>

                            @error('harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </form>

                    <div class="mt-3">

                        <button id="btnUpdate" type="button" class="btn btn-gradient-warning me-2">

                            <span id="btnText">Update Barang</span>

                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>

                        </button>

                        <a href="{{ route('barang.index') }}" class="btn btn-light">
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

            const form = document.getElementById("formBarang");
            const btn = document.getElementById("btnUpdate");
            const text = document.getElementById("btnText");
            const spinner = document.getElementById("btnSpinner");

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

        });

    </script>

@endsection