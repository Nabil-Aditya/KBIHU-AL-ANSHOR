<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
 <div class="brand-logo d-flex align-items-center justify-content-center position-relative">

  <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img mx-auto">
    <img src="{{ asset('assets/img/AlAnshor.jpg') }}" width="170" alt="Logo Kemenag"/>
  </a>

  <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer ms-auto" id="sidebarCollapse">
    <i class="ti ti-x fs-8"></i>
  </div>

</div>

    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">

        <!-- Home Section -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Home</span>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
            <span><i class="ti ti-layout-dashboard"></i></span>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <!-- Management Section -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Management</span>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.berita.index') }}" aria-expanded="false">
            <span><i class="ti ti-news"></i></span>
            <span class="hide-menu">Kelola Berita</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.video.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.video.index') }}" aria-expanded="false">
            <span><i class="ti ti-video"></i></span>
            <span class="hide-menu">Kelola Video</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.foto.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.foto.index') }}" aria-expanded="false">
            <span><i class="ti ti-photo"></i></span>
            <span class="hide-menu">Kelola Foto</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.infografis.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.infografis.index') }}" aria-expanded="false">
            <span><i class="ti ti-chart-infographic"></i></span>
            <span class="hide-menu">Kelola Infografis</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.doa.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.doa.index') }}" aria-expanded="false">
            <span><i class="ti ti-book"></i></span>
            <span class="hide-menu">Kelola Doa</span>
          </a>
        </li>


        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Jamaah</span>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.jamaah.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.jamaah.index') }}" aria-expanded="false">
            <span><i class="ti ti-news"></i></span>
            <span class="hide-menu">Kelola Jamaah</span>
          </a>
        </li>
        {{-- <li class="sidebar-item {{ request()->routeIs('admin.video.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.video.index') }}" aria-expanded="false">
            <span><i class="ti ti-video"></i></span>
            <span class="hide-menu">Kelola Video</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.foto.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.foto.index') }}" aria-expanded="false">
            <span><i class="ti ti-photo"></i></span>
            <span class="hide-menu">Kelola Foto</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.infografis.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.infografis.index') }}" aria-expanded="false">
            <span><i class="ti ti-chart-infographic"></i></span>
            <span class="hide-menu">Kelola Infografis</span>
          </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('admin.doa.*') ? 'active' : '' }}">
          <a class="sidebar-link" href="{{ route('admin.doa.index') }}" aria-expanded="false">
            <span><i class="ti ti-book"></i></span>
            <span class="hide-menu">Kelola Doa</span>
          </a>
        </li> --}}


        <!-- UI Components Section -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">UI COMPONENTS</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-article"></i></span>
            <span class="hide-menu">Buttons</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-alert-circle"></i></span>
            <span class="hide-menu">Alerts</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-cards"></i></span>
            <span class="hide-menu">Card</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-file-description"></i></span>
            <span class="hide-menu">Forms</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-typography"></i></span>
            <span class="hide-menu">Typography</span>
          </a>
        </li>

        <!-- Extra Section -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">EXTRA</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-mood-happy"></i></span>
            <span class="hide-menu">Icons</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#" aria-expanded="false">
            <span><i class="ti ti-aperture"></i></span>
            <span class="hide-menu">Sample Page</span>
          </a>
        </li>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->