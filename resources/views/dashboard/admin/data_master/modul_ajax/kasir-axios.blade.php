@extends('layouts.admin.admin')

@section('content')
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Input Barang</h4>
                    <div class="form-group">
                        <label>Kode Barang (Enter)</label>
                        <input type="text" id="input_kode" class="form-control border-primary"
                            placeholder="Contoh: 26040101">
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" id="nama_barang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" id="harga_barang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" id="qty" class="form-control" value="1" min="1">
                    </div>
                    <button id="btn_tambah" class="btn btn-primary w-100" disabled onclick="tambahKeKeranjang()">
                        <i class="mdi mdi-plus"></i> Tambahkan
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Belanja</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel_item"></tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-right">
                        <h3>Total: Rp <span id="total_display">0</span></h3>
                        <button class="btn btn-success btn-lg mt-2" onclick="bayar()">
                            <i class="mdi mdi-cash-multiple"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let keranjang = [];
        let tempBarang = null;

        // Ketentuan B: Search on Enter
        document.getElementById('input_kode').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const kode = this.value;
                axios.get(`/admin/barang/search/${kode}`)
                    .then(res => {
                        const b = res.data.data;
                        tempBarang = b;
                        document.getElementById('nama_barang').value = b.nama;
                        document.getElementById('harga_barang').value = b.harga;
                        document.getElementById('btn_tambah').disabled = false; // Ketentuan D
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Barang tidak ditemukan!', 'error');
                        resetInput();
                    });
            }
        });

        // Ketentuan E & F: Tambah ke tabel / Update Qty
        function tambahKeKeranjang() {
            const qtyInput = parseInt(document.getElementById('qty').value);
            if (qtyInput <= 0) return;

            const index = keranjang.findIndex(item => item.id_barang === tempBarang.id_barang);

            if (index !== -1) {
                keranjang[index].qty += qtyInput;
                keranjang[index].subtotal = keranjang[index].qty * keranjang[index].harga;
            } else {
                keranjang.push({
                    id_barang: tempBarang.id_barang,
                    nama: tempBarang.nama,
                    harga: tempBarang.harga,
                    qty: qtyInput,
                    subtotal: tempBarang.harga * qtyInput
                });
            }
            renderTabel();
            resetInput();
        }

        // Ketentuan G & H: Update Qty di tabel & Hitung Total
        function updateTabelQty(index, newQty) {
            if (newQty < 1) return renderTabel();
            keranjang[index].qty = parseInt(newQty);
            keranjang[index].subtotal = keranjang[index].qty * keranjang[index].harga;
            renderTabel();
        }

        function hapusItem(index) {
            keranjang.splice(index, 1);
            renderTabel();
        }

        function renderTabel() {
            let html = '';
            let total = 0;
            keranjang.forEach((item, index) => {
                total += item.subtotal;
                html += `<tr>
                    <td>${item.nama}</td>
                    <td>${item.harga}</td>
                    <td><input type="number" class="form-control form-control-sm" style="width:70px" 
                        value="${item.qty}" onchange="updateTabelQty(${index}, this.value)"></td>
                    <td>${item.subtotal}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="hapusItem(${index})"><i class="mdi mdi-trash-can"></i></button></td>
                </tr>`;
            });
            document.getElementById('tabel_item').innerHTML = html;
            document.getElementById('total_display').innerText = total.toLocaleString();
        }

        function resetInput() {
            document.getElementById('input_kode').value = '';
            document.getElementById('nama_barang').value = '';
            document.getElementById('harga_barang').value = '';
            document.getElementById('qty').value = 1;
            document.getElementById('btn_tambah').disabled = true;
            tempBarang = null;
        }

        // Ketentuan I & J: Simpan & SWAL2
        function bayar() {
            if (keranjang.length === 0) return Swal.fire('Peringatan', 'Keranjang masih kosong', 'warning');

            const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);

            axios.post("{{ route('kasir.store') }}", {
                total_bayar: total,
                items: keranjang,
                _token: "{{ csrf_token() }}"
            })
                .then(res => {
                    Swal.fire('Sukses!', 'Pembayaran transaksi berhasil disimpan', 'success')
                        .then(() => {
                            keranjang = [];
                            renderTabel();
                            resetInput();
                        });
                })
                .catch(err => Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error'));
        }
    </script>
@endsection