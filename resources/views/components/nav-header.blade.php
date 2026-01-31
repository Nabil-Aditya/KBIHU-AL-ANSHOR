<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
            <img src="{{ asset('assets/img/no-bg-logo.png') }}" alt="Logo KBIHU Al-ANSHOR" class="logo-img"
                style="max-height: 50px;">
            <div class="logo-text">
                <h1>
                    <span class="logo-text-full">KBIHU Al-ANSHOR</span>
                    <span class="logo-text-short">AL-ANSHOR</span>
                </h1>
                <span class="subtitle" style="color: #866749; font-size: 1.0625rem;">Kota Batam</span>
            </div>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ Request::routeIs('home') ? 'active' : '' }}">Beranda</a>
                </li>
                <li class="dropdown"><a href="#"><span>Profil</span> <i
                            class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ route('profil.struktur-organisasi') }}">Struktur Org</a></li>
                        <li><a href="{{ route('profil.tugas-fungsi') }}">Tugas & Fungsi</a></li>
                        <li><a href="{{ route('profil.visi-misi') }}">Visi & Misi</a></li>
                        <li><a href="{{ route('profil.latar-belakang') }}">Latar Belakang</a></li>
                    </ul>
                </li>
                <li><a href="#">Layanan</a></li>
                <li><a href="{{ route('doa.all') }}" class="{{ Request::routeIs('doa.*') ? 'active' : '' }}">Doa</a>
                </li>
                <li>
                    <a href="{{ route('berita.all') }}" class="{{ Request::routeIs('berita.*') ? 'active' : '' }}">
                        Berita
                    </a>
                </li>
                <li>
                    <a href="{{ route('video.all') }}" class="{{ Request::routeIs('video.*') ? 'active' : '' }}">
                        Video
                    </a>
                </li>
                <li>
                    <a href="{{ route('galeri.all') }}" class="{{ Request::routeIs('galeri.*') ? 'active' : '' }}">
                        Galeri Foto
                    </a>
                </li>
                <li>
                    <a href="{{ route('infografis.index') }}"
                        class="{{ Request::routeIs('infografis.*') ? 'active' : '' }}">
                        Infografis
                    </a>
                </li>
                <li><a href="#">Daftar</a></li>
                <a class="btn-getstarted flex-md-shrink-0" href="/login">Masuk!</a>

            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

    </div>
</header>