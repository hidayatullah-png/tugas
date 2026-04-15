@extends('layouts.vendor.vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-plus"></i>
            </span> Tambah Menu Baru
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Input Makanan/Minuman</h4>
                    <p class="card-description"> Masukkan detail menu yang ingin Anda jual. </p>

                    <form class="forms-sample" action="{{ route('vendor.makanan.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="nama_barang">Nama Makanan / Minuman</label>
                            <input type="text" class="form-control" name="nama_barang"
                                placeholder="Contoh: Nasi Goreng Gila" required>
                        </div>

                        <div class="form-group">
                            <label>Upload Foto Menu</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga (Rp)</label>
                            <input type="number" class="form-control" name="harga" placeholder="Contoh: 15000" required>
                        </div>

                        <div class="form-group">
                            <label for="stok">Stok Awal</label>
                            <input type="number" class="form-control" name="stok" placeholder="Contoh: 50" required>
                        </div>

                        <button type="submit" class="btn btn-gradient-primary me-2">Simpan Menu</button>
                        <a href="{{ route('vendor.makanan.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const inputFoto = document.querySelector('input[name="foto"]');

        inputFoto.addEventListener('change', function () {
            const file = this.files;

            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                // 1. Validasi Format
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Salah',
                        text: 'Gunakan format JPG atau PNG ya!',
                        confirmButtonColor: '#b66dff'
                    });
                    this.value = '';
                    return;
                }

                // 2. Validasi Ukuran
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Kegedean',
                        text: 'Maksimal ukuran cuma 2MB nih.',
                        confirmButtonColor: '#b66dff'
                    });
                    this.value = '';
                    return;
                }

                // 3. EFEK LOADER SAAT MEMPROSES GAMBAR
                Swal.fire({
                    title: 'Memproses Gambar...',
                    html: 'Sabar ya, lagi dicek...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // Munculin animasi loading bulat
                    }
                });

                const reader = new FileReader();
                reader.onload = function (e) {
                    // Simulasi delay sedikit biar loader-nya kelihatan (opsional)
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mantap!',
                            text: 'Gambar siap diupload.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        console.log('Preview siap ditampilkan');
                    }, 800);
                }

                reader.onerror = function () {
                    Swal.fire('Error', 'Gagal membaca file!', 'error');
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection