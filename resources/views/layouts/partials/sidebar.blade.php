<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">{{ Auth::check() ? Auth::user()->name : 'Visitor' }}</span>
          <span>
            @if(Auth::check())
              @if(Auth::user()->role_id == 1) Administrator
              @elseif(Auth::user()->role_id == 3) Vendor
              @else Visitor
              @endif
            @endif
          </span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>

    @if(Auth::check())
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/dashboard') }}">
          <span class="menu-title">Dashboard</span>
          <i class="mdi mdi-home menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
          <span class="menu-title">Master Data</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-table-large menu-icon"></i>
        </a>
        <div class="collapse" id="tables">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              @if(auth()->user()->role_id == 1)
                <a class="nav-link" href="{{ route('buku.index') }}">Data Buku</a>
              @else
                <a class="nav-link" href="{{ route('visitor.buku.index') }}">Data Buku</a>
              @endif
            </li>
            <li class="nav-item">
              @if(auth()->user()->role_id == 1)
                <a class="nav-link" href="{{ route('kategori.index') }}">Data Kategori</a>
              @else
                <a class="nav-link" href="{{ route('visitor.kategori.index') }}">Data Kategori</a>
              @endif
            </li>
            <li class="nav-item">
              @if(auth()->user()->role_id == 1)
                <a class="nav-link" href="{{ route('barang.index') }}">Data Barang</a>
              @endif
            </li>
            <li class="nav-item">
              @if(auth()->user()->role_id == 1)
                <a class="nav-link" href="{{ route('admin.barang.scan') }}">
                  <span class="menu-title">Scanner Barang</span>
                </a>
              @endif
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#antrianMenu" aria-expanded="false"
          aria-controls="antrianMenu">
          <span class="menu-title">Sistem Antrian</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-human-queue menu-icon"></i>
        </a>
        <div class="collapse" id="antrianMenu">
          <ul class="nav flex-column sub-menu">
            @if(auth()->user()->role_id == 1)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.antrian.index') }}">Manajemen Admin</a>
              </li>
            @endif
            <li class="nav-item">
              <a class="nav-link" href="{{ route('antrian.guest') }}" target="_blank">Layar Guest</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('antrian.papan') }}" target="_blank">Layar Papan Publik</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="forms">
          <span class="menu-title">Study Case</span>
          <i class="menu-arrow"></i>
          <i class="fa fa-inbox menu-icon"></i>
        </a>
        <div class="collapse" id="forms">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('study-case.barang.tabel-biasa') }}">Tabel Biasa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('study-case.barang.tabel-datatables') }}">Tabel Data</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('study-case.select2-kota') }}">Select2 Kota</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#stuff" aria-expanded="false" aria-controls="stuff">
          <span class="menu-title">Modul Jquery & Axios</span>
          <i class="menu-arrow"></i>
          <i class="fa fa-inbox menu-icon"></i>
        </a>
        <div class="collapse" id="stuff">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('modul_ajax.kasir-axios') }}">Kasir Axios</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('modul_ajax.kasir') }}">Kasir jQuery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('modul_ajax.wilayah-axios') }}">Wilayah Axios</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('modul_ajax.wilayah') }}">Wilayah jQuery</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#kunjungan" aria-expanded="false" aria-controls="kunjungan">
          <span class="menu-title">Kunjungan Toko</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-map-marker-radius menu-icon"></i>
        </a>
        <div class="collapse" id="kunjungan">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('kunjungan.index') }}">Sales Tracking</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#absensi" aria-expanded="false" aria-controls="absensi">
          <span class="menu-title">Absensi NFC</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-nfc menu-icon"></i>
        </a>
        <div class="collapse" id="absensi">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('absensi.nfc.index') }}">Scan NFC</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#pdfMenu" aria-expanded="false" aria-controls="pdfMenu">
          <span class="menu-title">Generate PDF</span>
          <i class="menu-arrow"></i>
          <i class="fa fa-file-pdf-o menu-icon"></i>
        </a>
        <div class="collapse" id="pdfMenu">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('pdf.sertifikat') }}">
                Sertifikat (Landscape)
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('pdf.undangan') }}">
                Undangan (Portrait)
              </a>
            </li>
          </ul>
        </div>
      </li>
    @endif
  </ul>
</nav>