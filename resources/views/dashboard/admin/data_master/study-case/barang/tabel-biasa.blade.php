@extends('layouts.admin.admin') {{-- Sesuaikan dengan nama layout master kamu --}}

@section('content')
    <style>
        /* Custom style untuk baris tabel agar terlihat interaktif */
        #tabelBiasa tbody tr {
            cursor: pointer;
            transition: all 0.2s;
        }

        #tabelBiasa tbody tr:hover {
            background-color: rgba(182, 109, 255, 0.1);
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-flask"></i>
            </span> Study Case: Simulasi Inventaris
        </h3>
    </div>

    <div class="row">
        {{-- BAGIAN FORM (KIRI) --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Input Barang</h4>
                    <p class="card-description"> Tambahkan data ke tabel sementara </p>

                    <form id="formSimulasi" class="forms-sample">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" id="nama_barang" class="form-control" placeholder="Contoh: Laptop" required>
                        </div>
                        <div class="form-group">
                            <label>Harga Barang</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-gradient-primary text-white">Rp</span>
                                </div>
                                <input type="number" id="harga_barang" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <button type="button" id="btnSubmit" class="btn btn-gradient-primary w-100 btn-icon-text"
                            onclick="prosesSimpan()">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> Simpan ke Tabel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- BAGIAN TABEL (KANAN) --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Preview Data (Klik baris untuk Aksi)</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabelBiasa">
                            <thead>
                                <tr class="bg-light">
                                    <th> ID Barang </th>
                                    <th> Nama Barang </th>
                                    <th> Harga </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL AKSI --}}
    <div class="modal fade" id="modalAksi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Data Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModal">
                        <div class="form-group">
                            <label>ID Barang (Read Only)</label>
                            <input type="text" id="modal_id" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" id="modal_nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Harga Barang</label>
                            <input type="number" id="modal_harga" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnUbah" class="btn btn-info" onclick="prosesUbah()">Ubah Data</button>
                    <button type="button" id="btnHapus" class="btn btn-danger" onclick="prosesHapus()">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let counterId = 1;
        let barisTerpilih = null;

        function prosesSimpan() {
            const nama = document.getElementById("nama_barang");
            const harga = document.getElementById("harga_barang");
            const btn = document.getElementById("btnSubmit");

            if (nama.value === "" || harga.value === "") {
                alert("Harap isi semua kolom!");
                return;
            }

            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';

            setTimeout(() => {
                const tbody = document.querySelector("#tabelBiasa tbody");

                const hargaFormatted = new Intl.NumberFormat('id-ID').format(harga.value);

                const newRow = `
                    <tr onclick="bukaModal(this)">
                        <td class="kolom-id"><label class="badge badge-outline-primary">ID-${counterId}</label></td>
                        <td class="kolom-nama text-dark font-weight-bold">${nama.value}</td>
                        <td class="kolom-harga" data-harga="${harga.value}">
                            Rp ${hargaFormatted}
                        </td>
                    </tr>`;

                tbody.insertAdjacentHTML('beforeend', newRow);
                counterId++;

                nama.value = "";
                harga.value = "";
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 500);
        }

        function bukaModal(row) {
            barisTerpilih = row;
            const idVal = row.querySelector('.kolom-id').innerText;
            const namaVal = row.querySelector('.kolom-nama').innerText;
            const hargaVal = row.querySelector('.kolom-harga').getAttribute('data-harga');

            document.getElementById('modal_id').value = idVal;
            document.getElementById('modal_nama').value = namaVal;
            document.getElementById('modal_harga').value = hargaVal;

            $('#modalAksi').modal('show');
        }

        function prosesUbah() {
            const namaBaru = document.getElementById("modal_nama").value;
            const hargaBaru = document.getElementById("modal_harga").value;

            barisTerpilih.querySelector('.kolom-nama').innerText = namaBaru;
            barisTerpilih.querySelector('.kolom-harga').innerText = "Rp " + parseInt(hargaBaru).toLocaleString();
            barisTerpilih.querySelector('.kolom-harga').setAttribute('data-harga', hargaBaru);

            $('#modalAksi').modal('hide');
        }

        function prosesHapus() {
            if (confirm('Yakin ingin menghapus data ini?')) {
                barisTerpilih.remove();
                $('#modalAksi').modal('hide');
            }
        }
    </script>
@endsection