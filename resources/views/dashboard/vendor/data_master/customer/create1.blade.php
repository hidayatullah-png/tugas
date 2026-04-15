@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-plus"></i>
            </span> Tambah Customer (Metode BLOB)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Smile for the camera :D <i
                        class="mdi mdi-camera icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informasi Pelanggan</h4>
                    <form action="{{ route('vendor.customer.store1') }}" method="POST" id="formCustomer">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Lengkap</label>
                            <input type="text" name="nama_customer" class="form-control" placeholder="Contoh: Hidayatullah Sukma Dewi"
                                required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"
                                placeholder="Nama jalan, nomor rumah..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Provinsi</label>
                                <select id="provinsi" name="provinsi" class="form-control border-primary">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Kota/Kabupaten</label>
                                <select id="kota" name="kota" class="form-control" disabled>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Kecamatan</label>
                                <select id="kecamatan" name="kecamatan" class="form-control" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Kelurahan / Kodepos</label>
                                <select id="kelurahan" name="kelurahan_kodepos" class="form-control" disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="image_data" id="image_data">

                        <div class="mt-4 p-3 bg-light rounded d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <div id="loader" class="spinner-border spinner-border-sm text-primary me-3 d-none"></div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold"
                                        style="font-size: 0.65rem;">Status</small>
                                    <span id="statusInfo" class="text-dark small font-weight-bold">Siap menerima
                                        data.</span>
                                </div>
                            </div>
                            <i id="statusIcon" class="mdi mdi-information-outline text-info mdi-24px"></i>
                        </div>

                        <button type="submit" id="btnSimpan" class="btn btn-gradient-primary w-100 shadow" disabled>
                            <i class="mdi mdi-content-save"></i> Simpan Data & Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5 grid-margin stretch-card">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h4 class="card-title">Ambil Foto Customer</h4>

                    <div id="my_camera" class="mx-auto border bg-dark mb-3 shadow-sm"
                        style="border-radius: 15px; overflow: hidden;"></div>

                    <button type="button" class="btn btn-info btn-sm mb-3" onclick="take_snapshot()">
                        <i class="mdi mdi-camera"></i> Ambil Foto (Snap)
                    </button>

                    <div id="results" class="mt-2 d-none">
                        <p class="text-muted small">Preview Foto:</p>
                        <div class="border p-1 d-inline-block bg-white shadow-sm rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- 1. KONFIGURASI KAMERA ---
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            flip_horiz: true
        });
        Webcam.attach('#my_camera');

        function take_snapshot() {
            Webcam.snap(function (data_uri) {
                document.getElementById('image_data').value = data_uri;
                document.querySelector('#results div').innerHTML = '<img src="' + data_uri + '" width="150" class="rounded"/>';
                document.getElementById('results').classList.remove('d-none');
                document.getElementById('btnSimpan').disabled = false;

                Swal.fire({
                    icon: 'success',
                    title: 'Foto Terambil!',
                    showConfirmButton: false,
                    timer: 800
                });
            });
        }

        // --- 2. LOGIKA DROPDOWN (ADAPTASI DARI SCRIPT KAMU) ---
        const selectors = {
            provinsi: document.getElementById('provinsi'),
            kota: document.getElementById('kota'),
            kecamatan: document.getElementById('kecamatan'),
            kelurahan: document.getElementById('kelurahan'),
            loader: document.getElementById('loader'),
            statusInfo: document.getElementById('statusInfo'),
            statusIcon: document.getElementById('statusIcon')
        };

        const resetDropdowns = (ids) => {
            ids.forEach(id => {
                const label = id === 'kelurahan' ? 'Kelurahan' : id.charAt(0).toUpperCase() + id.slice(1);
                selectors[id].innerHTML = `<option value="">Pilih ${label}</option>`;
                selectors[id].disabled = true;
            });
        };

        const setStatus = (type, message) => {
            selectors.loader.classList.add('d-none');
            selectors.statusInfo.innerText = message;
            if (type === 'loading') {
                selectors.loader.classList.remove('d-none');
                selectors.statusIcon.className = 'mdi mdi-sync mdi-spin text-primary mdi-24px';
            } else if (type === 'success') {
                selectors.statusIcon.className = 'mdi mdi-check-circle text-success mdi-24px';
            }
        };

        const fetchData = (id, targetId, routeName) => {
            if (!id) return;
            setStatus('loading', `Memuat data ${targetId}...`);

            axios.get(routeName, { params: { id: id } })  // ✅ GET bukan POST
                .then(res => {
                    const data = res.data.data || res.data;
                    let options = `<option value="">Pilih ${targetId}</option>`;
                    data.forEach(item => {
                        // ✅ value pakai item.id untuk fetch level selanjutnya
                        //    text pakai item.name untuk ditampilkan & disimpan
                        options += `<option value="${item.id}" data-name="${item.name}">${item.name}</option>`;
                    });
                    selectors[targetId].innerHTML = options;
                    selectors[targetId].disabled = false;
                    setStatus('success', `Data ${targetId} tersedia.`);
                })
                .catch(() => setStatus('error', 'Gagal memuat data!'));
        };

        selectors.provinsi.addEventListener('change', function () {
            resetDropdowns(['kota', 'kecamatan', 'kelurahan']);
            fetchData(this.value, 'kota', "{{ route('vendor.api.kota') }}");  // ✅
        });

        selectors.kota.addEventListener('change', function () {
            resetDropdowns(['kecamatan', 'kelurahan']);
            fetchData(this.value, 'kecamatan', "{{ route('vendor.api.kecamatan') }}");  // ✅
        });

        selectors.kecamatan.addEventListener('change', function () {
            resetDropdowns(['kelurahan']);
            fetchData(this.value, 'kelurahan', "{{ route('vendor.api.kelurahan') }}");  // ✅
        });

        // --- 3. LOADER SAAT SUBMIT ---
        document.getElementById('formCustomer').addEventListener('submit', function () {
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Data foto BLOB sedang dikirim ke database',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });
    </script>
@endsection