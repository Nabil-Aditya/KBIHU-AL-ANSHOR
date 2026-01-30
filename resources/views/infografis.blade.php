@extends('layouts.layout-pages')

@section('content')

<main class="main">

    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8 mt-5">
                        <h1>Infografis</h1>
                        <p class="mb-0">Informasi visual yang informatif dan menarik seputar Kbihu Al-Anshor dalam membimbing jamaah haji dan umrah.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Infografis</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Page Title -->

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="row justify-content-center">
                {{-- Search Widget for Infografis --}}
                <div class="search-widget widget-item mt-2">
                    <form action="{{ route('infografis.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Cari infografis..." value="{{ request('search') }}">
                        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section><!-- End Filter Section -->

    <!-- Infografis Section -->
    <section id="infografis" class="infografis section">
        <div class="container">
            <!-- Result Info -->
            @if(request('search'))
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <p class="text-muted">
                            Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                            @if($infografis->total() > 0)
                                - Ditemukan {{ $infografis->total() }} infografis
                            @endif
                        </p>
                        <a href="{{ route('infografis.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Hapus Pencarian
                        </a>
                    </div>
                </div>
            @endif

            <div class="row gy-4">
                @forelse($infografis as $item)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
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
                                        <i class="bi bi-file-earmark-image"></i>
                                    </div>
                                @endif

                                <div class="infografis-overlay">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                            </div>
                            
                            {{-- Tambahkan Content Section --}}
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
                            <p class="text-muted mt-3">
                                @if(request('search'))
                                    Tidak ada infografis ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada infografis tersedia
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($infografis->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            {{ $infografis->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </section>

</main>

<!-- Infografis Modal - CUSTOM SLIDER -->
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

        // Normalize index (circular navigation)
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
    document.addEventListener('DOMContentLoaded', function() {
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
            infografisModalEl.addEventListener('hidden.bs.modal', function() {
                customSliderImages = [];
                customCurrentIndex = 0;
                document.getElementById('customSliderWrapper').innerHTML = '';
                cleanupBackdrop();
            });
        }
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
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modalEl = document.getElementById('infografisModal');
            if (modalEl && modalEl.classList.contains('show')) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
        }
    });

    // Keyboard Navigation for Custom Slider
    document.addEventListener('keydown', function(e) {
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