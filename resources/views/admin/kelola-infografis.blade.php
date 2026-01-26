@extends('layouts.admin')

@section('title', 'Kelola Infografis')

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Kelola Infografis</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Infografis</li>
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
          <h5 class="card-title fw-semibold">Daftar Infografis</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahInfografisModal">
            <i class="ti ti-plus"></i> Tambah Infografis
          </button>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-3">
          <div class="col-md-9">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Cari infografis..." id="searchInput">
              <button class="btn btn-outline-secondary" type="button">
                <i class="ti ti-search"></i>
              </button>
            </div>
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
          <table class="table table-hover align-middle text-nowrap" id="infografisTable">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Thumbnail</th>
                <th>Judul</th>
                <th>Total File</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($infografis ?? [] as $index => $item)
                <tr data-status="{{ $item->status }}">
                  <td>{{ $infografis->firstItem() + $index }}</td>
                  <td>
                    @if(!empty($item->thumbnail) && file_exists(public_path('storage/' . $item->thumbnail)))
                      <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->judul }}" class="rounded"
                        width="60" height="60" style="object-fit: cover;">
                    @else
                      <div class="no-image-icon-xs">
                        <i class="ti ti-photo"></i>
                      </div>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="fw-semibold">{{ Str::limit($item->judul, 50) }}</span>
                      @if($item->deskripsi)
                        <small class="text-muted">{{ Str::limit($item->deskripsi, 60) }}</small>
                      @endif
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light-info text-info">
                      <i class="ti ti-files"></i> {{ $item->files_count }} file
                    </span>
                  </td>
                  <td>
                    @if($item->status == 'published')
                      <span class="badge bg-light-success text-success">Published</span>
                    @else
                      <span class="badge bg-light-warning text-warning">Draft</span>
                    @endif
                  </td>
                  <td>{{ $item->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-light-info" onclick="showDetail({{ $item->id }})"
                        title="Lihat Detail">
                        <i class="ti ti-eye"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-light-warning" onclick="editInfografis({{ $item->id }})"
                        title="Edit">
                        <i class="ti ti-edit"></i>
                      </button>
                      <form action="{{ route('admin.infografis.destroy', $item->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus infografis ini beserta semua filenya?')">
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
                  <td colspan="7" class="text-center py-5">
                      <div class="mb-3">
                      <i class="ti ti-chart-infographic" style="font-size: 50px; color: #ddd;"></i>
                    </div>
                    <p class="text-muted">Belum ada data infografis</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if(isset($infografis) && $infografis->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Menampilkan {{ $infografis->firstItem() }} - {{ $infografis->lastItem() }} dari {{ $infografis->total() }}
              data
            </div>
            {{ $infografis->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal Tambah Infografis -->
  <div class="modal fade" id="tambahInfografisModal" tabindex="-1" aria-labelledby="tambahInfografisModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahInfografisModalLabel">Tambah Infografis Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.infografis.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Judul <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul"
                value="{{ old('judul') }}" required>
              @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="3"
                placeholder="Deskripsi singkat infografis...">{{ old('deskripsi') }}</textarea>
              @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Upload File Infografis <span class="text-danger">*</span></label>
              <input type="file" class="form-control @error('files.*') is-invalid @enderror" name="files[]" multiple
                accept="image/*" required onchange="previewMultipleImages(this, 'previewTambah')">
              <small class="text-muted">Pilih beberapa file gambar (JPG, PNG, JPEG). Max 5MB per file.</small>
              @error('files.*')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div id="previewTambah" class="row g-2 mt-2"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy"></i> Simpan Infografis
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Infografis -->
  <div class="modal fade" id="editInfografisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Infografis</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formEditInfografis" method="POST" enctype="multipart/form-data">
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
              <i class="ti ti-device-floppy"></i> Update Infografis
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Detail Infografis -->
  <div class="modal fade" id="detailInfografisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Infografis</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="loadingDetail" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <div id="detailInfografisContent" style="display: none;">
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
      const baseUrl = "{{ url('admin/infografis') }}";

      // Preview multiple images
      function previewMultipleImages(input, targetId = 'previewTambah') {
        const preview = document.getElementById(targetId);
        preview.innerHTML = '';

        if (input.files) {
          Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
              const col = document.createElement('div');
              col.className = 'col-md-3';
              col.innerHTML = `
              <div class="position-relative">
                <img src="${e.target.result}" class="img-thumbnail rounded" style="height: 150px; object-fit: cover; width: 100%;">
                <span class="badge bg-primary position-absolute top-0 start-0 m-2">${index + 1}</span>
              </div>
            `;
              preview.appendChild(col);
            };
            reader.readAsDataURL(file);
          });
        }
      }

      // Search functionality
      document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#infografisTable tbody tr');

        rows.forEach(row => {
          if (row.cells.length > 1) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
          }
        });
      });

      // Filter by Status
      document.getElementById('filterStatus').addEventListener('change', function () {
        let status = this.value;
        let rows = document.querySelectorAll('#infografisTable tbody tr');

        rows.forEach(row => {
          if (row.cells.length > 1) {
            let rowStatus = row.getAttribute('data-status');
            let showStatus = !status || rowStatus === status;
            row.style.display = showStatus ? '' : 'none';
          }
        });
      });

      // Edit function
      function editInfografis(id) {
        const modal = new bootstrap.Modal(document.getElementById('editInfografisModal'));
        const form = document.getElementById('formEditInfografis');
        const loadingDiv = document.getElementById('loadingEdit');
        const contentDiv = document.getElementById('editFormContent');

        // Show loading
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';

        // Show modal
        modal.show();

        // Fetch data
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
            // Set form action
            form.action = `${baseUrl}/${data.id}`;

            // Build existing files HTML
            let existingFilesHtml = '';
            data.files.forEach(file => {
              existingFilesHtml += `
              <div class="col-md-3" id="file-${file.id}">
                <div class="position-relative">
                  <img src="${file.file_url}" class="img-thumbnail rounded" style="height: 150px; object-fit: cover; width: 100%;">
                  <span class="badge bg-primary position-absolute top-0 start-0 m-2">${file.urutan}</span>
                  <button type="button" 
                          class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                          onclick="deleteFile(${file.id})">
                    <i class="ti ti-trash"></i>
                  </button>
                </div>
                <small class="text-muted d-block text-truncate mt-1">${escapeHtml(file.file_name)}</small>
              </div>
            `;
            });

            // Populate form
            contentDiv.innerHTML = `
            <div class="mb-3">
              <label class="form-label">Judul <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="judul" value="${escapeHtml(data.judul)}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" rows="3">${escapeHtml(data.deskripsi || '')}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select" name="status" required>
                <option value="published" ${data.status === 'published' ? 'selected' : ''}>Published</option>
                <option value="draft" ${data.status === 'draft' ? 'selected' : ''}>Draft</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">File yang Ada (${data.total_files} file)</label>
              <div class="row g-2" id="existingFiles">
                ${existingFilesHtml}
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Tambah File Baru (Optional)</label>
              <input type="file" 
                     class="form-control" 
                     name="files[]" 
                     multiple 
                     accept="image/*"
                     onchange="previewMultipleImages(this, 'editImagePreview')">
              <small class="text-muted">Pilih beberapa file gambar untuk ditambahkan. Max 5MB per file.</small>
            </div>

            <div id="editImagePreview" class="row g-2 mt-2"></div>
          `;

            // Hide loading and show content
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

      // Delete single file
      function deleteFile(fileId) {
        if (!confirm('Yakin ingin menghapus file ini?')) return;

        fetch(`${baseUrl}/file/${fileId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              document.getElementById(`file-${fileId}`).remove();
              alert('File berhasil dihapus!');
            } else {
              alert('Gagal menghapus file: ' + data.message);
            }
          })
          .catch(error => {
            alert('Error: ' + error.message);
          });
      }

      // Show detail
      function showDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailInfografisModal'));
        const loadingDiv = document.getElementById('loadingDetail');
        const contentDiv = document.getElementById('detailInfografisContent');

        // Show loading
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';

        // Show modal
        modal.show();

        // Fetch and display detail
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

            // Build files gallery
            let filesGalleryHtml = '';
            data.files.forEach(file => {
              filesGalleryHtml += `
              <div class="col-md-3">
                <div class="card">
                  <img src="${file.file_url}" class="card-img-top" style="height: 200px; object-fit: contain;">
                  <div class="card-body p-2">
                    <span class="badge bg-primary">File ${file.urutan}</span>
                    <small class="d-block text-truncate mt-1">${escapeHtml(file.file_name)}</small>
                  </div>
                </div>
              </div>
            `;
            });

            contentDiv.innerHTML = `
            <div class="mb-3">
              <h4 class="fw-bold">${escapeHtml(data.judul)}</h4>
            </div>

            <div class="d-flex gap-2 mb-3">
              ${statusBadge}
              <span class="badge bg-info">
                <i class="ti ti-files"></i> ${data.total_files} file
              </span>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <small class="text-muted">
                  <i class="ti ti-calendar"></i> ${data.created_at}
                </small>
              </div>
              <div class="col-md-6 text-end">
                <small class="text-muted">
                  <i class="ti ti-clock"></i> Diupdate: ${data.updated_at}
                </small>
              </div>
            </div>

            ${data.deskripsi ? `
            <div class="mb-3">
              <h6 class="fw-semibold">Deskripsi:</h6>
              <p class="text-muted">${escapeHtml(data.deskripsi)}</p>
            </div>
            ` : ''}

            <hr>
            <h6 class="fw-semibold mb-3">Semua File (${data.total_files})</h6>
            <div class="row g-3">
              ${filesGalleryHtml}
            </div>
          `;

            // Hide loading and show content
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
        if (!text) return '';
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
      });
    </script>
  @endpush
@endsection