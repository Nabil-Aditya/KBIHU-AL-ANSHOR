@extends('layouts.layout-pages')

@section('content')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8 mt-5">
              <h1>Video</h1>
              <p class="mb-0">Temukan berbagai video informatif dan edukatif seputar Kbihu Al-Anshor. Saksikan kegiatan, 
                tutorial, dan dokumentasi perjalanan ibadah kami.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="current">Video</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Filter Section -->
    <section class="filter-section">
      <div class="container">
        <div class="row justify-content-center">
          {{-- Search Widget for Videos --}}
          <div class="search-widget widget-item mt-2">
            <form action="{{ route('video.all') }}" method="GET">
              <input type="text" name="search" placeholder="Cari video..." value="{{ request('search') }}">
              <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
          </div>
        </div>
      </div>
    </section><!-- End Filter Section -->

    <!-- Video Section -->
    <section id="video" class="video section">
        <div class="container">
            <!-- Result Info -->
            @if(request('search'))
              <div class="row">
                <div class="col-12 text-center mb-4">
                  <p class="text-muted">
                    Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    @if($videos->total() > 0)
                      - Ditemukan {{ $videos->total() }} video
                    @endif
                  </p>
                  <a href="{{ route('video.all') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Hapus Pencarian
                  </a>
                </div>
              </div>
            @endif

            <div class="row gy-4">
                @forelse($videos as $video)
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
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
                            <p class="text-muted mt-3">
                                @if(request('search'))
                                    Tidak ada video ditemukan untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada video tersedia
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
              <div class="row mt-5">
                <div class="col-12">
                  <nav aria-label="Page navigation">
                    {{ $videos->appends(request()->query())->links('pagination::bootstrap-5') }}
                  </nav>
                </div>
              </div>
            @endif
        </div>
    </section>

  </main>

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

@endsection

@push('scripts')
  <script>
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

    // Cleanup when modal closes
    document.addEventListener('DOMContentLoaded', function() {
        const videoModalEl = document.getElementById('videoModal');
        if (videoModalEl) {
            videoModalEl.addEventListener('hidden.bs.modal', function() {
                const youtubePlayer = document.getElementById('youtubePlayer');
                const filePlayer = document.getElementById('filePlayer');

                youtubePlayer.src = '';
                youtubePlayer.style.display = 'none';
                filePlayer.pause();
                filePlayer.src = '';
                filePlayer.load();
                filePlayer.style.display = 'none';
            });
        }
    });
  </script>
@endpush