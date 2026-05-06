@extends ('layouts.admin.admin')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-barcode-scan"></i>
            </span>
            Scanner Barang
        </h3>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Kamera Scanner</h4>
                    <div id="reader" style="width: 100%; border-radius: 10px;" class="bg-dark">
                        <button class="btn btn-gradient-primary btn-sm mt-3" onclick="location.reload()">
                            <i class="mdi mdi-refresh"></i> Refresh Scanner
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informasi barang</h4>
                    <hr>
                    <div id="hasil" class="text-center">
                        <p class="text-muted">Scan kode QR untuk menampilkan informasi barang</p>
                    </div>
                    <div id="detail-barang" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Detail Barang</h5>
                            <button class="btn btn-gradient-primary btn-sm" onclick="location.reload()">
                                <i class="mdi mdi-refresh"></i> kembali ke Scanner
                            </button>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">ID Barang</th>
                                <td id="res-id" class="fw-bold text-primary"></td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td id="res-nama"></td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td id="res-harga" class="text-success fw-bold"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="beep" src="{{ asset('assets/sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // 1. Jalankan bunyi beep (Poin a)
            document.getElementById('beep').play();

            // 2. BERHENTIKAN SCANNER (Poin b)
            // Menggunakan .clear() untuk mematikan kamera pada Html5QrcodeScanner
            html5QrcodeScanner.clear().then(() => {
                console.log("Scanner stopped.");

                // 3. AMBIL DATA DARI DATABASE (Poin c)
                fetchDataBarang(decodedText);
            }).catch(error => {
                console.error("Gagal menghentikan scanner: ", error);
            });
        }

        function fetchDataBarang(idBarang) {
            // Panggil API yang sudah kita buat di BarangController
            axios.get(`/admin/api/cari-barang/${idBarang}`)
                .then(res => {
                    const barang = res.data.data;

                    // Tampilkan UI Detail
                    document.getElementById('hasil').classList.add('d-none');
                    document.getElementById('detail-barang').classList.remove('d-none');

                    // Isi data barang berdasarkan Database
                    document.getElementById('res-id').innerText = barang.id_barang;
                    document.getElementById('res-nama').innerText = barang.nama;

                    // Format mata uang Rupiah
                    const hargaFormatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(barang.harga);

                    document.getElementById('res-harga').innerText = hargaFormatted;
                })
                .catch(err => {
                    alert("Barang " + idBarang + " tidak ditemukan!");
                    // Jika gagal, mungkin kamu mau reload halaman agar user bisa scan lagi
                    location.reload();
                });
        }

        // Inisialisasi Scanner
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
            fps: 10,
            qrbox: { width: 250, height: 150 } // Box disesuaikan untuk Barcode 1D
        });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection