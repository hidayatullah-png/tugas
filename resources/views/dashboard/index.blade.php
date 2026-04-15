@extends('layouts.guest.guest')

@section('content')
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-food"></i>
      </span> Kantin Digital
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span>Pesan makanan tanpa ribet! <i
            class="mdi mdi-check-circle-outline icon-sm text-primary align-middle"></i>
        </li>
      </ul>
    </nav>
  </div>

  <div class="row">
    <div class="col-md-8 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title">Menu Tersedia</h4>
            <select class="form-control form-control-sm w-25" id="filterVendor" onchange="filterMenu(this.value)">
              <option value="all">Semua Toko</option>
              @foreach($vendors as $v)
                <option value="{{ $v->id }}">{{ $v->nama_vendor }}</option>
              @endforeach
            </select>
          </div>

          <div class="row" id="menu-container">
            @foreach($makanan as $m)
              <div class="col-md-6 col-lg-4 grid-margin menu-item" data-vendor="{{ $m->vendor_id }}">
                <div class="card card-img-holder text-dark border shadow-sm h-100">

                  <div class="position-relative">
                    @if($m->foto)
                      <img src="{{ asset('storage/menu/' . $m->foto) }}" class="card-img-top" alt="{{ $m->nama_barang }}"
                        style="height: 160px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    @else
                      <img src="{{ asset('assets/images/dashboard/no-food.jpg') }}" class="card-img-top"
                        style="height: 160px; object-fit: cover; opacity: 0.5;">
                    @endif

                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                      style="opacity: 0.3;" />
                  </div>

                  <div class="card-body p-3">
                    <p class="text-muted small mb-1">
                      <i class="mdi mdi-store text-primary"></i> {{ $m->nama_vendor }}
                    </p>
                    <h5 class="font-weight-bold mb-2">{{ $m->nama_barang }}</h5>
                    <h4 class="text-primary mb-3">Rp {{ number_format($m->harga, 0, ',', '.') }}</h4>

                    <button class="btn btn-gradient-primary btn-sm btn-block shadow-sm"
                      onclick="tambahKeKeranjang({{ $m->id }}, '{{ $m->nama_barang }}', {{ $m->harga }})">
                      <i class="mdi mdi-plus"></i> Tambah ke Pesanan
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
      <div class="card bg-light border-0 shadow-sm sticky-top" style="top: 100px; height: fit-content;">
        <div class="card-body">
          <h4 class="card-title text-center"><i class="mdi mdi-cart-outline"></i> Pesanan Saya</h4>
          <hr>

          <div class="form-group">
            <label for="nama_pembeli">Nama Pemesan / No. Meja</label>
            <input type="text" class="form-control" id="nama_pembeli" placeholder="Contoh: Budi - Meja 5">
          </div>

          <div id="keranjang-list" style="max-height: 300px; overflow-y: auto;">
            <p class="text-center text-muted my-4">Belum ada makanan terpilih</p>
          </div>

          <hr>
          <div class="d-flex justify-content-between mb-4">
            <h5 class="font-weight-bold">Total</h5>
            <h4 class="text-primary font-weight-bold" id="total-harga">Rp 0</h4>
          </div>

          <button class="btn btn-gradient-danger btn-lg btn-block shadow" id="btn-checkout" onclick="checkout()" disabled>
            <i class="mdi mdi-credit-card"></i> Bayar Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    let cart = [];
    let grandTotal = 0;

    function tambahKeKeranjang(id, nama, harga) {
      let exists = cart.find(item => item.id === id);
      if (exists) {
        exists.qty++;
        exists.subtotal = exists.qty * exists.harga;
      } else {
        cart.push({ id: id, nama_barang: nama, harga: harga, qty: 1, subtotal: harga });
      }
      renderCart();
    }

    function hapusItem(id) {
      cart = cart.filter(item => item.id !== id);
      renderCart();
    }

    function renderCart() {
      const container = document.getElementById('keranjang-list');
      const totalDisplay = document.getElementById('total-harga');
      const btnCheckout = document.getElementById('btn-checkout');

      container.innerHTML = '';
      grandTotal = 0;

      if (cart.length === 0) {
        container.innerHTML = '<p class="text-center text-muted my-4">Belum ada makanan terpilih</p>';
        btnCheckout.disabled = true;
      } else {
        cart.forEach(item => {
          grandTotal += item.subtotal;
          container.innerHTML += `
                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                <div>
                                    <p class="mb-0 font-weight-bold">${item.nama_barang}</p>
                                    <small class="text-muted">${item.qty} x Rp ${item.harga.toLocaleString('id-ID')}</small>
                                </div>
                                <div class="text-right">
                                    <p class="mb-0 text-dark small">Rp ${item.subtotal.toLocaleString('id-ID')}</p>
                                    <button class="btn btn-link text-danger p-0 small" onclick="hapusItem(${item.id})"><i class="mdi mdi-delete"></i></button>
                                </div>
                            </div>
                        `;
        });
        btnCheckout.disabled = false;
      }
      totalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function checkout() {
      const nama = document.getElementById('nama_pembeli').value;
      if (!nama) {
        Swal.fire('Nama Kosong', 'Tolong isi nama kamu dulu ya!', 'warning');
        return;
      }

      Swal.fire({
        title: 'Sedang Menyiapkan Pembayaran...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
      });

      axios.post("{{ route('guest.checkout') }}", {
        nama_pembeli: nama,
        total_harga: grandTotal,
        items: cart
      })
        .then(response => {
          Swal.close();
          window.snap.pay(response.data.snap_token, {
            onSuccess: (result) => {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Menampilkan QR Code...',
                showConfirmButton: false,
                timer: 1500
              }).then(() => {
                // Redirect ke route payment.finish (yang mengarah ke VendorPesananController@selesai)
                window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id;
              });
            },
            onPending: (result) => {
              Swal.fire('Pending', 'Selesaikan pembayaranmu di aplikasi ya!', 'info');
            },
            onError: (result) => {
              Swal.fire('Gagal', 'Pembayaran bermasalah, coba lagi.', 'error');
            }
          });
        })
        .catch(error => {
          Swal.close();
          Swal.fire('Error', error.response.data.message || 'Gagal terhubung ke Midtrans', 'error');
        });
    }

    function filterMenu(vendorId) {
      const items = document.querySelectorAll('.menu-item');
      items.forEach(item => {
        if (vendorId === 'all' || item.getAttribute('data-vendor') === vendorId) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }
  </script>
@endsection