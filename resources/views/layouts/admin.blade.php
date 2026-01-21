<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard')</title>
  
  <link href="{{ asset('assets/img/kemenhaj.png') }}" rel="icon">
  <link rel="stylesheet" href="{{ asset('assets2/css/styles.min.css') }}">
  
  @stack('styles')
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Include Sidebar -->
    @include('components.sidebar')
    
    <!-- Main wrapper -->
    <div class="body-wrapper">
      
      <!-- Include Header -->
      @include('components.header')
      
      <!-- Content -->
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
  
  <script src="{{ asset('assets2/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets2/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets2/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('assets2/js/app.min.js') }}"></script>
  <script src="{{ asset('assets2/libs/simplebar/dist/simplebar.js') }}"></script>
  
  @stack('scripts')
</body>
</html>