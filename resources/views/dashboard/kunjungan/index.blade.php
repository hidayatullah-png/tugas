@extends('layouts.admin.admin') 

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-radius"></i>
        </span> Kunjungan Toko (Sales Tracking)
    </h3>
</div>

<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">Langkah 1: Scan Barcode Toko</h4>
                <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden;" class="bg-dark"></div>
                <button class="btn btn-gradient-info btn-sm mt-3" onclick="location.reload()">
                    <i class="mdi mdi-refresh"></i> Reset Proses
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-7 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Langkah 2: Validasi Geolocation</h4>
                <hr>

                <div id="gps-loader" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <p class="text-muted">Mencari akurasi GPS terbaik... mohon tunggu di tempat terbuka (Akurasi Target &le; 50m)</p>
                </div>

                <div id="hasil-kunjungan" class="d-none">
                    <div class="alert text-center fw-bold" id="status-alert"></div>
                    
                    <h5 class="text-primary">Data Identifikasi:</h5>
                    <table class="table table-bordered mb-4">
                        <tr><th width="40%">Nama Toko</th><td id="view-nama-toko">-</td></tr>
                        <tr><th>Jarak Aktual Kalkulasi</th><td id="view-jarak" class="fw-bold"></td></tr>
                        <tr><th>Batas Toleransi Efektif</th><td id="view-threshold"></td></tr>
                    </table>

                    <div class="row text-muted small text-center">
                        <div class="col-6 border-end">
                            <strong>Koordinat Target Toko:</strong><br>
                            Lat: <span id="toko-lat"></span> | Lng: <span id="toko-lng"></span> (<span id="toko-acc"></span>m)
                        </div>
                        <div class="col-6">
                            <strong>Koordinat Real-time Sales:</strong><br>
                            Lat: <span id="sales-lat"></span> | Lng: <span id="sales-lng"></span> (<span id="sales-acc"></span>m)
                        </div>
                    </div>
                </div>

                <div id="placeholder-text" class="text-center py-5 text-muted">
                    <i class="mdi mdi-qrcode-scan mdi-48px"></i>
                    <p>Silakan arahkan kamera ke barcode label toko terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const beep = document.getElementById("beep-sound");
    
    // Inisialisasi instansi dasar kamera Html5Qrcode (No-UI manual start)
    const html5QrCode = new Html5Qrcode("reader");
    const qrConfig = { fps: 10, qrbox: { width: 280, height: 160 } };

    // Trigger ketika Barcode Sukses Terbaca
    const onScanSuccess = (decodedText, decodedResult) => {
        // a. Bunyi beep pendek
        beep.play();

        // b. Scanner otomatis berhenti scan mendadak
        html5QrCode.stop().then(() => {
            console.log("Kamera dimatikan. Memulai pelacakan lokasi.");
            document.getElementById('placeholder-text').classList.add('d-none');
            document.getElementById('gps-loader').classList.remove('d-none');
            
            // Proses lanjut: ambil lokasi presisi sales
            startGeolocationProcess(decodedText);
        }).catch(err => console.error("Gagal stop kamera:", err));
    };

    // Jalankan Kamera Belakang otomatis (Environment) agar tidak Mirroring
    html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess);

    // Lampiran 1: Fungsi JS Kustom Pencari Koordinat Paling Akurat (Shareloc WA style)
    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
        return new Promise((resolve, reject) => {
            let bestResult = null;
            const startTime = Date.now();
            const watchId = navigator.geolocation.watchPosition(
                (position) => {
                    const acc = position.coords.accuracy;
                    
                    // Simpan hasil terbaik sejauh ini (angka akurasi terkecil = paling akurat)
                    if (!bestResult || acc < bestResult.coords.accuracy) {
                        bestResult = position;
                    }
                    // Kalau sudah mencapai target akurasi standar, langsung selesaikan kunci koordinat
                    if (acc <= targetAccuracy) {
                        navigator.geolocation.clearWatch(watchId);
                        resolve(bestResult);
                    }
                    // Batas toleransi waktu timeout, pakai data terbaik yang sempat terkumpul
                    if (Date.now() - startTime >= maxWait) {
                        navigator.geolocation.clearWatch(watchId);
                        if (bestResult) resolve(bestResult);
                        else reject(new Error("Timeout, GPS gagal mengunci posisi"));
                    }
                },
                (error) => reject(error),
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
            );
        });
    }

    // Alur Integrasi Data Koordinat Lapangan
    async function startGeolocationProcess(barcodeToko) {
        try {
            // Ambil data posisi sales dengan target akurasi di bawah 50 meter (Lampiran 1)
            const salesPos = await getAccuratePosition(50, 15000);
            
            document.getElementById('gps-loader').classList.add('d-none');
            document.getElementById('hasil-kunjungan').classList.remove('d-none');

            // Kirim paket koordinat ke backend via Axios untuk divalidasi dengan Haversine
            axios.post('/api/kunjungan/verifikasi', {
                barcode: barcodeToko,
                sales_lat: salesPos.coords.latitude,
                sales_lng: salesPos.coords.longitude,
                sales_acc: salesPos.coords.accuracy
            })
            .then(res => {
                showVerificationResult(res.data, true);
            })
            .catch(err => {
                if(err.response && err.response.status === 400) {
                    showVerificationResult(err.response.data, false);
                } else {
                    alert("Terjadi kesalahan sistem atau barcode toko tidak dikenal.");
                    location.reload();
                }
            });

        } catch (error) {
            alert("Gagal mendeteksi GPS GPS Handphone Anda: " + error.message);
            location.reload();
        }
    }

    // Tampilkan Informasi Akhir Sesuai Aturan Terima/Tolak Lampiran 3
    function showVerificationResult(res, isAccepted) {
        const alertBox = document.getElementById('status-alert');
        
        // Setup visual penanda status audit lokasi
        if(isAccepted) {
            alertBox.className = "alert alert-success fw-bold";
            alertBox.innerText = "KUNJUNGAN VALID (DITERIMA)";
        } else {
            alertBox.className = "alert alert-danger fw-bold";
            alertBox.innerText = "KUNJUNGAN INVALID (DITOLAK)";
        }

        // Ambil info detail toko dari respons gabungan backend
        document.getElementById('view-nama-toko').innerText = res.message;
        document.getElementById('view-jarak').innerText = res.jarak + " Meter";
        document.getElementById('view-threshold').innerText = res.threshold_efektif + " Meter";
        
        document.getElementById('sales-lat').innerText = document.getElementById('sales-lat').innerText || '-'; 
        document.getElementById('sales-acc').innerText = document.getElementById('sales-acc').innerText || '-';
    }
</script>
@endsection