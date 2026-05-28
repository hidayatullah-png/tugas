@extends('layouts.admin.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="mdi mdi-nfc"></i>
            </span> Scanner Absensi NFC
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Sistem Kehadiran Mahasiswa</h4>

                    <i class="mdi mdi-cellphone-nfc mdi-48px text-primary mb-3"></i>

                    <p id="status-text" class="text-muted mb-4">Klik tombol di bawah untuk mengaktifkan sensor NFC.</p>

                    <button id="btn-scan" class="btn btn-gradient-info btn-lg w-100 mb-4" onclick="mulaiScanNfc()">
                        <i class="mdi mdi-contactless-payment"></i> Aktifkan Sensor NFC
                    </button>

                    <div id="hasil-scan" class="d-none">
                        <div class="alert" id="alert-box"></div>
                        <ul class="list-group list-group-flush text-start d-none" id="data-mhs">
                            <li class="list-group-item"><strong>Email:</strong> <span id="res-nim"></span></li>
                            <li class="list-group-item"><strong>Nama:</strong> <span id="res-nama"></span></li>
                            <li class="list-group-item"><strong>Waktu Hadir:</strong> <span id="res-waktu"></span></li>
                            <li class="list-group-item text-muted small"><strong>NFC Serial:</strong> <span
                                    id="res-serial"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="beep-success" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const statusText = document.getElementById('status-text');
        const btnScan = document.getElementById('btn-scan');
        const alertBox = document.getElementById('alert-box');
        const dataMhs = document.getElementById('data-mhs');
        const beep = document.getElementById('beep-success');

        async function mulaiScanNfc() {
            // 1. Cek Kompatibilitas Browser
            if (!('NDEFReader' in window)) {
                statusText.innerHTML = '<span class="text-danger">Browser/Perangkat Anda tidak mendukung Web NFC API. Gunakan Chrome for Android.</span>';
                return;
            }

            try {
                // 2. Inisialisasi NDEFReader
                const ndef = new NDEFReader();

                // 3. Minta izin dan mulai scan (Bakal muncul popup permission pertama kali)
                await ndef.scan();

                // Ubah UI untuk indikasi siap scan
                btnScan.classList.replace('btn-gradient-info', 'btn-success');
                btnScan.innerHTML = '<i class="mdi mdi-radar mdi-spin"></i> NFC Aktif. Dekatkan Kartu...';
                statusText.innerText = "Tempelkan Kartu Mahasiswa di area sensor NFC (belakang HP).";

                // 4. Pasang Event Listener saat tag terdeteksi
                ndef.addEventListener('reading', ({ serialNumber, message }) => {
                    console.log('NFC Terdeteksi. Serial:', serialNumber);
                    beep.play(); // Bunyikan sukses fisik

                    // Matikan mode scan sementara agar tidak double-scan
                    btnScan.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses Absensi...';

                    // Kirim Serial Number ke Laravel Backend
                    prosesAbsensi(serialNumber);
                });

                // Handle error saat proses reading
                ndef.addEventListener('readingerror', () => {
                    alertBox.className = "alert alert-warning";
                    alertBox.innerText = "Gagal membaca tag NFC. Tolong jauhkan dan dekatkan kembali.";
                    document.getElementById('hasil-scan').classList.remove('d-none');
                });

            } catch (error) {
                statusText.innerHTML = `<span class="text-danger">Error: ${error.message}</span>`;
                console.error(error);
            }
        }

        function prosesAbsensi(serialNumber) {
            axios.post('/api/absensi/proses-scan', {
                _token: '{{ csrf_token() }}',
                serial_number: serialNumber
            })
                .then(res => {
                    const data = res.data;
                    document.getElementById('hasil-scan').classList.remove('d-none');
                    alertBox.className = "alert alert-success fw-bold";
                    alertBox.innerText = data.message;

                    // Tampilkan Data Mhs
                    dataMhs.classList.remove('d-none');
                    document.getElementById('res-nim').innerText = data.nim;
                    document.getElementById('res-nama').innerText = data.nama_mahasiswa;
                    document.getElementById('res-waktu').innerText = data.waktu;
                    document.getElementById('res-serial').innerText = serialNumber;

                    resetScannerUI();
                })
                .catch(err => {
                    document.getElementById('hasil-scan').classList.remove('d-none');
                    alertBox.className = "alert alert-danger fw-bold";
                    dataMhs.classList.add('d-none'); // Sembunyikan detail nama jika gagal

                    // Tampilkan pesan error (Belum terdaftar atau sudah absen)
                    if (err.response && err.response.data) {
                        alertBox.innerText = err.response.data.message;
                    } else {
                        alertBox.innerText = "Terjadi kesalahan koneksi server.";
                    }

                    document.getElementById('res-serial').innerText = serialNumber;
                    resetScannerUI();
                });
        }

        function resetScannerUI() {
            setTimeout(() => {
                btnScan.innerHTML = '<i class="mdi mdi-radar mdi-spin"></i> NFC Aktif. Dekatkan Kartu Berikutnya...';
            }, 1500); // Kembalikan tulisan tombol setelah 1.5 detik
        }
    </script>
@endsection