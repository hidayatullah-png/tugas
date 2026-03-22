@extends('layouts.admin.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title">Update Data Kategori</h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Form Update Kategori</h4>
                    <p class="card-description">Perbarui nama kategori dengan benar.</p>

                    <form id="formKategori" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text"
                                   class="form-control @error('nama_kategori') is-invalid @enderror"
                                   id="nama_kategori"
                                   name="nama_kategori"
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                   required>

                            @error('nama_kategori')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </form>

                    <div class="mt-3">
                        <button id="btnUpdate" type="button" class="btn btn-gradient-warning me-2">
                            <span id="btnText">Update Kategori</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                        <a href="{{ route('kategori.index') }}" class="btn btn-light">
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

    const form = document.getElementById("formKategori");
    const btn = document.getElementById("btnUpdate");
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