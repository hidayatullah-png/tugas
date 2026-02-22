@extends ('layouts.admin.admin')
@section('content')
<div class="page-header">
    <h3 class="page-title"> Tambah Data Buku </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> Form Tambah Buku </h4>
                <p class="card-description"> Isi data buku dengan lengkap dan benar. </p>
                
                {{-- Form Tambah Buku --}}
                <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" placeholder="Misal: B001" value="{{ old('kode') }}">
                        @error('kode') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control @error('judul') is-invalid @haserror" id="judul" name="judul" placeholder="Misal: Pemrograman Laravel" value="{{ old('judul') }}">
                        @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang" name="pengarang" placeholder="Misal: John Doe" value="{{ old('pengarang') }}">
                        @error('pengarang') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-gradient-primary me-2">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection