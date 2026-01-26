@extends('layouts.index')

@section('content')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1>Doa Haji & Umrah</h1>
              <p class="mb-0">Doa adalah penguat hati dan teman perjalanan menuju rumah Allah. Semoga rangkaian doa yang kami sajikan membantu jamaah menjalani ibadah haji dan umrah dengan penuh ketenangan, keikhlasan, dan keberkahan.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Starter Page</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Recent Posts Section -->
    <section id="recent-posts" class="recent-posts section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Berita Terkini</h2>
            <p>Informasi terbaru seputar Kbihu Al-Anshor</p>
        </div>

        <div class="container">
            <div class="row gy-5">
                @forelse($beritas as $berita)
                    <div class="col-xl-4 col-md-6">
                        <div class="post-item position-relative h-100" data-aos="fade-up"
                            data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="post-img position-relative overflow-hidden">
                                @if(!empty($berita->gambar) && file_exists(public_path('storage/' . $berita->gambar)))
                                    <img src="{{ asset('storage/' . $berita->gambar) }}" class="img-fluid"
                                        alt="{{ $berita->judul }}">
                                @else
                                    <div class="no-image-icon">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif

                                <span class="post-date">
                                    {{ \Carbon\Carbon::parse($berita->tanggal)->format('F d') }}
                                </span>
                            </div>
                            <div class="post-content d-flex flex-column">
                                <h3 class="post-title">{{ Str::limit($berita->judul, 60) }}</h3>
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
                            <p class="text-muted mt-3">Belum ada berita tersedia</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($beritas->count() >= 6)
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('berita.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Lihat Semua Berita
                    </a>
                </div>
            @endif
        </div>
    </section>

  </main>

@endsection
