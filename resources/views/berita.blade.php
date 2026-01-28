@extends('layouts.doa-viewer')

@section('content')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8 mt-5">
              <h1>Berita</h1>
              <p class="mb-0">Temukan berbagai informasi dan berita terbaru seputar Kbihu Al-Anshor. Tetap update dengan
                berita dan kegiatan terkini kami.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="current">Berita</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Filter Section -->
    <section class="filter-section">
      <div class="container">
        <div class="row justify-content-center mb-4">
          {{-- Search Widget for News --}}
          <div class="search-widget widget-item mt-2">
            <form action="{{ route('berita.all') }}" method="GET">
              <input type="text" name="search" placeholder="Cari berita..." value="{{ request('search') }}">
              <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
          </div>

          <!-- Recent Posts Section -->
          <section id="recent-posts" class="recent-posts section">
            <div class="container">
              <!-- Result Info -->
              @if(request('search'))
                <div class="row">
                  <div class="col-12 text-center mb-4">
                    <p class="text-muted">
                      Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                      @if($beritas->total() > 0)
                        - Ditemukan {{ $beritas->total() }} berita
                      @endif
                    </p>
                    <a href="{{ route('berita.all') }}" class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-x-circle"></i> Hapus Pencarian
                    </a>
                  </div>
                </div>
              @endif

              <div class="row gy-5">
                @forelse($beritas as $berita)
                  <div class="col-xl-4 col-md-6">
                    <div class="post-item position-relative h-100" data-aos="fade-up"
                      data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
                      <div class="post-img position-relative overflow-hidden">
                        <a href="{{ route('berita.show', $berita->slug) }}">
                          @if(!empty($berita->gambar) && file_exists(public_path('storage/' . $berita->gambar)))
                            <img src="{{ asset('storage/' . $berita->gambar) }}" class="img-fluid" alt="{{ $berita->judul }}">
                          @else
                            <div class="no-image-icon">
                              <i class="bi bi-image"></i>
                            </div>
                          @endif
                        </a>

                        <span class="post-date">
                          {{ \Carbon\Carbon::parse($berita->tanggal)->format('F d') }}
                        </span>
                      </div>
                      <div class="post-content d-flex flex-column">
                        <h3 class="post-title">
                          <a href="{{ route('berita.show', $berita->slug) }}">
                            {{ Str::limit($berita->judul, 60) }}
                          </a>
                        </h3>
                        <div class="meta d-flex align-items-center">
                          <div class="d-flex align-items-center">
                            <i class="bi bi-person"></i> <span class="ps-2">{{ $berita->penulis }}</span>
                          </div>
                          <span class="px-3 text-black-50">/</span>
                          <div class="d-flex align-items-center">
                            <i class="bi bi-folder2"></i> <span class="ps-2">{{ ucfirst($berita->kategori) }}</span>
                          </div>
                        </div>

                        @if($berita->excerpt)
                          <p class="mt-3">{{ Str::limit($berita->excerpt, 100) }}</p>
                        @endif

                        <hr>
                        <a href="{{ route('berita.show', $berita->slug) }}" class="readmore stretched-link">
                          <span>Selengkapnya</span><i class="bi bi-arrow-right"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="col-12">
                    <div class="text-center py-5">
                      <i class="bi bi-newspaper" style="font-size: 3rem; color: #ccc;"></i>
                      <p class="text-muted mt-3">
                        @if(request('search'))
                          Tidak ada berita ditemukan untuk pencarian "{{ request('search') }}"
                        @else
                          Belum ada berita tersedia
                        @endif
                      </p>
                    </div>
                  </div>
                @endforelse
              </div>

              <!-- Pagination -->
              @if($beritas->hasPages())
                <div class="row mt-5">
                  <div class="col-12">
                    <nav aria-label="Page navigation">
                      {{ $beritas->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                  </div>
                </div>
              @endif
            </div>
          </section>

  </main>

@endsection

@push('styles')
  <style>
    .filter-section {
      padding: 30px 0;
      background-color: #f8f9fa;
    }

    .search-widget {
      max-width: 600px;
      margin: 0 auto;
    }

    .search-widget form {
      display: flex;
      gap: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    .search-widget input[type="text"] {
      flex: 1;
      padding: 12px 20px;
      border: none;
      font-size: 16px;
    }

    .search-widget input[type="text"]:focus {
      outline: none;
    }

    .search-widget button {
      padding: 12px 24px;
      background-color: #3498db;
      border: none;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .search-widget button:hover {
      background-color: #2980b9;
    }

    .no-image-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 250px;
      background-color: #e9ecef;
      color: #6c757d;
      font-size: 4rem;
    }

    .post-item {
      transition: transform 0.3s ease;
    }

    .post-item:hover {
      transform: translateY(-5px);
    }

    .post-img img {
      transition: transform 0.3s ease;
      height: 250px;
      object-fit: cover;
      width: 100%;
    }

    .post-item:hover .post-img img {
      transform: scale(1.05);
    }

    .post-title a {
      color: #2c3e50;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .post-title a:hover {
      color: #3498db;
    }
  </style>
@endpush