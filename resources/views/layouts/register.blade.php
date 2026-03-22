<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register - Purple Admin</title>
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <img src="{{ asset('assets/images/logo.svg') }}">
              </div>
              <h4>New here?</h4>
              <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>

              <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                  <input type="text" name="name"
                    class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Username"
                    value="{{ old('name') }}" required autofocus>
                  @error('name')
                    <span class="text-danger small">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <input type="email" name="email"
                    class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email"
                    value="{{ old('email') }}" required>
                  @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <input type="password" name="password"
                    class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password"
                    required>
                  @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <input type="password" name="password_confirmation" class="form-control form-control-lg"
                    placeholder="Confirm Password" required>
                </div>

                <div class="mb-4">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" required> I agree to all Terms & Conditions
                    </label>
                  </div>
                </div>

                <div class="mt-3 d-grid gap-2">
                  <button type="submit" id="btnRegister"
                    class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                    <span id="btnText">SIGN UP</span>
                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                  </button>
                </div>

                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/misc.js') }}"></script>
</body>

</html>