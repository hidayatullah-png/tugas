<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
          <span
            class="text-secondary text-small">{{ auth()->user()->role_id == 1 ? 'Administrator' : 'Visitor' }}</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/dashboard') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
        <span class="menu-title">Master Data</span>
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
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#pdfMenu" aria-expanded="false">
        <span class="menu-title">Generate PDF</span>
        <i class="mdi mdi-file-pdf menu-icon"></i>
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
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <span class="menu-title">Charts</span>
        <i class="mdi mdi-chart-bar menu-icon"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-lock menu-icon"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="#blank"> Blank Page </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('login') }}"> Login </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('register') }}"> Register </a></li>
          <li class="nav-item"> <a class="nav-link" href="#blabla"> 404 </a></li>
        </ul>
      </div>
    </li>
  </ul>
</nav>