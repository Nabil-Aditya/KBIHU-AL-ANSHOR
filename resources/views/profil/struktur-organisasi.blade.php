@extends('layouts.layout-pages')

@section('content')
<main class="main">
    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8 mt-5">
                        <h1>Struktur Organisasi</h1>
                        <p class="mb-0">Struktur organisasi KBIHU Al-Anshor yang solid dan profesional dalam memberikan pelayanan terbaik kepada jamaah haji dan umrah.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Struktur Organisasi</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Page Title -->

    <!-- Struktur Organisasi Section -->
    <section class="struktur-section py-5">
        <div class="container">
            <!-- Pembina & Pengawas -->
            <div class="row justify-content-center mb-4" data-aos="fade-down">
                <div class="col-lg-6 col-md-8">
                    <div class="org-card pembina-card text-center p-4 rounded-4 shadow-sm">
                        <div class="org-label mb-2">
                            <span class="badge bg-danger">Pembina & Pengawas</span>
                        </div>
                        <h4 class="fw-bold mb-1">Kementerian Haji</h4>
                        <p class="text-muted mb-0 small">Pengawasan & Pembinaan</p>
                    </div>
                </div>
            </div>

            <!-- Arrow Down -->
            <div class="row justify-content-center mb-4">
                <div class="col-auto">
                    <div class="org-arrow-down">
                        <i class="bi bi-arrow-down-circle-fill h2 text-primary"></i>
                    </div>
                </div>
            </div>

            <!-- Ketua -->
            <div class="row justify-content-center mb-4" data-aos="fade-up">
                <div class="col-lg-5 col-md-6">
                    <div class="org-card ketua-card text-center p-4 rounded-4 shadow-lg border-primary">
                        <div class="org-icon mb-3">
                            <div class="icon-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-badge-fill h1 text-primary mb-0"></i>
                            </div>
                        </div>
                        <div class="org-label mb-2">
                            <span class="badge bg-primary">Ketua</span>
                        </div>
                        <h3 class="fw-bold mb-1">K.H. Su'udi</h3>
                        <p class="text-muted mb-0">Pimpinan Organisasi</p>
                    </div>
                </div>
            </div>

            <!-- Arrow to Bendahara -->
            <div class="row justify-content-center mb-4">
                <div class="col-auto">
                    <div class="org-arrow-right">
                        <i class="bi bi-arrow-right-circle-fill h2 text-info"></i>
                    </div>
                </div>
            </div>

            <!-- Bendahara (Side Position) -->
            <div class="row justify-content-center mb-4" data-aos="fade-left">
                <div class="col-lg-5 col-md-6">
                    <div class="org-card bendahara-card text-center p-4 rounded-4 shadow-sm border-info">
                        <div class="org-icon mb-3">
                            <div class="icon-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px;">
                                <i class="bi bi-cash-stack h2 text-info mb-0"></i>
                            </div>
                        </div>
                        <div class="org-label mb-2">
                            <span class="badge bg-info">Bendahara</span>
                        </div>
                        <h4 class="fw-bold mb-1">Hj. Etti Retno Sari</h4>
                        <p class="text-muted mb-0 small">Pengelola Keuangan</p>
                    </div>
                </div>
            </div>

            <!-- Arrow Down to Divisions -->
            <div class="row justify-content-center mb-4">
                <div class="col-auto">
                    <div class="org-arrow-down">
                        <i class="bi bi-arrow-down-circle-fill h2 text-success"></i>
                    </div>
                </div>
            </div>

            <!-- Divisi-divisi (4 Columns) -->
            <div class="row g-4 justify-content-center">
                <!-- Sekretaris -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="org-card divisi-card h-100 text-center p-4 rounded-4 shadow-sm">
                        <div class="org-icon mb-3">
                            <div class="icon-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-pencil-square h3 text-success mb-0"></i>
                            </div>
                        </div>
                        <div class="org-label mb-2">
                            <span class="badge bg-success">Sekretaris</span>
                        </div>
                        <h5 class="fw-bold mb-2">K.H.M. Nawawi</h5>
                        <p class="text-muted mb-0 small">Administrasi & Dokumentasi</p>
                    </div>
                </div>

                <!-- Bidang Bimbingan -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="org-card divisi-card h-100 text-center p-4 rounded-4 shadow-sm">
                        <div class="org-icon mb-3">
                            <div class="icon-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-book-fill h3 text-warning mb-0"></i>
                            </div>
                        </div>
                        <div class="org-label mb-2">
                            <span class="badge bg-warning">Bidang Bimbingan</span>
                        </div>
                        <h5 class="fw-bold mb-2">K.H. Achmad Fanani</h5>
                        <p class="text-muted mb-0 small">Manasik & Pembinaan</p>
                    </div>
                </div>

                <!-- Bidang Kesehatan -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="org-card divisi-card h-100 text-center p-4 rounded-4 shadow-sm">
                        <div class="org-icon mb-3">
                            <div class="icon-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-heart-pulse-fill h3 text-danger mb-0"></i>
                            </div>
                        </div>
                        <div class="org-label mb-2">
                            <span class="badge bg-danger">Bidang Kesehatan</span>
                        </div>
                        <h5 class="fw-bold mb-2">dr. Hj. Yulianti Dewi</h5>
                        <p class="text-muted mb-0 small">Kesehatan Jamaah</p>
                    </div>
                </div>

                <!-- Bidang Humas & Kerja Sama -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="org-card divisi-card h-100 text-center p-4 rounded-4 shadow-sm">
                        <div class="org-icon mb-3">
                            <div class="icon-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-people-fill h3 text-primary mb-0"></i>
                            </div>
                        </div>
                        <div class="org-label mb-2">
                            <span class="badge bg-primary">Humas & Kerja Sama</span>
                        </div>
                        <h5 class="fw-bold mb-2">Tim Humas</h5>
                        <ul class="list-unstyled mb-0 mt-3 text-start small">
                            <li class="mb-2"><i class="bi bi-dot text-primary"></i> Hj. Sugiati</li>
                            <li class="mb-2"><i class="bi bi-dot text-primary"></i> Hj. Wilna Sukrini</li>
                            <li><i class="bi bi-dot text-primary"></i> Hj. Rina</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="row justify-content-center mt-5" data-aos="fade-up">
                <div class="col-lg-10">
                    <div class="info-box p-4 rounded-4 bg-light text-center">
                        <i class="bi bi-info-circle-fill h2 text-primary mb-3"></i>
                        <h4 class="fw-bold mb-3">Struktur Organisasi yang Solid</h4>
                        <p class="text-muted mb-0">
                            Setiap bidang dalam struktur organisasi KBIHU Al-Anshor memiliki tugas dan tanggung jawab yang jelas untuk memastikan pelayanan terbaik kepada jamaah. Kami berkomitmen untuk terus meningkatkan kualitas dan profesionalitas dalam setiap aspek pelayanan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@push('styles')
<style>
    /* Custom Styles for Struktur Organisasi Page */
    :root {
        --primary-color: #866749;
        --box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        --transition: all 0.3s ease;
    }

    .struktur-section {
        background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
    }

    .org-card {
        background: white;
        border: 2px solid #e9ecef;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .org-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .org-card:hover::before {
        left: 100%;
    }

    .org-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-color: var(--primary-color);
    }

    .pembina-card {
        background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
        border-color: #dc3545;
    }

    .ketua-card {
        background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
        border-width: 3px;
    }

    .bendahara-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
    }

    .divisi-card {
        background: white;
    }

    .divisi-card:hover {
        border-color: #0d6efd;
    }

    .icon-circle {
        transition: var(--transition);
    }

    .org-card:hover .icon-circle {
        transform: scale(1.1) rotate(5deg);
    }

    .org-arrow-down, .org-arrow-right {
        text-align: center;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .org-label {
        margin-bottom: 10px;
    }

    .org-label .badge {
        font-size: 0.75rem;
        padding: 5px 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .info-box {
        border-left: 5px solid var(--primary-color);
        box-shadow: var(--box-shadow);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .org-card {
            margin-bottom: 20px;
        }

        .icon-circle {
            width: 60px !important;
            height: 60px !important;
        }

        .ketua-card .icon-circle {
            width: 70px !important;
            height: 70px !important;
        }

        .org-arrow-right {
            transform: rotate(90deg);
        }

        h3 {
            font-size: 1.3rem;
        }

        h4 {
            font-size: 1.1rem;
        }

        h5 {
            font-size: 1rem;
        }
    }

    /* Print Styles */
    @media print {
        .org-card {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        .page-title {
            break-after: avoid;
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
                offset: 100,
                easing: 'ease-in-out'
            });
        }

        // Add hover animation to cards
        const orgCards = document.querySelectorAll('.org-card');
        orgCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endpush