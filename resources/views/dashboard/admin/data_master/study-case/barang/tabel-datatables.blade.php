@extends('layouts.admin.admin')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <style>
        #tabelDataTables tbody tr {
            cursor: pointer;
            transition: all 0.2s;
        }

        #tabelDataTables tbody tr:hover {
            background-color: rgba(182, 109, 255, 0.1);
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-large"></i>
            </span> Study Case: Tabel DataTables
        </h3>
    </div>

    <div class="row">
        {{-- BAGIAN FORM (KIRI) --}}
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Input Barang</h4>
                    <p class="card-description"> Tambahkan data ke DataTables </p>

                    <form id="formDataTables" class="forms-sample">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" id="dt_nama" class="form-control" placeholder="Contoh: Laptop" required>
                        </div>
                        <div class="form-group">
                            <label>Harga Barang</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-gradient-primary text-white">Rp</span>
                                </div>
                                <input type="number" id="dt_harga" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <button type="button" id="btnSubmitDT" class="btn btn-gradient-primary w-100 btn-icon-text"
                            onclick="prosesSimpanDT()">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> Simpan ke Tabel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- BAGIAN TABEL (KANAN) --}}
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Preview Data (Klik baris untuk Aksi)</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabelDataTables">
                            <thead>
                                <tr class="bg-light">
                                    <th> ID Barang </th>
                                    <th> Nama Barang </th>
                                    <th> Harga </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data akan muncul di sini via JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL AKSI --}}
    <div class="modal fade" id="modalAksiDT" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Data Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModalDT">
                        <div class="form-group">
                            <label>ID Barang (Read Only)</label>
                            <input type="text" id="modal_id_dt" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" id="modal_nama_dt" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Harga Barang</label>
                            <input type="number" id="modal_harga_dt" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnUbahDT" class="btn btn-info" onclick="prosesUbahDT()">Ubah Data</button>
                    <button type="button" id="btnHapusDT" class="btn btn-danger" onclick="prosesHapusDT()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        var counterIdDT = 1;
        var barisTerpilihDT = null;

        $(document).ready(function () {
            $('#tabelDataTables').DataTable({
                language: {
                    emptyTable: "Belum ada data. Silakan tambahkan melalui form.",
                    zeroRecords: "Data tidak ditemukan.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });

            $('#tabelDataTables tbody').on('click', 'tr', function () {
                if ($(this).find('.dataTables_empty').length > 0) return;

                barisTerpilihDT = this;
                let idVal = $(this).find('td:eq(0)').text().trim();
                let namaVal = $(this).find('td:eq(1)').text().trim();
                let hargaVal = $(this).find('td:eq(2)').text().replace('Rp ', '').replace(/\./g, '').trim();

                $('#modal_id_dt').val(idVal);
                $('#modal_nama_dt').val(namaVal);
                $('#modal_harga_dt').val(hargaVal);

                $('#modalAksiDT').modal('show');
            });
        });

        function prosesSimpanDT() {
            const nama = document.getElementById("dt_nama");
            const harga = document.getElementById("dt_harga");
            const btn = document.getElementById("btnSubmitDT");

            if (nama.value === "" || harga.value === "") {
                alert("Harap isi semua kolom!");
                return;
            }

            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';

            setTimeout(function () {
                const hargaFormatted = new Intl.NumberFormat('id-ID').format(harga.value);

                $('#tabelDataTables').DataTable().row.add([
                    `<label class="badge badge-outline-primary">ID-${counterIdDT}</label>`,
                    `<span class="text-dark font-weight-bold">${nama.value}</span>`,
                    `Rp ${hargaFormatted}`
                ]).draw(false);

                counterIdDT++;
                nama.value = "";
                harga.value = "";
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 500);
        }

        function prosesUbahDT() {
            const btn = document.getElementById("btnUbahDT");
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menyimpan...';

            setTimeout(function () {
                const idLama = $('#modal_id_dt').val();
                const namaBaru = $('#modal_nama_dt').val();
                const hargaBaru = parseInt($('#modal_harga_dt').val());
                const hargaFormatted = new Intl.NumberFormat('id-ID').format(hargaBaru);

                $('#tabelDataTables').DataTable().row(barisTerpilihDT).data([
                    `<label class="badge badge-outline-primary">${idLama}</label>`,
                    `<span class="text-dark font-weight-bold">${namaBaru}</span>`,
                    `Rp ${hargaFormatted}`
                ]).draw(false);

                btn.disabled = false;
                btn.innerHTML = originalText;
                $('#modalAksiDT').modal('hide');
            }, 500);
        }

        function prosesHapusDT() {
            if (!confirm('Yakin ingin menghapus data ini?')) return;

            const btn = document.getElementById("btnHapusDT");
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menghapus...';

            setTimeout(function () {
                $('#tabelDataTables').DataTable().row(barisTerpilihDT).remove().draw(false);

                btn.disabled = false;
                btn.innerHTML = originalText;
                $('#modalAksiDT').modal('hide');
            }, 500);
        }
    </script>
@endsection