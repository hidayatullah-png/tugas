@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-qrcode-scan"></i>
            </span> Validasi Pengambilan Pesanan
        </h3>
    </div>

    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Scanner QR</h4>
                    <div id="reader" style="width: 100%; border-radius: 10px;" class="bg-dark"></div>
                    <button class="btn btn-light btn-sm mt-3" onclick="location.reload()">
                        <i class="mdi mdi-refresh"></i> Reset Kamera
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-7 grid-margin">
            <div class="card shadow border-primary d-none" id="hasil-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Detail Pesanan Customer</h4>
                        <span id="status-bayar"></span>
                    </div>

                    <table class="table table-bordered mb-4">
                        <tr>
                            <th width="30%">No. Faktur</th>
                            <td id="res-faktur"></td>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <td id="res-customer"></td>
                        </tr>
                    </table>

                    <h5 class="text-primary mb-3">Menu Yang Harus Disiapkan:</h5>
                    <ul class="list-group mb-4" id="res-items">
                    </ul>

                    <button class="btn btn-gradient-primary w-100" onclick="location.reload()">
                        Selesai & Scan Berikutnya
                    </button>
                </div>
            </div>

            <div class="card bg-light text-center py-5" id="placeholder-scan">
                <i class="mdi mdi-qrcode mdi-48px text-muted"></i>
                <p class="text-muted">Menunggu scan QR Code dari pelanggan...</p>
            </div>
        </div>
    </div>

    <audio id="beep" src="{{ asset('assets/sounds/beep.mp3') }}"></audio>
@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const beep = document.getElementById("beep");
        const html5QrCode = new Html5Qrcode("reader");

        const onScanSuccess = (decodedText) => {
            // 1. Bunyi Beep (Poin c.1)
            beep.play();

            // 2. Scanner Berhenti (Poin c.2)
            html5QrCode.stop().then(() => {
                console.log("Scanner stopped.");
                processOrder(decodedText);
            });
        };

        function processOrder(orderId) {
            axios.get(`/vendor/api/cek-pesanan/${orderId}`)
                .then(res => {
                    const d = res.data;
                    // 3. Menampilkan Menu & Status (Poin c.3)
                    document.getElementById('placeholder-scan').classList.add('d-none');
                    document.getElementById('hasil-container').classList.remove('d-none');

                    document.getElementById('res-faktur').innerText = d.nomor_faktur;
                    document.getElementById('res-customer').innerText = d.nama_customer;

                    // Status Bayar
                    const statusBadge = d.status_bayar === 'Lunas'
                        ? '<span class="badge badge-success">LUNAS</span>'
                        : '<span class="badge badge-danger">BELUM BAYAR</span>';
                    document.getElementById('status-bayar').innerHTML = statusBadge;

                    // Render List Menu
                    const list = document.getElementById('res-items');
                    list.innerHTML = "";
                    d.items.forEach(item => {
                        list.innerHTML += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>${item.nama_barang}</strong>
                                <span class="badge badge-primary badge-pill">x${item.jumlah}</span>
                            </li>`;
                    });
                })
                .catch(err => {
                    alert(err.response.data.message || "Gagal mengambil data pesanan.");
                    location.reload();
                });
        }

        html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess);
    </script>
@endsection

