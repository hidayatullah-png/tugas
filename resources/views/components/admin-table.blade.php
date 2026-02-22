{{-- File: resources/views/components/admin-table.blade.php --}}

@props(['title', 'buttonLabel' => null, 'buttonLink' => '#'])

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      
      {{-- 1. HEADER: Judul Tabel & Tombol Tambah --}}
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="card-title">{{ $title }}</h4>
          
          {{-- Tombol hanya muncul jika button Label diisi --}}
          @if($buttonLabel)
          <a href="{{ $buttonLink }}" class="btn btn-gradient-primary btn-sm">
              <i class="mdi mdi-plus"></i> {{ $buttonLabel }}
          </a>
          @endif
      </div>

      {{-- 2. ALERT: Pesan Sukses (Otomatis muncul jika ada) --}}
      @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      {{-- 3. TABEL: Menggunakan style table-striped dari template --}}
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            {{-- Slot untuk Judul Kolom (<th>) --}}
            {{ $thead }}
          </thead>
          <tbody>
            {{-- Slot untuk Isi Data (<tr><td>) --}}
            {{ $slot }}
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>