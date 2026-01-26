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
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">Doa Haji & Umrah</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Page Title -->

    <!-- Doa Section -->
    <section id="doa-section" class="recent-posts section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Koleksi Doa</h2>
            <p>Doa-doa yang dapat digunakan dalam perjalanan ibadah haji dan umrah</p>
        </div>

        <!-- Filter Section -->
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form action="{{ route('doa.public.index') }}" method="GET" class="d-flex gap-2">
                        <!-- Search Input -->
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari doa..." 
                               value="{{ request('search') }}">
                        
                        <!-- Category Filter -->
                        <select name="kategori" class="form-select" style="max-width: 200px;">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}
                                </option>
                            @endforeach
                        </select>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        
                        @if(request()->hasAny(['search', 'kategori']))
                            <a href="{{ route('doa.public.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Doa Cards -->
        <div class="container">
            <div class="row gy-4">
                @forelse($doas as $doa)
                    <div class="col-xl-3 col-md-6">
                        <div class="card h-100 shadow-sm" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
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
                                        <i class="bi bi-eye me-1"></i> Lihat PDF
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
                                @if(request()->hasAny(['search', 'kategori']))
                                    Tidak ada doa yang sesuai dengan pencarian
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
                <div class="d-flex justify-content-center mt-5">
                    {{ $doas->links() }}
                </div>
            @endif
        </div>
    </section>

</main>

@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .btn-primary:hover {
        transform: scale(1.02);
    }
</style>
@endpush