<!--  Header Start -->
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link nav-icon-hover" href="javascript:void(0)">
          <i class="ti ti-bell-ringing"></i>
          <div class="notification bg-primary rounded-circle"></div>
        </a>
      </li>
    </ul>
    
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        
        <!-- Profile Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('assets2/images/profile/user-1.jpg') }}" alt="User Profile" width="35" height="35" class="rounded-circle">
          </a>
          
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2" style="min-width: 250px;">
            <div class="message-body p-2">
              <!-- Profile Header -->
              <div class="px-3 py-2 mb-2 border-bottom">
                <small class="text-muted">{{ ucfirst(string: Auth::user()->name) }}</small>
              </div>
              
              <!-- Menu Items -->
              <a href="javascript:void(0)" class="dropdown-item d-flex align-items-center gap-2 py-2 px-3">
                <i class="ti ti-user fs-5"></i>
                <span>My Profile</span>
              </a>
              <a href="javascript:void(0)" class="dropdown-item d-flex align-items-center gap-2 py-2 px-3">
                <i class="ti ti-mail fs-5"></i>
                <span>My Account</span>
              </a>
              <a href="javascript:void(0)" class="dropdown-item d-flex align-items-center gap-2 py-2 px-3">
                <i class="ti ti-list-check fs-5"></i>
                <span>My Task</span>
              </a>
              
              <div class="dropdown-divider my-2"></div>
              
              <!-- Logout Form -->
              <form method="POST" action="{{ route('logout') }}" class="px-2">
                @csrf
                <button type="submit" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                  <i class="ti ti-power fs-5"></i>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!--  Header End -->