@extends('layouts.guest')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="col-md-6 col-lg-5 grid-margin stretch-card">
            <div class="card shadow-sm border-0" style="border-radius: 10px; overflow: hidden;">
                <div class="card-header text-center py-4" style="background-color: #ffdd57; border-bottom: none;">
                    <h3 class="mb-0 fw-bold text-dark">
                        <i class="mdi mdi-ticket-account me-2"></i>Ambil Nomor Antrian
                    </h3>
                </div>
                <div class="card-body p-4 bg-white">

                    @if(session('success'))
                        <div class="text-center mb-2">
                            <h4 class="text-muted mb-3">Nomor Antrian Anda:</h4>
                            <h1 class="display-1 fw-bold mb-3" style="color: #3ea2c7; font-size: 6rem;">{{ session('nomor') }}
                            </h1>
                            <p class="lead mb-4">Atas nama: <strong class="text-dark">{{ session('nama') }}</strong></p>

                            <div class="alert alert-success fw-bold p-3 mb-4">
                                <i class="mdi mdi-check-circle me-1"></i> Silakan tunggu nomor Anda dipanggil.
                            </div>

                            <a href="{{ route('antrian.guest') }}" class="btn btn-light btn-lg w-100 border">
                                <i class="mdi mdi-refresh me-1"></i> Daftar Antrian Baru
                            </a>
                        </div>
                    @else
                        <form action="{{ route('antrian.submit') }}" method="POST">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="nama" class="form-label fw-bold text-dark">Nama Lengkap</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0">
                                            <i class="mdi mdi-account-outline text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg border-left-0" id="nama" name="nama"
                                        placeholder="Masukkan nama Anda..." required autofocus style="padding-left: 10px;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm"
                                style="background-color: #3ea2c7;">
                                <i class="mdi mdi-send me-1"></i> Daftar Sekarang
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection