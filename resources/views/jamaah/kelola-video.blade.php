@extends('layouts.admin')

@section('title', 'Kelola Video')

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Kelola Video</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Video</li>
              </ol>
            </nav>
          </div>
          <div class="col-3">
            <div class="text-center mb-n5">
              <img src="{{ asset('assets/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong>
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Main Content -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title fw-semibold">Daftar Video</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahVideoModal">
            <i class="ti ti-plus"></i> Tambah Video
          </button>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Cari video..." id="searchInput">
              <button class="btn btn-outline-secondary" type="button">
                <i class="ti ti-search"></i>
              </button>
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="filterKategori">
              <option value="">Semua Kategori</option>
              <option value="kegiatan">Kegiatan</option>
              <option value="pengumuman">Pengumuman</option>
              <option value="tutorial">Tutorial</option>
              <option value="dokumentasi">Dokumentasi</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="filterStatus">
              <option value="">Semua Status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>
          </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle text-nowrap" id="videoTable">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Thumbnail</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($videos ?? [] as $index => $video)
                <tr data-kategori="{{ $video->kategori }}" data-status="{{ $video->status }}">
                  <td>{{ $videos->firstItem() + $index }}</td>
                  <td>
                    @if($video->thumbnail_url)
                      <img src="{{ $video->thumbnail_url }}" alt="{{ $video->judul }}" class="rounded" width="80" height="60"
                        style="object-fit: cover;"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-light-secondary d-flex align-items-center justify-content-center rounded\' style=\'width:80px;height:60px;\'><i class=\'ti ti-video fs-2 text-secondary\'></i></div>';">
                    @else
                      <div class="bg-light-secondary d-flex align-items-center justify-content-center rounded"
                        style="width: 80px; height: 60px;">
                        <i class="ti ti-video fs-2 text-secondary"></i>
                      </div>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="fw-semibold">{{ Str::limit($video->judul, 50) }}</span>
                      <small class="text-muted">{{ $video->formatted_date }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light-primary text-primary">{{ ucfirst($video->kategori) }}</span>
                  </td>
                  <td>
                    @if($video->video_type == 'youtube')
                      <span class="badge bg-light-danger text-danger"><i class="ti ti-brand-youtube"></i> YouTube</span>
                    @else
                      <span class="badge bg-light-info text-info"><i class="ti ti-file-video"></i> File</span>
                    @endif
                  </td>
                  <td>{{ \Carbon\Carbon::parse($video->created_at)->format('d M Y') }}</td>
                  <td>
                    @if($video->status == 'published')
                      <span class="badge bg-light-success text-success">Published</span>
                    @else
                      <span class="badge bg-light-warning text-warning">Draft</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-light-info" onclick="showDetail({{ $video->id }})"
                        title="Lihat Detail">
                        <i class="ti ti-eye"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-light-warning" onclick="editVideo({{ $video->id }})"
                        title="Edit">
                        <i class="ti ti-edit"></i>
                      </button>
                      <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus video ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light-danger" title="Hapus">
                          <i class="ti ti-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                     <div class="mb-3">
                      <i class="ti ti-video" style="font-size: 50px; color: #ddd;"></i>
                    </div>
                    <p class="text-muted">Belum ada data video</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if(isset($videos) && $videos->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Menampilkan {{ $videos->firstItem() }} - {{ $videos->lastItem() }} dari {{ $videos->total() }} data
            </div>
            {{ $videos->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal Tambah Video -->
  <div class="modal fade" id="tambahVideoModal" tabindex="-1" aria-labelledby="tambahVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahVideoModalLabel">Tambah Video Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Judul Video <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul"
                value="{{ old('judul') }}" required>
              @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
                  <option value="">Pilih Kategori</option>
                  <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                  <option value="pengumuman" {{ old('kategori') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                  <option value="tutorial" {{ old('kategori') == 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                  <option value="dokumentasi" {{ old('kategori') == 'dokumentasi' ? 'selected' : '' }}>Dokumentasi</option>
                </select>
                @error('kategori')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Tipe Video <span class="text-danger">*</span></label>
                <select class="form-select @error('video_type') is-invalid @enderror" name="video_type"
                  id="videoTypeTambah" required onchange="toggleVideoInput('Tambah')">
                  <option value="">Pilih Tipe</option>
                  <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                  <option value="file" {{ old('video_type') == 'file' ? 'selected' : '' }}>Upload File</option>
                </select>
                @error('video_type')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- YouTube URL Input -->
            <div class="mb-3" id="youtubeInputTambah" style="display: none;">
              <label class="form-label">URL YouTube <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('video_url') is-invalid @enderror" name="video_url"
                value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
              <small class="text-muted">Contoh: https://www.youtube.com/watch?v=xxxxx atau https://youtu.be/xxxxx</small>
              @error('video_url')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- File Upload Input -->
            <div class="mb-3" id="fileInputTambah" style="display: none;">
              <label class="form-label">File Video <span class="text-danger">*</span></label>
              <input type="file" class="form-control @error('video_file') is-invalid @enderror" name="video_file"
                accept="video/*">
              <small class="text-muted">Format: MP4, AVI, MOV, WMV. Maksimal 50MB</small>
              @error('video_file')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Thumbnail (Opsional)</label>
              <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" name="thumbnail"
                accept="image/*" onchange="previewImage(this, 'previewTambah')">
              <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan untuk menggunakan thumbnail
                default.</small>
              @error('thumbnail')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="mt-2">
                <img id="previewTambah" src="" alt="Preview" style="max-width: 200px; display: none;" class="rounded">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy"></i> Simpan Video
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Video -->
  <div class="modal fade" id="editVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Video</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formEditVideo" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div id="loadingEdit" class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div id="editFormContent" style="display: none;">
              <!-- Content will be loaded here -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">
              <i class="ti ti-device-floppy"></i> Update Video
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Detail Video -->
  <div class="modal fade" id="detailVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Video</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="loadingDetail" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <div id="detailVideoContent" style="display: none;">
            <!-- Content will be loaded here -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      // Base URL untuk AJAX
      const baseUrl = "{{ url('admin/video') }}";

      // Toggle video input based on type
      function toggleVideoInput(type) {
        const videoType = document.getElementById('videoType' + type).value;
        const youtubeInput = document.getElementById('youtubeInput' + type);
        const fileInput = document.getElementById('fileInput' + type);

        if (videoType === 'youtube') {
          youtubeInput.style.display = 'block';
          fileInput.style.display = 'none';
          youtubeInput.querySelector('input').required = true;
          if (fileInput.querySelector('input')) {
            fileInput.querySelector('input').required = false;
          }
        } else if (videoType === 'file') {
          youtubeInput.style.display = 'none';
          fileInput.style.display = 'block';
          youtubeInput.querySelector('input').required = false;
          if (fileInput.querySelector('input')) {
            fileInput.querySelector('input').required = true;
          }
        } else {
          youtubeInput.style.display = 'none';
          fileInput.style.display = 'none';
        }
      }

      // Image Preview
      function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      // Search functionality
      document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#videoTable tbody tr');

        rows.forEach(row => {
          if (row.cells.length > 1) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
          }
        });
      });

      // Filter by Category
      document.getElementById('filterKategori').addEventListener('change', function () {
        filterTable();
      });

      // Filter by Status
      document.getElementById('filterStatus').addEventListener('change', function () {
        filterTable();
      });

      function filterTable() {
        let kategori = document.getElementById('filterKategori').value;
        let status = document.getElementById('filterStatus').value;
        let rows = document.querySelectorAll('#videoTable tbody tr');

        rows.forEach(row => {
          if (row.cells.length > 1) {
            let rowKategori = row.getAttribute('data-kategori');
            let rowStatus = row.getAttribute('data-status');
            let showKategori = !kategori || rowKategori === kategori;
            let showStatus = !status || rowStatus === status;

            row.style.display = (showKategori && showStatus) ? '' : 'none';
          }
        });
      }

      // Edit function
      function editVideo(id) {
        const modal = new bootstrap.Modal(document.getElementById('editVideoModal'));
        const form = document.getElementById('formEditVideo');
        const loadingDiv = document.getElementById('loadingEdit');
        const contentDiv = document.getElementById('editFormContent');

        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';
        modal.show();

        fetch(`${baseUrl}/${id}/edit`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            form.action = `${baseUrl}/${data.id}`;

            const thumbnailPreview = data.thumbnail_url ?
              `<div class="mt-2">
                  <img id="previewEdit" src="${data.thumbnail_url}" alt="Current Thumbnail" style="max-width: 200px;" class="rounded">
                </div>` : '';

            contentDiv.innerHTML = `
                <div class="mb-3">
                  <label class="form-label">Judul Video <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="judul" value="${escapeHtml(data.judul)}" required>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select" name="kategori" required>
                      <option value="">Pilih Kategori</option>
                      <option value="kegiatan" ${data.kategori === 'kegiatan' ? 'selected' : ''}>Kegiatan</option>
                      <option value="pengumuman" ${data.kategori === 'pengumuman' ? 'selected' : ''}>Pengumuman</option>
                      <option value="tutorial" ${data.kategori === 'tutorial' ? 'selected' : ''}>Tutorial</option>
                      <option value="dokumentasi" ${data.kategori === 'dokumentasi' ? 'selected' : ''}>Dokumentasi</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Tipe Video <span class="text-danger">*</span></label>
                    <select class="form-select" name="video_type" id="videoTypeEdit" required onchange="toggleVideoInput('Edit')">
                      <option value="">Pilih Tipe</option>
                      <option value="youtube" ${data.video_type === 'youtube' ? 'selected' : ''}>YouTube</option>
                      <option value="file" ${data.video_type === 'file' ? 'selected' : ''}>Upload File</option>
                    </select>
                  </div>
                </div>

                <div class="mb-3" id="youtubeInputEdit" style="display: ${data.video_type === 'youtube' ? 'block' : 'none'};">
                  <label class="form-label">URL YouTube</label>
                  <input type="text" class="form-control" name="video_url" value="${escapeHtml(data.video_url || '')}" 
                         placeholder="https://www.youtube.com/watch?v=...">
                  <small class="text-muted">Contoh: https://www.youtube.com/watch?v=xxxxx atau https://youtu.be/xxxxx</small>
                </div>

                <div class="mb-3" id="fileInputEdit" style="display: ${data.video_type === 'file' ? 'block' : 'none'};">
                  <label class="form-label">File Video</label>
                  <input type="file" class="form-control" name="video_file" accept="video/*">
                  <small class="text-muted">Format: MP4, AVI, MOV, WMV. Maksimal 50MB. Kosongkan jika tidak ingin mengubah video.</small>
                  ${data.video_type === 'file' ? `<small class="text-info d-block mt-1">Video saat ini: ${escapeHtml(data.video_url)}</small>` : ''}
                </div>

                <div class="mb-3">
                  <label class="form-label">Thumbnail (Opsional)</label>
                  <input type="file" class="form-control" name="thumbnail" accept="image/*" onchange="previewImage(this, 'previewEdit')">
                  <small class="text-muted">Kosongkan jika tidak ingin mengubah thumbnail. Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                  ${thumbnailPreview}
                </div>

                <div class="mb-3">
                  <label class="form-label">Status <span class="text-danger">*</span></label>
                  <select class="form-select" name="status" required>
                    <option value="draft" ${data.status === 'draft' ? 'selected' : ''}>Draft</option>
                    <option value="published" ${data.status === 'published' ? 'selected' : ''}>Published</option>
                  </select>
                </div>
              `;

            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          })
          .catch(error => {
            console.error('Error:', error);
            contentDiv.innerHTML = `
                <div class="alert alert-danger">
                  <i class="ti ti-alert-circle"></i> Gagal memuat data: ${error.message}
                </div>
              `;
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          });
      }

      // Detail function
      function showDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailVideoModal'));
        const loadingDiv = document.getElementById('loadingDetail');
        const contentDiv = document.getElementById('detailVideoContent');

        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';
        modal.show();

        fetch(`${baseUrl}/${id}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            const statusBadge = data.status === 'published'
              ? '<span class="badge bg-success">Published</span>'
              : '<span class="badge bg-warning">Draft</span>';

            const kategoriBadge = `<span class="badge bg-primary">${data.kategori.charAt(0).toUpperCase() + data.kategori.slice(1)}</span>`;

            const typeBadge = data.video_type === 'youtube'
              ? '<span class="badge bg-danger"><i class="ti ti-brand-youtube"></i> YouTube</span>'
              : '<span class="badge bg-info"><i class="ti ti-file-video"></i> File</span>';

            let videoPlayer = '';
            if (data.video_type === 'youtube' && data.embed_url) {
              videoPlayer = `
                  <div class="ratio ratio-16x9 mb-4">
                    <iframe src="${data.embed_url}" allowfullscreen></iframe>
                  </div>
                `;
            } else if (data.video_type === 'file') {
              videoPlayer = `
                  <div class="mb-4">
                    <video controls class="w-100" style="max-height: 500px;">
                      <source src="{{ asset('storage') }}/${data.video_url}" type="video/mp4">
                      Browser Anda tidak mendukung video tag.
                    </video>
                  </div>
                `;
            }

            contentDiv.innerHTML = `
                ${videoPlayer}

                <div class="mb-3">
                  <h4 class="fw-bold">${escapeHtml(data.judul)}</h4>
                </div>

                <div class="d-flex gap-2 mb-3">
                  ${kategoriBadge}
                  ${typeBadge}
                  ${statusBadge}
                </div>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <small class="text-muted">
                      <i class="ti ti-calendar"></i> ${data.created_at}
                    </small>
                  </div>
                  ${data.video_type === 'youtube' ? `
                  <div class="col-md-6 text-end">
                    <a href="${escapeHtml(data.video_url)}" target="_blank" class="btn btn-sm btn-outline-danger">
                      <i class="ti ti-brand-youtube"></i> Lihat di YouTube
                    </a>
                  </div>
                  ` : ''}
                </div>

                <div class="mb-3">
                  <h6 class="fw-semibold">Thumbnail:</h6>
                  <img src="${data.thumbnail_url}" alt="Thumbnail" class="img-fluid rounded" style="max-width: 300px;">
                </div>
              `;

            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          })
          .catch(error => {
            console.error('Error:', error);
            contentDiv.innerHTML = `
                <div class="alert alert-danger">
                  <i class="ti ti-alert-circle"></i> Gagal memuat data: ${error.message}
                </div>
              `;
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          });
      }

      // Helper function untuk escape HTML
      function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
      }

      // Auto-dismiss alerts after 5 seconds
      document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
          setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
          }, 5000);
        });

        // Trigger toggle on page load if old input exists
        const videoTypeTambah = document.getElementById('videoTypeTambah');
        if (videoTypeTambah && videoTypeTambah.value) {
          toggleVideoInput('Tambah');
        }
      });
    </script>
  @endpush

@endsection