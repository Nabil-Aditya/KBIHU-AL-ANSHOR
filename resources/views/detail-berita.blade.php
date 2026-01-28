@extends('layouts.index')

@section('title', $berita->judul . ' - Kementerian Haji dan Umrah Kota Batam')

@section('content')

    <main class="main">

        <!-- Page Title -->
        <div class="page-title">
            <div class="heading">
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('berita.index') }}">Berita</a></li>
                        <li class="current">{{ Str::limit($berita->judul, 50) }}</li>
                    </ol>
                </div>
            </nav>
        </div><!-- End Page Title -->

        <div class="container">
            <div class="row">

                <div class="col-lg-8">

                    <!-- Blog Details Section -->
                    <section id="blog-details" class="blog-details section">
                        <div class="container">

                            <article class="article">

                                <div class="post-img">
                                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}"
                                        class="img-fluid" onerror="this.src='{{ asset('assets/img/blog/blog-1.jpg') }}'">
                                </div>

                                <h2 class="title">{{ $berita->judul }}</h2>

                                <div class="meta-top">
                                    <ul>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-person"></i>
                                            <a href="#">{{ $berita->penulis }}</a>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-clock"></i>
                                            <a href="#">
                                                <time datetime="{{ $berita->tanggal }}">
                                                    {{ \Carbon\Carbon::parse($berita->tanggal)->format('M d, Y') }}
                                                </time>
                                            </a>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-folder2"></i>
                                            <a href="{{ route('berita.kategori', $berita->kategori) }}">
                                                {{ ucfirst($berita->kategori) }}
                                            </a>
                                        </li>
                                    </ul>
                                </div><!-- End meta top -->

                                <div class="content">


                                    <div style="line-height: 1.8; text-align: justify;">
                                        {!! nl2br(e($berita->konten)) !!}
                                    </div>

                                </div><!-- End post content -->

                                <div class="meta-bottom mt-4">
                                    <i class="bi bi-folder"></i>
                                    <ul class="cats">
                                        <li>
                                            <a href="{{ route('berita.kategori', $berita->kategori) }}">
                                                {{ ucfirst($berita->kategori) }}
                                            </a>
                                        </li>
                                    </ul>

                                    <i class="bi bi-share"></i>
                                    <span class="ms-2">Bagikan:</span>
                                    <div class="d-inline-flex gap-2 ms-2">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                            target="_blank" title="Share on Facebook">
                                            <i class="bi bi-facebook"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}"
                                            target="_blank" title="Share on Twitter">
                                            <i class="bi bi-twitter-x"></i>
                                        </a>
                                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}"
                                            target="_blank" title="Share on WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div><!-- End meta bottom -->

                            </article>

                        </div>
                    </section><!-- /Blog Details Section -->

                    <!-- Related Posts Section -->
                    @if($relatedNews->count() > 0)
                        <section id="blog-posts-2" class="recent-posts section">
                            <div class="container">
                                <div class="section-title" data-aos="fade-up">
                                    <h2>Berita Terkait</h2>
                                    <p>Berita lainnya dalam kategori {{ ucfirst($berita->kategori) }}</p>
                                </div>

                                <div class="row gy-4">
                                    @foreach($relatedNews as $related)
                                        <div class="col-lg-4 col-md-6" data-aos="fade-up"
                                            data-aos-delay="{{ $loop->iteration * 100 }}">
                                            <div class="post-item position-relative h-100">
                                                <div class="post-img position-relative overflow-hidden">
                                                    <img src="{{ asset('storage/' . $related->gambar) }}" class="img-fluid"
                                                        alt="{{ $related->judul }}"
                                                        onerror="this.src='{{ asset('assets/img/blog/blog-1.jpg') }}'">
                                                    <span
                                                        class="post-date">{{ \Carbon\Carbon::parse($related->tanggal)->format('F d') }}</span>
                                                </div>

                                                <div class="post-content d-flex flex-column">
                                                    <h3 class="post-title">{{ Str::limit($related->judul, 50) }}</h3>

                                                    <div class="meta d-flex align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person"></i>
                                                            <span class="ps-2">{{ $related->penulis }}</span>
                                                        </div>
                                                        <span class="px-3 text-black-50">/</span>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-folder2"></i>
                                                            <span class="ps-2">{{ ucfirst($related->kategori) }}</span>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <a href="{{ route('berita.show', $related->slug) }}"
                                                        class="readmore stretched-link">
                                                        <span>Selengkapnya</span><i class="bi bi-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endif

                </div>

                <div class="col-lg-4 sidebar">

                    <div class="widgets-container">

                        <!-- Search Widget -->
                        <div class="search-widget widget-item">
                            <h3 class="widget-title">Pencarian</h3>
                            <form action="{{ route('berita.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Cari berita...">
                                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                            </form>
                        </div><!--/Search Widget -->

                        <!-- Categories Widget -->
                        <div class="categories-widget widget-item">
                            <h3 class="widget-title">Kategori</h3>
                            <ul class="mt-3">
                                <li>
                                    <a href="{{ route('berita.kategori', 'kegiatan') }}">
                                        Kegiatan
                                        <span>({{ \App\Models\Berita::where('kategori', 'kegiatan')->where('status', 'published')->count() }})</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('berita.kategori', 'pengumuman') }}">
                                        Pengumuman
                                        <span>({{ \App\Models\Berita::where('kategori', 'pengumuman')->where('status', 'published')->count() }})</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('berita.kategori', 'artikel') }}">
                                        Artikel
                                        <span>({{ \App\Models\Berita::where('kategori', 'artikel')->where('status', 'published')->count() }})</span>
                                    </a>
                                </li>
                            </ul>
                        </div><!--/Categories Widget -->

                        <!-- Recent Posts Widget -->
                        @if($latestNews->count() > 0)
                            <div class="recent-posts-widget widget-item">
                                <h3 class="widget-title">Berita Terbaru</h3>

                                @foreach($latestNews as $latest)
                                    <div class="post-item">
                                        <img src="{{ asset('storage/' . $latest->gambar) }}" alt="{{ $latest->judul }}"
                                            class="flex-shrink-0"
                                            onerror="this.src='{{ asset('assets/img/blog/blog-recent-1.jpg') }}'">
                                        <div>
                                            <h4>
                                                <a href="{{ route('berita.show', $latest->slug) }}">
                                                    {{ Str::limit($latest->judul, 50) }}
                                                </a>
                                            </h4>
                                            <time datetime="{{ $latest->tanggal }}">
                                                {{ \Carbon\Carbon::parse($latest->tanggal)->format('M d, Y') }}
                                            </time>
                                        </div>
                                    </div><!-- End recent post item-->
                                @endforeach

                            </div><!--/Recent Posts Widget -->
                        @endif

                        <!-- Back to All News Widget -->
                        <div class="widget-item text-center">
                            <a href="{{ route('berita.index') }}" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-left me-2"></i> Semua Berita
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </main>

@endsection

@push('styles')
    <style>
        .article .content {
            font-size: 16px;
            line-height: 1.8;
            color: #4a4a4a;
        }

        .meta-bottom {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }

        .meta-bottom i {
            font-size: 18px;
            margin-right: 5px;
        }

        .meta-bottom a {
            color: #4154f1;
            transition: 0.3s;
        }

        .meta-bottom a:hover {
            color: #5969f3;
        }
    </style>
@endpush