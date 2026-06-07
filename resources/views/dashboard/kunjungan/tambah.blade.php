@extends('layouts.admin.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon text-white me-2" style="background-color: #3ea2c7;">
                <i class="mdi mdi-store"></i>
            </span> Input Titik Awal Toko
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-4">Tambahkan toko baru dan simpan koordinat lokasi awal toko. Barcode akan
                        di-generate otomatis oleh sistem.</p>

                    <form action="{{ route('kunjungan.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="fw-bold">Nama Toko</label>
                            <input type="text" class="form-control" name="nama_toko" placeholder="Contoh: Toko Abadi Jaya"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold">Latitude</label>
                            <input type="text" class="form-control bg-light" id="lat" name="latitude" readonly required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold">Longitude</label>
                            <input type="text" class="form-control bg-light" id="lng" name="longitude" readonly required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="fw-bold">Accuracy (meter)</label>
                            <input type="text" class="form-control bg-light" id="acc" name="accuracy" readonly required>
                        </div>

                        <button type="button" class="btn text-white w-100 mb-2" style="background-color: #3ea2c7;"
                            onclick="ambilLokasiToko()" id="btn-geoloc">
                            <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Toko
                        </button>

                        <button type="submit" class="btn btn-dark w-100 fw-bold" id="btn-submit" disabled>
                            <i class="mdi mdi-content-save"></i> Simpan Toko
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Lampiran 1: Fungsi pengambil lokasi akurat dari modul
        function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
            return new Promise((resolve, reject) => {
                let bestResult = null;
                const startTime = Date.now();
                const watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        const acc = position.coords.accuracy;
                        if (!bestResult || acc < bestResult.coords.accuracy) {
                            bestResult = position;
                        }
                        if (acc <= targetAccuracy) {
                            navigator.geolocation.clearWatch(watchId);
                            resolve(bestResult);
                        }
                        if (Date.now() - startTime >= maxWait) {
                            navigator.geolocation.clearWatch(watchId);
                            if (bestResult) resolve(bestResult);
                            else reject(new Error("Timeout, tidak dapat posisi"));
                        }
                    },
                    (error) => reject(error),
                    { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
                );
            });
        }

        async function ambilLokasiToko() {
            const btnGeo = document.getElementById('btn-geoloc');
            btnGeo.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari sinyal GPS...';
            btnGeo.disabled = true;

            try {
                const pos = await getAccuratePosition(50, 15000);

                // Isi form otomatis
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
                document.getElementById('acc').value = pos.coords.accuracy;

                // Kembalikan tombol
                btnGeo.innerHTML = '<i class="mdi mdi-check"></i> Lokasi Terkunci';
                btnGeo.classList.replace('btn-primary', 'btn-success');

                // Aktifkan tombol submit
                document.getElementById('btn-submit').disabled = false;
            } catch (error) {
                alert("Gagal mengambil lokasi: " + error.message);
                btnGeo.innerHTML = '<i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Toko';
                btnGeo.disabled = false;
            }
        }
    </script>
@endsection