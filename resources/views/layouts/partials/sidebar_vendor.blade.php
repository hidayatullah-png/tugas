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
                    <span class="text-secondary text-small">Vendor Kantin</span>
                </div>
                <i class="mdi mdi-store text-success nav-profile-badge"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu" aria-expanded="false"
                aria-controls="customer-menu">
                <span class="menu-title">Customer</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
            <div class="collapse" id="customer-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendor.customer.index') }}">Data Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendor.customer.create1') }}">Tambah Customer 1 (BLOB)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendor.customer.create2') }}">Tambah Customer 2 (Path)</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('vendor.pesanan.index') }}">
                <span class="menu-title">Pesanan Masuk</span>
                <i class="mdi mdi-bell-ring menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-makanan" aria-expanded="false"
                aria-controls="menu-makanan">
                <span class="menu-title">Kelola Toko</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-food menu-icon"></i>
            </a>
            <div class="collapse" id="menu-makanan">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendor.makanan.index') }}">Daftar Makanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendor.makanan.create') }}">Tambah Menu</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>