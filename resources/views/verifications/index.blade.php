@extends('layouts.auth')

@section('content')

<div class="brand-logo">
    <img src="{{ asset('assets/images/logo.svg') }}">
</div>

<h4>Verifikasi OTP</h4>
<h6 class="font-weight-light">
    Masukkan kode 6 digit yang dikirim ke email Anda.
</h6>

<form class="pt-3" method="POST" action="{{ route('verify.otp') }}">
    @csrf

    <div class="form-group">
        <input type="text" name="otp"
            class="form-control form-control-lg text-center font-weight-bold"
            placeholder="000000" maxlength="6" required>
    </div>

    <div class="mt-3 d-grid gap-2">
        <button type="submit"
            class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
            VERIFIKASI
        </button>
    </div>

</form>

@endsection