@extends('layouts.admin.admin')

@section('title-page', 'Manajemen Wilayah - jQuery AJAX')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map-marker-radius"></i>
            </span> Wilayah Administrasi Indonesia (jQuery Version)
        </h3>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card card-outline-primary shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">Filter Wilayah Bertingkat</h4>

                    <div class="row">
                        {{-- Level 1 --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Provinsi</label>
                            <select id="provinsi" class="form-control form-control-sm border-primary">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Level 2 --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Kota/Kabupaten</label>
                            <select id="kota" class="form-control form-control-sm" disabled>
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>

                        {{-- Level 3 --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Kecamatan</label>
                            <select id="kecamatan" class="form-control form-control-sm" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        {{-- Level 4 --}}
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Kelurahan/Desa</label>
                            <select id="kelurahan" class="form-control form-control-sm" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status Monitor --}}
                    <div class="mt-4 p-3 bg-light rounded-sm border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div id="ajax-loader" class="spinner-border spinner-border-sm text-primary me-3 d-none"
                                role="status"></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Sistem
                                    Status</small>
                                <span id="status-text" class="text-dark font-weight-bold">Menunggu pilihan
                                    Provinsi...</span>
                            </div>
                        </div>
                        <i id="status-icon" class="mdi mdi-information-outline text-info mdi-24px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            // Helper: Reset Dropdown
            function resetSelect(ids) {
                ids.forEach(id => {
                    let label = id.charAt(0).toUpperCase() + id.slice(1);
                    $(`#${id}`).html(`<option value="">Pilih ${label}</option>`).prop('disabled', true);
                });
            }

            // Helper: Update Status (Sama dengan logika setStatus di Axios)
            function updateStatus(type, message) {
                const loader = $('#ajax-loader');
                const text = $('#status-text');
                const icon = $('#status-icon');

                loader.addClass('d-none');
                text.text(message);

                if (type === 'loading') {
                    loader.removeClass('d-none');
                    text.attr('class', 'text-primary font-weight-bold');
                    icon.attr('class', 'mdi mdi-sync mdi-spin text-primary mdi-24px');
                } else if (type === 'success') {
                    text.attr('class', 'text-success font-weight-bold');
                    icon.attr('class', 'mdi mdi-check-circle text-success mdi-24px');
                } else if (type === 'error') {
                    text.attr('class', 'text-danger font-weight-bold');
                    icon.attr('class', 'mdi mdi-alert-circle text-danger mdi-24px');
                }
            }

            // 1. Level 1 (Provinsi) -> Level 2 (Kota)
            $('#provinsi').on('change', function () {
                let id = $(this).val();
                resetSelect(['kota', 'kecamatan', 'kelurahan']);

                if (!id) return;

                updateStatus('loading', 'Mengambil data kota...');

                $.ajax({
                    url: "{{ route('api.getKota') }}", // FIXED: Menggunakan api.getKota
                    method: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        let options = '<option value="">Pilih Kota</option>';
                        $.each(res.data, function (i, item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#kota').html(options).prop('disabled', false);
                        updateStatus('success', 'Data kota berhasil dimuat');
                    },
                    error: function () {
                        updateStatus('error', 'Gagal memuat data!');
                    }
                });
            });

            // 2. Level 2 (Kota) -> Level 3 (Kecamatan)
            $('#kota').on('change', function () {
                let id = $(this).val();
                resetSelect(['kecamatan', 'kelurahan']);

                if (!id) return;

                updateStatus('loading', 'Mengambil data kecamatan...');

                $.ajax({
                    url: "{{ route('api.getKecamatan') }}", // FIXED: Menggunakan api.getKecamatan
                    method: "POST",
                    data: { id: id, _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        let options = '<option value="">Pilih Kecamatan</option>';
                        $.each(res.data, function (i, item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#kecamatan').html(options).prop('disabled', false);
                        updateStatus('success', 'Data kecamatan berhasil dimuat');
                    }
                });
            });

            // 3. Level 3 (Kecamatan) -> Level 4 (Kelurahan)
            $('#kecamatan').on('change', function () {
                let id = $(this).val();
                resetSelect(['kelurahan']);

                if (!id) return;

                updateStatus('loading', 'Mengambil data kelurahan...');

                $.ajax({
                    url: "{{ route('api.getKelurahan') }}", // FIXED: Menggunakan api.getKelurahan
                    method: "POST",
                    data: { id: id, _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        let options = '<option value="">Pilih Kelurahan</option>';
                        $.each(res.data, function (i, item) {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('#kelurahan').html(options).prop('disabled', false);
                        updateStatus('success', 'Data kelurahan berhasil dimuat');
                    }
                });
            });

        });
    </script>
@endsection