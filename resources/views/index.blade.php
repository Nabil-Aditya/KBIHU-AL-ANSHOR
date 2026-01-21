@extends('layouts.index')

@section('title', 'Kementerian Haji dan Umrah Kota Batam')

@section('content')

    <!-- Hero Section with Video Background -->
    <section class="hero" id="home">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="{{ asset('assets/vid/kaabah.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title-desktop">Kementerian Haji dan Umrah Kota Batam</h1>
        </div>
    </section>

    <!-- Recent Posts Section -->
    <section id="recent-posts" class="recent-posts section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Berita Terkini</h2>
            <p>Informasi terbaru seputar Kementerian Haji dan Umrah Kota Batam</p>
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

    <!-- Video Section -->
    <section id="video" class="video section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Video Terbaru</h2>
            <p>Video dokumentasi dan informasi seputar Kementerian Haji dan Umrah Kota Batam</p>
        </div>

        <div class="container">
            <div class="row gy-4">
                @forelse($videos as $video)
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="video-card">
                            <div class="video-thumbnail position-relative">
                                @if(!empty($video->thumbnail_url))
                                    <img src="{{ $video->thumbnail_url }}" class="img-fluid" alt="{{ $video->judul }}">
                                @else
                                    <div class="no-image-icon">
                                        <i class="bi bi-camera-video"></i>
                                    </div>
                                @endif

                                <div class="video-play-overlay">
                                    <button type="button" class="video-play-btn" onclick="playVideo({{ $video->id }})">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="video-info">
                                <h3 class="video-title">{{ $video->judul }}</h3>
                                <div class="video-date">
                                    <i class="bi bi-calendar3"></i>
                                    <span>{{ \Carbon\Carbon::parse($video->created_at)->format('d F Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-camera-video" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada video tersedia</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($videos->count() >= 3)
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('video.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-collection-play me-2"></i>Lihat Semua Video
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Foto Section -->
    <section id="foto" class="foto section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Galeri Foto</h2>
            <p>Dokumentasi kegiatan dan fasilitas Kementerian Haji dan Umrah Kota Batam</p>
        </div>

        <div class="container">
            <div class="row gy-4">
                @forelse($fotos as $foto)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="foto-card" onclick="showFotoModal({{ $foto->id }})">
                            <div class="foto-img">
                                @if(!empty($foto->foto) && file_exists(public_path('storage/' . $foto->foto)))
                                    <img src="{{ asset('storage/' . $foto->foto) }}" class="img-fluid" alt="{{ $foto->judul }}">
                                @else
                                    <div class="no-image-icon">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif

                                <div class="foto-overlay">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-images" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada foto tersedia</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($fotos->count() >= 6)
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('foto.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-images me-2"></i>Lihat Semua Foto
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Infografis Section -->
    <section id="infografis" class="infografis section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Infografis</h2>
            <p>Informasi visual seputar Kementerian Haji dan Umrah Kota Batam</p>
        </div>

        <div class="container">
            <div class="row gy-4">
                @forelse($infografis as $item)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="infografis-card" onclick="showInfografisModal({{ $item->id }})">
                            <div class="infografis-thumbnail">
                                @php
                                    $thumbnail = $item->files->first();
                                @endphp

                                @if($thumbnail && file_exists(public_path('storage/' . $thumbnail->file_path)))
                                    <img src="{{ asset('storage/' . $thumbnail->file_path) }}" class="img-fluid"
                                        alt="{{ $item->judul }}">

                                    @if($item->files->count() > 1)
                                        <span class="badge-pages">
                                            <i class="bi bi-images me-1"></i>{{ $item->files->count() }}
                                        </span>
                                    @endif
                                @else
                                    <div class="no-image-icon">
                                        <i class="bi bi-image"></i>
                                        <p>No Image</p>
                                    </div>
                                @endif

                                <div class="infografis-overlay">
                                    <i class="bi bi-zoom-in"></i>
                                    <p class="mt-2">Lihat Detail</p>
                                </div>
                            </div>
                            <div class="infografis-content">
                                <h4>{{ Str::limit($item->judul, 60) }}</h4>
                                <div class="infografis-date">
                                    <i class="bi bi-calendar3"></i>
                                    <span>{{ $item->created_at->format('d F Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-image" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada infografis tersedia</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($infografis->count() >= 3)
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('infografis.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-grid-3x3 me-2"></i>Lihat Semua Infografis
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div id="videoPlayerContainer">
                        <div class="ratio ratio-16x9">
                            <iframe id="youtubePlayer" src="" allowfullscreen style="display: none;"></iframe>
                            <video id="filePlayer" controls style="display: none;">
                                <source src="" type="video/mp4">
                                Browser Anda tidak mendukung video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Foto Modal -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center mb-3">
                        <img id="modalFotoImage" src="" class="img-fluid rounded" alt=""
                            style="max-height: 500px; object-fit: contain;">
                    </div>
                    <div class="text-center">
                        <h4 id="modalFotoJudul" class="fw-bold mb-2"></h4>
                        <div class="text-muted">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span id="modalFotoTanggal"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Infografis Modal - CUSTOM SLIDER (NO BOOTSTRAP CAROUSEL) -->
    <div class="modal fade" id="infografisModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header border-0 position-absolute top-0 end-0 z-3">
                    <button type="button" class="custom-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="custom-slider-container">
                        <div class="custom-slider-wrapper" id="customSliderWrapper">
                            <!-- Images will be loaded here -->
                        </div>

                        <!-- Navigation Buttons -->
                        <button class="custom-nav-btn custom-nav-prev" id="customPrevBtn">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="custom-nav-btn custom-nav-next" id="customNextBtn">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        <!-- Page Counter -->
                        <div class="custom-page-counter">
                            <span id="customCurrentPage">1</span> / <span id="customTotalPages">1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Custom Slider Variables
        let customSliderImages = [];
        let customCurrentIndex = 0;
        let customTouchStartX = 0;
        let customTouchEndX = 0;
        let customIsAnimating = false;

        // ===== VIDEO FUNCTIONS =====
        function playVideo(videoId) {
            const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
            const youtubePlayer = document.getElementById('youtubePlayer');
            const filePlayer = document.getElementById('filePlayer');

            youtubePlayer.style.display = 'none';
            filePlayer.style.display = 'none';
            youtubePlayer.src = '';
            filePlayer.src = '';

            fetch(`/api/video/${videoId}/embed`)
                .then(response => response.json())
                .then(data => {
                    if (data.video_type === 'youtube' && data.embed_url) {
                        youtubePlayer.src = data.embed_url;
                        youtubePlayer.style.display = 'block';
                    } else if (data.video_type === 'file' && data.video_url) {
                        filePlayer.src = `/storage/${data.video_url}`;
                        filePlayer.style.display = 'block';
                    }
                    videoModal.show();
                })
                .catch(error => {
                    console.error('Error fetching video:', error);
                    alert('Gagal memuat video. Silakan coba lagi.');
                });
        }

        // ===== FOTO FUNCTIONS =====
        function showFotoModal(fotoId) {
            const modal = new bootstrap.Modal(document.getElementById('fotoModal'));

            document.getElementById('modalFotoJudul').textContent = 'Loading...';
            document.getElementById('modalFotoTanggal').textContent = '';
            document.getElementById('modalFotoImage').src = '';

            fetch(`/api/foto/${fotoId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const fotoPath = data.foto_url || `/storage/${data.foto}`;
                    document.getElementById('modalFotoImage').src = fotoPath;
                    document.getElementById('modalFotoImage').alt = data.judul;
                    document.getElementById('modalFotoJudul').textContent = data.judul;
                    document.getElementById('modalFotoTanggal').textContent = data.created_at;
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching foto:', error);
                    alert('Gagal memuat foto. Silakan coba lagi.');
                });
        }

        // ===== CUSTOM INFOGRAFIS SLIDER FUNCTIONS =====
        function showInfografisModal(id) {
            fetch(`/api/infografis/${id}`)
                .then(response => response.json())
                .then(data => {
                    customSliderImages = data.files;
                    customCurrentIndex = 0;

                    renderCustomSlider();
                    updateCustomNavigation();

                    const modal = new bootstrap.Modal(document.getElementById('infografisModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat infografis');
                });
        }

        function renderCustomSlider() {
            const wrapper = document.getElementById('customSliderWrapper');
            wrapper.innerHTML = '';

            customSliderImages.forEach((file, index) => {
                const slide = document.createElement('div');
                slide.className = 'custom-slide';
                slide.style.display = index === customCurrentIndex ? 'flex' : 'none';
                slide.innerHTML = `
                                    <img src="${file.file_url}" 
                                         alt="Slide ${index + 1}"
                                         draggable="false">
                                `;
                wrapper.appendChild(slide);
            });

            updateCustomCounter();
        }

        function customGoToSlide(index) {
            if (customIsAnimating) return;

            const slides = document.querySelectorAll('.custom-slide');
            if (slides.length === 0) return;

            // Normalize index
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;

            customIsAnimating = true;

            // Hide current slide
            slides[customCurrentIndex].style.display = 'none';

            // Show new slide
            customCurrentIndex = index;
            slides[customCurrentIndex].style.display = 'flex';

            updateCustomCounter();
            updateCustomNavigation();

            setTimeout(() => {
                customIsAnimating = false;
            }, 300);
        }

        function customNextSlide() {
            customGoToSlide(customCurrentIndex + 1);
        }

        function customPrevSlide() {
            customGoToSlide(customCurrentIndex - 1);
        }

        function updateCustomCounter() {
            document.getElementById('customCurrentPage').textContent = customCurrentIndex + 1;
            document.getElementById('customTotalPages').textContent = customSliderImages.length;
        }

        function updateCustomNavigation() {
            const prevBtn = document.getElementById('customPrevBtn');
            const nextBtn = document.getElementById('customNextBtn');

            // Always show buttons for circular navigation
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        }

        // Touch/Swipe Handlers
        function handleCustomTouchStart(e) {
            customTouchStartX = e.touches ? e.touches[0].clientX : e.clientX;
        }

        function handleCustomTouchEnd(e) {
            customTouchEndX = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
            handleCustomSwipe();
        }

        function handleCustomSwipe() {
            const swipeThreshold = 50;
            const diff = customTouchStartX - customTouchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    customNextSlide();
                } else {
                    customPrevSlide();
                }
            }
        }

        // ===== DOM LOADED EVENT =====
        document.addEventListener('DOMContentLoaded', function () {
            // Video Modal Cleanup
            const videoModalEl = document.getElementById('videoModal');
            if (videoModalEl) {
                videoModalEl.addEventListener('hidden.bs.modal', function () {
                    const youtubePlayer = document.getElementById('youtubePlayer');
                    const filePlayer = document.getElementById('filePlayer');

                    youtubePlayer.src = '';
                    youtubePlayer.style.display = 'none';
                    filePlayer.pause();
                    filePlayer.src = '';
                    filePlayer.load();
                    filePlayer.style.display = 'none';

                    cleanupBackdrop();
                });

                videoModalEl.addEventListener('hide.bs.modal', function () {
                    const filePlayer = document.getElementById('filePlayer');
                    if (filePlayer.src) filePlayer.pause();
                });
            }

            // Foto Modal Cleanup
            const fotoModalEl = document.getElementById('fotoModal');
            if (fotoModalEl) {
                fotoModalEl.addEventListener('hidden.bs.modal', function () {
                    document.getElementById('modalFotoImage').src = '';
                    document.getElementById('modalFotoJudul').textContent = '';
                    document.getElementById('modalFotoTanggal').textContent = '';
                    cleanupBackdrop();
                });
            }

            // Custom Slider Navigation Buttons
            document.getElementById('customPrevBtn').addEventListener('click', customPrevSlide);
            document.getElementById('customNextBtn').addEventListener('click', customNextSlide);

            // Custom Slider Touch Events
            const sliderWrapper = document.getElementById('customSliderWrapper');
            sliderWrapper.addEventListener('touchstart', handleCustomTouchStart, { passive: true });
            sliderWrapper.addEventListener('touchend', handleCustomTouchEnd, { passive: true });
            sliderWrapper.addEventListener('mousedown', handleCustomTouchStart);
            sliderWrapper.addEventListener('mouseup', handleCustomTouchEnd);

            // Infografis Modal Cleanup
            const infografisModalEl = document.getElementById('infografisModal');
            if (infografisModalEl) {
                infografisModalEl.addEventListener('hidden.bs.modal', function () {
                    customSliderImages = [];
                    customCurrentIndex = 0;
                    document.getElementById('customSliderWrapper').innerHTML = '';
                    cleanupBackdrop();
                });
            }

            // Video Card Click Handler
            document.querySelectorAll('.video-card').forEach(card => {
                card.addEventListener('click', function (e) {
                    if (document.querySelector('.modal.show')) return;
                    const playButton = this.querySelector('[onclick*="playVideo"]');
                    if (playButton) {
                        const onclickAttr = playButton.getAttribute('onclick');
                        const videoId = onclickAttr?.match(/\d+/)?.[0];
                        if (videoId) playVideo(videoId);
                    }
                });
            });
        });

        // Cleanup Backdrop Helper
        function cleanupBackdrop() {
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 100);
        }

        // ESC Key Handler
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modals = ['videoModal', 'fotoModal', 'infografisModal'];
                modals.forEach(modalId => {
                    const modalEl = document.getElementById(modalId);
                    if (modalEl && modalEl.classList.contains('show')) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                });
            }
        });

        // Keyboard Navigation for Custom Slider
        document.addEventListener('keydown', function (e) {
            const infografisModal = document.getElementById('infografisModal');
            if (infografisModal && infografisModal.classList.contains('show') && !customIsAnimating) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    customPrevSlide();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    customNextSlide();
                }
            }
        });
    </script>
@endpush