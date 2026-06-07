@extends('layouts.admin.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2" style="background: #3ea2c7;">
                <i class="mdi mdi-clipboard-text"></i>
            </span> Manajemen Antrian Real-Time
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Daftar Menunggu</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-menunggu">
                                <tr>
                                    <td colspan="3" class="text-center">Memuat data real-time...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body" style="background-color: #ffdd57; border-radius: 5px;">
                    <h4 class="card-title text-dark">Sedang Dipanggil</h4>
                    <h1 class="display-3 fw-bold text-dark text-center my-3" id="lbl-dipanggil-nomor">-</h1>
                    <h5 class="text-dark text-center mb-4" id="lbl-dipanggil-nama">Belum ada</h5>

                    <hr>
                    <h4 class="card-title text-dark mt-4">Daftar Terlewat</h4>
                    <ul class="list-group list-group-flush" id="list-terlewat">
                        <li class="list-group-item bg-transparent text-dark">Kosong</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Koneksi ke endpoint SSE
        const sseSource = new EventSource("{{ route('antrian.sse') }}");

        sseSource.addEventListener('queue-update', function (event) {
            const data = JSON.parse(event.data);
            updateUIAdmin(data);
        });

        sseSource.onerror = function (err) {
            console.error("SSE Error:", err);
        };

        function updateUIAdmin(data) {
            // 1. Update Tabel Menunggu
            const tbody = document.getElementById('table-menunggu');
            tbody.innerHTML = '';
            if (data.menunggu.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada antrian.</td></tr>';
            } else {
                data.menunggu.forEach(item => {
                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${item.nomor_antrian}</strong></td>
                            <td>${item.nama}</td>
                            <td>
                                <button onclick="prosesAksi('panggil', ${item.id})" class="btn btn-sm btn-success">Panggil</button>
                                <button onclick="prosesAksi('terlewat', ${item.id})" class="btn btn-sm btn-warning">Terlewat</button>
                            </td>
                        </tr>
                    `;
                });
            }

            // 2. Update Sedang Dipanggil
            if (data.dipanggil) {
                document.getElementById('lbl-dipanggil-nomor').innerText = data.dipanggil.nomor_antrian;
                document.getElementById('lbl-dipanggil-nama').innerText = data.dipanggil.nama;
            } else {
                document.getElementById('lbl-dipanggil-nomor').innerText = '-';
                document.getElementById('lbl-dipanggil-nama').innerText = 'Belum ada';
            }

            // 3. Update Terlewat
            const ulTerlewat = document.getElementById('list-terlewat');
            ulTerlewat.innerHTML = '';
            if (data.terlewat.length === 0) {
                ulTerlewat.innerHTML = '<li class="list-group-item bg-transparent text-dark">Kosong</li>';
            } else {
                data.terlewat.forEach(item => {
                    ulTerlewat.innerHTML += `
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center text-dark">
                            ${item.nomor_antrian} - ${item.nama}
                            <button onclick="prosesAksi('panggil', ${item.id})" class="btn btn-xs btn-dark">Panggil Ulang</button>
                        </li>
                    `;
                });
            }
        }

        function prosesAksi(aksi, id) {
            axios.post(`/api/antrian/${aksi}/${id}`, { _token: '{{ csrf_token() }}' })
                .then(res => console.log(res.data.message))
                .catch(err => console.error(err));
        }
    </script>
@endsection