<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Internal - Login</title>

  <link rel="stylesheet" href="{{ asset('assets2/css/styles.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/styles-login.css') }}">
</head>

<body>
  <div class="page-wrapper" id="main-wrapper"
       data-layout="vertical"
       data-navbarbg="skin6"
       data-sidebartype="full"
       data-sidebar-position="fixed"
       data-header-position="fixed">

    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">

                <div class="text-center py-3">
                  <img src="{{ asset('assets/img/icon-logo.png') }}"
                       width="120"
                       alt="Logo Kemenag">
                </div>

                <p class="text-center mb-4 login-title">
                  Sistem Internal
                </p>

                @if (session('error'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif

                @if (session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                  @csrf

                  <div class="mb-3">
                    <label for="username" class="form-label">Username atau Email</label>
                    <input type="text" 
                           class="form-control @error('username') is-invalid @enderror" 
                           id="username"
                           name="username"
                           value="{{ old('username') }}"
                           required
                           autofocus>
                    @error('username')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password"
                           name="password"
                           required>
                    @error('password')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <div class="mb-4 form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="remember"
                           name="remember">
                    <label class="form-check-label" for="remember">
                      Remember Me
                    </label>
                  </div>

                  <button type="submit"
                          class="btn btn-primary w-100 py-2 fs-5 rounded-2">
                    <i class="ti ti-login me-2"></i>
                    Sign In
                  </button>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets2/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets2/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>