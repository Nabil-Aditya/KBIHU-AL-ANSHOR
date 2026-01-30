@extends('layouts.layout-pages')

@section('content')

<main class="main">

    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8 mt-5">
                        <h1>Galeri Foto</h1>
                        <p class="mb-0">Dokumentasi kegiatan dan momen berharga Kbihu Al-Anshor dalam membimbing jamaah haji dan umrah menuju ibadah yang maqbul dan mabrur.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Galeri Foto</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Page Title -->

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="row justify-content-center">
                {{-- Search Widget for Photos --}}
                <div class="search-widget widget-item mt-2">
                    <form action="{{ route('galeri.all') }}" method="GET">
                        <input type="text" name="search" placeholder="Cari foto..." value="{{ request('search') }}">
                        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section><!-- End Filter Section -->

    <!-- Galeri Section -->
    <section id="galeri" class="galeri section">
        <div class="container">
            <!-- Result Info -->
            @if(request('search'))
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <p class="text-muted">
                            Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                            @if($fotos->total() > 0)
                                - Ditemukan {{ $fotos->total() }} foto
                            @endif
                        </p>
                        <a href="{{ route('galeri.all') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Hapus Pencarian
                        </a>
                    </div>
                </div>
            @endif

            <div class="row gy-4">
                @forelse($fotos as $foto)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
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
                            <p class="text-muted mt-3">
                                @if(request('search'))
                                    Tidak ada foto ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada foto tersedia
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($fotos->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            {{ $fotos->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </section>

</main>

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
        background-color: #e74c3c;
        border: none;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-widget button:hover {
        background-color: #c0392b;
    }

    .foto-card {
        cursor: pointer;
        transition: transform 0.3s ease;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .foto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .foto-img {
        position: relative;
        overflow: hidden;
        height: 250px;
    }

    .foto-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .foto-card:hover .foto-img img {
        transform: scale(1.1);
    }

    .foto-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .foto-card:hover .foto-overlay {
        opacity: 1;
    }

    .foto-overlay i {
        font-size: 3rem;
        color: white;
    }

    .foto-info {
        padding: 20px;
    }

    .foto-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .foto-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .foto-date {
        font-size: 0.9rem;
        color: #666;
    }

    .foto-date i {
        margin-right: 5px;
    }

    .no-image-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .foto-img {
            height: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
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

    // ===== DOM LOADED EVENT =====
    document.addEventListener('DOMContentLoaded', function() {
        // Foto Modal Cleanup
        const fotoModalEl = document.getElementById('fotoModal');
        if (fotoModalEl) {
            fotoModalEl.addEventListener('hidden.bs.modal', function() {
                document.getElementById('modalFotoImage').src = '';
                document.getElementById('modalFotoJudul').textContent = '';
                document.getElementById('modalFotoTanggal').textContent = '';
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
            const modalEl = document.getElementById('fotoModal');
            if (modalEl && modalEl.classList.contains('show')) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
        }
    });
</script>
@endpush