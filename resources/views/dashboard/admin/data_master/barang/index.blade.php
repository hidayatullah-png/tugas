@extends('layouts.admin.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title">Data Barang</h3>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">

                <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                    Tambah Barang
                </a>

                <div class="d-flex align-items-center gap-2">

                    <label>Posisi X:</label>
                    <input type="number" name="x" min="1" max="5" required form="form-cetak"
                        class="form-control form-control-sm" style="width:80px;">

                    <label>Posisi Y:</label>
                    <input type="number" name="y" min="1" max="8" required form="form-cetak"
                        class="form-control form-control-sm" style="width:80px;">

                    <button type="button" id="btnCetak" class="btn btn-success btn-sm">

                        <span id="btnText">Cetak</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>

                    </button>

                </div>
            </div>


            <form id="form-cetak" action="{{ route('barang.cetak') }}" method="POST">
                @csrf
            </form>


            <div class="table-responsive">
                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th width="40px">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($barang as $item)

                            <tr>

                                <td>
                                    <input type="checkbox" name="items[]" value="{{ $item->id_barang }}" form="form-cetak">
                                </td>

                                <td>{{ $item->id_barang }}</td>

                                <td>{{ $item->nama }}</td>

                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>

                                <td>

                                    <a href="{{ route('barang.edit', $item->id_barang) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST"
                                        style="display:inline;">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus?')">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>
    </div>

@endsection



@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const form = document.getElementById("form-cetak");
            const btn = document.getElementById("btnCetak");
            const text = document.getElementById("btnText");
            const spinner = document.getElementById("btnSpinner");
            const checkAll = document.getElementById("checkAll");

            if (checkAll) {
                checkAll.addEventListener('click', function () {
                    let checkboxes = document.querySelectorAll('input[name="items[]"]');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            if (form && btn && text && spinner) {
                btn.addEventListener("click", function () {

                    const x = document.querySelector('input[name="x"]');
                    const y = document.querySelector('input[name="y"]');
                    const items = document.querySelectorAll('input[name="items[]"]:checked');

                    // VALIDASI
                    if (!x.value || !y.value) {
                        alert("Posisi X dan Y wajib diisi!");
                        return;
                    }

                    if (items.length === 0) {
                        alert("Pilih minimal 1 barang!");
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