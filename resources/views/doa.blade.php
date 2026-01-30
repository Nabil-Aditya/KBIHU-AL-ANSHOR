@extends('layouts.layout-pages')

@section('content')

<main class="main">

    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8 mt-5">
                        <h1>Doa Haji & Umrah</h1>
                        <p class="mb-0">Doa adalah penguat hati dan teman perjalanan menuju rumah Allah. Semoga rangkaian doa yang kami sajikan membantu jamaah menjalani ibadah haji dan umrah dengan penuh ketenangan, keikhlasan, dan keberkahan.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Doa Haji & Umrah</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Page Title -->

    <!-- Filter Section -->
    <section class="filter-section">
      <div class="container">
        <div class="row justify-content-center">
          {{-- Search Widget for Doa --}}
          <div class="search-widget widget-item mt-2">
            <form action="{{ route('doa.all') }}" method="GET">
              <input type="text" name="search" placeholder="Cari doa..." value="{{ request('search') }}">
              <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
          </div>
        </div>
      </div>
    </section><!-- End Filter Section -->

    <!-- Doa Section -->
    <section id="doa-section" class="recent-posts section">
        <div class="container">
            <!-- Result Info -->
            @if(request('search'))
              <div class="row">
                <div class="col-12 text-center mb-4">
                  <p class="text-muted">
                    Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    @if($doas->total() > 0)
                      - Ditemukan {{ $doas->total() }} doa
                    @endif
                  </p>
                  <a href="{{ route('doa.all') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Hapus Pencarian
                  </a>
                </div>
              </div>
            @endif

            <div class="row gy-4">
                @forelse($doas as $doa)
                    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 4) * 100 }}">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <!-- PDF Icon -->
                                <div class="text-center mb-3">
                                    <i class="bi bi-file-pdf-fill text-danger" style="font-size: 4rem;"></i>
                                </div>
                                
                                <!-- Title -->
                                <h5 class="card-title text-center mb-3">
                                    {{ Str::limit($doa->judul, 50) }}
                                </h5>
                                
                                <!-- Meta Info -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary">{{ ucfirst($doa->kategori) }}</span>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $doa->created_at->format('d M Y') }}
                                    </small>
                                </div>
                                
                                <!-- Action Button -->
                                <div class="mt-auto">
                                    <a href="{{ route('doa.public.view', $doa->id) }}" 
                                       class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-eye me-1"></i> Baca Doa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-pdf" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">
                                @if(request('search'))
                                    Tidak ada doa ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada doa tersedia
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($doas->hasPages())
              <div class="row mt-5">
                <div class="col-12">
                  <nav aria-label="Page navigation">
                    {{ $doas->appends(request()->query())->links('pagination::bootstrap-5') }}
                  </nav>
                </div>
              </div>
            @endif
        </div>
    </section>

</main>

@endsection

@push('scripts')
<script>
    // Form validation - prevent empty search
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('.search-widget form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const searchInput = this.querySelector('input[name="search"]');
                if (searchInput && searchInput.value.trim() === '') {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>
@endpush