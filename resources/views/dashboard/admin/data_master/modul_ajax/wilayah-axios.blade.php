@extends('layouts.admin.admin')

@section('title-page', 'Manajemen Wilayah - Axios Version')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map-marker-radius"></i>
            </span> Wilayah Administrasi Indonesia (Axios Version)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Eksplorasi Data <i
                        class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card card-outline-primary shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">Filter Lokasi (Metode Axios)</h4>

                    <div class="row custom-form-wilayah">
                        {{-- Level 1: Provinsi --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Provinsi</label>
                            <select id="provinsi" class="form-control form-control-sm border-primary">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Level 2: Kota --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Kota/Kabupaten</label>
                            <select id="kota" class="form-control form-control-sm" disabled>
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>

                        {{-- Level 3: Kecamatan --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Kecamatan</label>
                            <select id="kecamatan" class="form-control form-control-sm" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        {{-- Level 4: Kelurahan --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Kelurahan/Desa</label>
                            <select id="kelurahan" class="form-control form-control-sm" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status Monitor (Debug Mode) --}}
                    <div class="mt-4 p-3 bg-light rounded-sm border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div id="loader" class="spinner-border spinner-border-sm text-primary me-3 d-none"
                                role="status"></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Sistem
                                    Status</small>
                                <span id="statusInfo" class="text-dark font-weight-bold">Menunggu pilihan Provinsi...</span>
                            </div>
                        </div>
                        <i id="statusIcon" class="mdi mdi-information-outline text-info mdi-24px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        /**
         * Konfigurasi & Helper
         * Berdasarkan konsep Promise di Axios [cite: 255]
         */
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
                const label = id.charAt(0).toUpperCase() + id.slice(1);
                selectors[id].innerHTML = `<option value="">Pilih ${label}</option>`;
                selectors[id].disabled = true;
                selectors[id].classList.remove('border-primary');
            });
        };

        const setStatus = (type, message) => {
            const icon = selectors.statusIcon;
            const text = selectors.statusInfo;

            selectors.loader.classList.add('d-none');
            text.innerText = message;

            if (type === 'loading') {
                selectors.loader.classList.remove('d-none');
                text.className = 'text-primary font-weight-bold';
                icon.className = 'mdi mdi-sync mdi-spin text-primary mdi-24px';
            } else if (type === 'success') {
                text.className = 'text-success font-weight-bold';
                icon.className = 'mdi mdi-check-circle text-success mdi-24px';
            } else if (type === 'error') {
                text.className = 'text-danger font-weight-bold';
                icon.className = 'mdi mdi-alert-circle text-danger mdi-24px';
            }
        };

        /**
         * Fungsi Utama Render Data
         */
        const fetchData = (id, targetId, routeName, nextMessage) => {
            if (!id) return;

            setStatus('loading', `Sedang mengambil data ${targetId}...`);

            axios.post(routeName, {
                id: id,
                _token: "{{ csrf_token() }}" // Wajib disertakan pada data [cite: 132]
            })
                .then(res => {
                    // Asumsi response format: { status: 'success', data: [...] }
                    const data = res.data.data || res.data;
                    let options = `<option value="">Pilih ${targetId.charAt(0).toUpperCase() + targetId.slice(1)}</option>`;

                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });

                    selectors[targetId].innerHTML = options;
                    selectors[targetId].disabled = false;
                    selectors[targetId].classList.add('border-primary');

                    setStatus('success', `Data ${targetId} siap dipilih.`);
                })
                .catch(err => {
                    console.error(err);
                    setStatus('error', `Gagal memuat data ${targetId}!`);
                });
        };

        /**
         * Event Listeners
         * Mentrigger fungsi asinkron [cite: 341]
         */
        selectors.provinsi.addEventListener('change', function () {
            resetDropdowns(['kota', 'kecamatan', 'kelurahan']);
            fetchData(this.value, 'kota', "{{ route('api.getKota') }}");
        });

        selectors.kota.addEventListener('change', function () {
            resetDropdowns(['kecamatan', 'kelurahan']);
            fetchData(this.value, 'kecamatan', "{{ route('api.getKecamatan') }}");
        });

        selectors.kecamatan.addEventListener('change', function () {
            resetDropdowns(['kelurahan']);
            fetchData(this.value, 'kelurahan', "{{ route('api.getKelurahan') }}");
        });

    </script>
@endsection