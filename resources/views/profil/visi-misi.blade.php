@extends('layouts.layout-pages')

@section('content')
<main class="main">
    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8 mt-5">
                        <h1>Visi & Misi</h1>
                        <p class="mb-0">Visi dan misi KBIHU Al-Anshor menjadi landasan dalam memberikan bimbingan, pendampingan, serta pelayanan terbaik bagi jamaah haji dan umrah secara amanah dan berkelanjutan.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Visi & Misi</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Page Title -->

    <!-- Visi Section -->
    <section class="visi-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="fw-bold mb-4">Visi Kami</h2>
                    <p class="lead text-muted mb-4">Arah dan tujuan utama yang menjadi pedoman dalam setiap langkah kami</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="zoom-in">
                    <div class="visi-card p-5 rounded-4 shadow-lg bg-white position-relative overflow-hidden">
                        <div class="visi-icon position-absolute" style="top: -20px; right: -20px; opacity: 0.1;">
                            <i class="bi bi-bullseye" style="font-size: 10rem; color: #866749;"></i>
                        </div>
                        <div class="position-relative z-1">
                            <blockquote class="blockquote text-center">
                                <h5 class="display-5 fw-bold text-dark mb-4">
                                    "Menjadi lembaga bimbingan ibadah haji dan umrah yang profesional, amanah, dan istiqamah dalam membimbing jamaah menuju haji dan umrah mabrur."
                                </h5>
                            </blockquote>
                            <div class="mt-5 pt-4 border-top">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="feature-box p-3">
                                            <i class="bi bi-award-fill h2 text-success mb-3"></i>
                                            <h5>Profesional</h5>
                                            <p class="small text-muted">Berkompetensi tinggi dan bersertifikasi</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="feature-box p-3">
                                            <i class="bi bi-shield-check h2 text-success mb-3"></i>
                                            <h5>Amanah</h5>
                                            <p class="small text-muted">Terpercaya dan bertanggung jawab</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="feature-box p-3">
                                            <i class="bi bi-compass h2 text-success mb-3"></i>
                                            <h5>Istiqamah</h5>
                                            <p class="small text-muted">Konsisten dalam kebaikan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Misi Section -->
    <section class="misi-section py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="fw-bold mb-4">Misi Kami</h2>
                    <p class="lead text-muted mb-4">Langkah-langkah strategis untuk mewujudkan visi yang telah ditetapkan</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach([
                    [
                        'icon' => 'bi-book',
                        'title' => 'Bimbingan Berbasis Syariah',
                        'description' => 'Memberikan bimbingan manasik haji dan umrah yang sesuai dengan Al-Qur\'an dan Sunnah.',
                        'color' => 'primary'
                    ],
                    [
                        'icon' => 'bi-person-fill-check',
                        'title' => 'Tim Berkompetensi',
                        'description' => 'Menyediakan pembimbing yang berkompeten, berpengalaman, dan bersertifikat.',
                        'color' => 'success'
                    ],
                    [
                        'icon' => 'bi-star-fill',
                        'title' => 'Sistem Pelayanan Terbaik',
                        'description' => 'Membangun sistem pelayanan yang amanah, transparan, dan berorientasi pada kenyamanan jamaah.',
                        'color' => 'warning'
                    ],
                    [
                        'icon' => 'bi-share-fill ',
                        'title' => 'Jaringan Kerja Sama',
                        'description' => 'Menjalin kerja sama dengan berbagai pihak untuk meningkatkan kualitas layanan.',
                        'color' => 'info'
                    ],
                    [
                        'icon' => 'bi-heart',
                        'title' => 'Pembinaan Ukhuwah',
                        'description' => 'Membina ukhuwah Islamiyah di kalangan jamaah sebelum, selama, dan setelah ibadah.',
                        'color' => 'danger'
                    ]
                ] as $index => $misi)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="misi-card h-100 p-4 rounded-4 shadow-sm border-0 bg-white position-relative overflow-hidden">
                            <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.05;">
                                <i class="bi {{ $misi['icon'] }}" style="font-size: 6rem;"></i>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-{{ $misi['color'] }} bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="bi {{ $misi['icon'] }} h3 text-{{ $misi['color'] }}"></i>
                                </div>
                                <span class="badge bg-{{ $misi['color'] }} py-1 px-2 align-self-start">{{ $index + 1 }}</span>
                            </div>
                            <h4 class="fw-bold mb-3">{{ $misi['title'] }}</h4>
                            <p class="text-muted mb-0">{{ $misi['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</main>
@endsection

@push('styles')
<style>
    /* Custom Styles for Visi Misi Page */
    :root {
        --primary-color: #866749;
        --secondary-color: #866749;
        --gradient-primary: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    }

    .visi-misi-hero {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .hero-image {
        position: relative;
    }

    .hero-image::after {
        content: '';
        position: absolute;
        bottom: -20px;
        right: -20px;
        width: 100%;
        height: 100%;
        background: var(--gradient-primary);
        border-radius: 10px;
        z-index: -1;
        opacity: 0.8;
    }

    .visi-card, .misi-card, .value-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .visi-card:hover, .misi-card:hover, .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .bg-gradient-primary {
        background: var(--gradient-primary) !important;
    }

    .icon-circle {
        transition: transform 0.3s ease;
    }

    .value-card:hover .icon-circle {
        transform: rotate(15deg) scale(1.1);
    }

    .icon-wrapper {
        transition: all 0.3s ease;
    }

    .misi-card:hover .icon-wrapper {
        transform: scale(1.1);
    }

    .cta-section {
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .stats {
        flex-wrap: wrap;
    }

    .stat-item {
        flex: 1;
        min-width: 100px;
    }

    .visi-icon {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @media (max-width: 768px) {
        .hero-image::after {
            display: none;
        }
        
        .stats {
            justify-content: center;
            gap: 20px;
        }
        
        .stat-item {
            min-width: 80px;
        }
        
        .visi-card, .misi-card {
            padding: 20px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation on scroll initialization
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        }

        // Counter animation for stats
        const counters = document.querySelectorAll('.stat-item h3');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            const increment = target / 100;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current) + '+';
                    setTimeout(updateCounter, 20);
                } else {
                    counter.textContent = target + '+';
                }
            };
            
            // Start counter when in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(counter);
        });

        // Add hover effect to cards
        const cards = document.querySelectorAll('.misi-card, .value-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush