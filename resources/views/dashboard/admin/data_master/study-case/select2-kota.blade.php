@extends('layouts.admin.admin') {{-- Sesuaikan dengan master layout kamu --}}

@section('content')
<style>
    /* Styling Form Input (Select Biasa) */
    .form-control-custom {
        height: 45px !important;
        border: 1px solid #ebedf2 !important;
        background-color: #ffffff;
        border-radius: 4px;
        padding: 10px 15px;
        width: 100%;
    }

    /* Styling Search Input ala Navbar (Card Kanan) */
    .navbar-search-wrapper {
        background: #f1f3f9; 
        border-radius: 4px;
        padding: 5px 15px;
        display: flex;
        align-items: center;
        height: 45px;
        border: 1px solid transparent;
        transition: border 0.3s;
    }
    .navbar-search-wrapper:focus-within {
        border: 1px solid #b66dff;
    }
    .navbar-search-wrapper i {
        font-size: 1.1rem;
        color: #b66dff; 
        margin-right: 10px;
    }
    .navbar-search-wrapper input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        width: 100%;
        color: #495057;
        outline: none;
    }

    /* Box Hasil Terpilih */
    .result-box {
        background: #ffffff;
        border: 1px solid #ebedf2;
        border-radius: 4px;
        padding: 15px;
        min-height: 50px;
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    /* Dropdown Hasil Pencarian (Floating) */
    .search-dropdown {
        position: absolute;
        width: 90%;
        z-index: 1000;
        background: white;
        border: 1px solid #ebedf2;
        border-top: none;
        max-height: 150px;
        overflow-y: auto;
        display: none;
        box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
    }
    .search-item {
        padding: 10px 15px;
        cursor: pointer;
    }
    .search-item:hover {
        background: #f1f3f9;
        color: #b66dff;
    }
</style>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-radius"></i>
        </span> Study Case: Select2 & Search
    </h3>
</div>

<div class="row">
    {{-- 1. INPUT MASTER KOTA --}}
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">1. Input Master Kota</h4>
                <p class="card-description">Tambahkan nama kota ke sistem untuk mengisi daftar di bawah.</p>
                <div class="input-group">
                    <input type="text" id="master_kota" class="form-control" placeholder="Ketik nama kota..." style="height: 45px;">
                    <div class="input-group-append">
                        <button class="btn btn-gradient-primary px-4 py-2" onclick="tambahkanKeSistem()">
                            <i class="mdi mdi-plus"></i> TAMBAH
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. SELECT BIASA --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-gradient-primary text-white py-3">SELECT BIASA</div>
            <div class="card-body">
                <p class="text-muted small">Pilih dari List:</p>
                <select id="select_biasa" class="form-control-custom" onchange="updateBiasa(this.value)">
                    <option value="">-- Pilih Kota --</option>
                </select>
                
                <div class="mt-4">
                    <p class="text-muted small mb-1">Kota Terpilih (ID):</p>
                    <div class="result-box">
                        <h6 id="hasil_biasa" class="mb-0 text-primary font-weight-bold">-</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. SELECT 2 STYLE --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-gradient-info text-white py-3">SELECT 2 (NAVBAR SEARCH STYLE)</div>
            <div class="card-body">
                <p class="text-muted small">Cari Kota secara Real-time:</p>
                <div class="navbar-search-wrapper">
                    <i class="mdi mdi-magnify"></i>
                    <input type="text" id="nav_search" placeholder="Masukkan nama kota..." autocomplete="off">
                </div>
                <div id="nav_results" class="search-dropdown"></div>

                <div class="mt-4">
                    <p class="text-muted small mb-1">Kota Terpilih (Nama):</p>
                    <div class="result-box">
                        <h6 id="hasil_nav" class="mb-0 text-info font-weight-bold">-</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let listKota = [];

    function tambahkanKeSistem() {
        let val = $('#master_kota').val().trim();
        if (val === "") return;

        // Cek agar tidak duplikat
        if (listKota.includes(val)) {
            alert('Kota sudah ada di daftar!');
            return;
        }

        listKota.push(val);
        $('#select_biasa').append(new Option(val, val));
        
        $('#master_kota').val("").focus();
        // SweetAlert jika ada, jika tidak pakai alert biasa
        alert('Kota ' + val + ' berhasil masuk daftar!');
    }

    function updateBiasa(val) {
        $('#hasil_biasa').text(val || "-");
    }
 
    $('#nav_search').on('keyup', function() {
        let query = $(this).val().toLowerCase();
        let resultsDiv = $('#nav_results');
        resultsDiv.empty();

        if (query.length > 0) {
            let filtered = listKota.filter(k => k.toLowerCase().includes(query));
            
            if (filtered.length > 0) {
                filtered.forEach(item => {
                    resultsDiv.append(`<div class="search-item" onclick="pilihNav('${item}')">${item}</div>`);
                });
                resultsDiv.show();
            } else {
                resultsDiv.hide();
            }
        } else {
            resultsDiv.hide();
        }
    });

    function pilihNav(kota) {
        $('#nav_search').val(kota);
        $('#hasil_nav').text(kota);
        $('#nav_results').hide();
    }

    // Menutup dropdown jika klik di luar area search
    $(document).click(function(e) {
        if (!$(e.target).closest('.navbar-search-wrapper, #nav_results').length) {
            $('#nav_results').hide();
        }
    });
</script>
@endsection