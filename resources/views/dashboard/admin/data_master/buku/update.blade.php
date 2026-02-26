@extends ('layouts.admin.admin')

@section('content')

    <div class="page-header">
        <h3 class="page-title"> Ubah Data Buku </h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"> Form Ubah Buku </h4>
                    <p class="card-description"> Perbarui informasi buku sesuai kebutuhan. </p>

                    {{-- Form Edit Buku --}}
                    <form class="forms-sample" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="kode">Kode Buku</label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode"
                                name="kode" placeholder="Misal: B001" value="{{ old('kode', $buku->kode) }}">
                            @error('kode') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul"
                                name="judul" placeholder="Misal: Pemrograman Laravel" value="{{ old('judul', $buku->judul) }}">
                            @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label for="pengarang">Pengarang</label>
                            <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang"
                                name="pengarang" placeholder="Misal: John Doe" value="{{ old('pengarang', $buku->pengarang) }}">
                            @error('pengarang') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label for="idkategori">Kategori</label>
                            <select class="form-control @error('idkategori') is-invalid @enderror" id="idkategori"
                                name="idkategori">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->idkategori }}" {{ (old('idkategori', $buku->idkategori) == $item->idkategori) ? 'selected' : '' }}>
                                        {{ $item->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idkategori') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Simpan Perubahan</button>
                        <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    
@endsection