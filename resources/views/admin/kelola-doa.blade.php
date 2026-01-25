@extends('layouts.admin')

@section('title', 'Kelola Doa')

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Kelola Doa</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Doa</li>
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
          <h5 class="card-title fw-semibold">Daftar Doa</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBeritaModal">
            <i class="ti ti-plus"></i> Tambah Doa
          </button>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Cari berita..." id="searchInput">
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
              <option value="artikel">Artikel</option>
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
          <table class="table table-hover align-middle text-nowrap" id="beritaTable">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($beritas ?? [] as $index => $berita)
                <tr data-kategori="{{ $berita->kategori }}" data-status="{{ $berita->status }}">
                  <td>{{ $beritas->firstItem() + $index }}</td>
                  <td>
                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="rounded"
                      width="60" height="60" style="object-fit: cover;"
                      onerror="this.src='{{ asset('assets/images/backgrounds/no-image.jpg') }}'">
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="fw-semibold">{{ Str::limit($berita->judul, 50) }}</span>
                      <small class="text-muted">{{ Str::limit($berita->excerpt, 60) }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light-primary text-primary">{{ ucfirst($berita->kategori) }}</span>
                  </td>
                  <td>{{ $berita->penulis }}</td>
                  <td>{{ \Carbon\Carbon::parse($berita->tanggal)->format('d M Y') }}</td>
                  <td>
                    @if($berita->status == 'published')
                      <span class="badge bg-light-success text-success">Published</span>
                    @else
                      <span class="badge bg-light-warning text-warning">Draft</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-light-info" onclick="showDetail({{ $berita->id }})"
                        title="Lihat Detail">
                        <i class="ti ti-eye"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-light-warning" onclick="editBerita({{ $berita->id }})"
                        title="Edit">
                        <i class="ti ti-edit"></i>
                      </button>
                      <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
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
                      <i class="ti ti-book" style="font-size: 50px; color: #ddd;"></i>
                    </div>
                    <p class="text-muted">Belum ada data doa</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if(isset($beritas) && $beritas->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Menampilkan {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} dari {{ $beritas->total() }} data
            </div>
            {{ $beritas->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal Tambah Berita -->
  <div class="modal fade" id="tambahBeritaModal" tabindex="-1" aria-labelledby="tambahBeritaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahBeritaModalLabel">Tambah Berita Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
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
                  <option value="artikel" {{ old('kategori') == 'artikel' ? 'selected' : '' }}>Artikel</option>
                </select>
                @error('kategori')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                  value="{{ old('tanggal', date('Y-m-d')) }}" required>
                @error('tanggal')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Excerpt/Ringkasan</label>
              <textarea class="form-control @error('excerpt') is-invalid @enderror" name="excerpt" rows="2"
                maxlength="200" placeholder="Ringkasan singkat berita...">{{ old('excerpt') }}</textarea>
              <small class="text-muted">Maksimal 200 karakter</small>
              @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Konten <span class="text-danger">*</span></label>
              <textarea class="form-control @error('konten') is-invalid @enderror" name="konten" rows="5"
                required>{{ old('konten') }}</textarea>
              @error('konten')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Gambar <span class="text-danger">*</span></label>
              <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" accept="image/*"
                required onchange="previewImage(this, 'previewTambah')">
              <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
              @error('gambar')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="mt-2">
                <img id="previewTambah" src="" alt="Preview" style="max-width: 200px; display: none;" class="rounded">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Penulis</label>
              <input type="text" class="form-control" name="penulis" value="{{ old('penulis', auth()->user()->name) }}"
                readonly>
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
              <i class="ti ti-device-floppy"></i> Simpan Berita
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Berita -->
  <div class="modal fade" id="editBeritaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Berita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formEditBerita" method="POST" enctype="multipart/form-data">
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
              <i class="ti ti-device-floppy"></i> Update Berita
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Detail Berita -->
  <div class="modal fade" id="detailBeritaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Berita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="loadingDetail" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <div id="detailBeritaContent" style="display: none;">
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
      const baseUrl = "{{ url('admin/berita') }}";

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
        let rows = document.querySelectorAll('#beritaTable tbody tr');

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
        let rows = document.querySelectorAll('#beritaTable tbody tr');

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

      // Edit function - DIPERBAIKI dengan error handling lengkap
      function editBerita(id) {
        const modal = new bootstrap.Modal(document.getElementById('editBeritaModal'));
        const form = document.getElementById('formEditBerita');
        const loadingDiv = document.getElementById('loadingEdit');
        const contentDiv = document.getElementById('editFormContent');

        // Show loading
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';

        // Show modal
        modal.show();

        // Fetch data - GUNAKAN baseUrl
        fetch(`${baseUrl}/${id}/edit`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            // Set form action
            form.action = `${baseUrl}/${data.id}`;

            // Populate form
            contentDiv.innerHTML = `
            <div class="mb-3">
              <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="judul" value="${escapeHtml(data.judul)}" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" name="kategori" required>
                  <option value="">Pilih Kategori</option>
                  <option value="kegiatan" ${data.kategori === 'kegiatan' ? 'selected' : ''}>Kegiatan</option>
                  <option value="pengumuman" ${data.kategori === 'pengumuman' ? 'selected' : ''}>Pengumuman</option>
                  <option value="artikel" ${data.kategori === 'artikel' ? 'selected' : ''}>Artikel</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="tanggal" value="${data.tanggal}" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Excerpt/Ringkasan</label>
              <textarea class="form-control" name="excerpt" rows="2" maxlength="200">${escapeHtml(data.excerpt || '')}</textarea>
              <small class="text-muted">Maksimal 200 karakter</small>
            </div>

            <div class="mb-3">
              <label class="form-label">Konten <span class="text-danger">*</span></label>
              <textarea class="form-control" name="konten" rows="5" required>${escapeHtml(data.konten)}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Gambar</label>
              <input type="file" class="form-control" name="gambar" accept="image/*" onchange="previewImage(this, 'previewEdit')">
              <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Format: JPG, PNG, JPEG. Maksimal 2MB</small>
              <div class="mt-2">
                <img id="previewEdit" src="{{ asset('storage') }}/${data.gambar}" alt="Current Image" style="max-width: 200px;" class="rounded">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Penulis</label>
              <input type="text" class="form-control" name="penulis" value="${escapeHtml(data.penulis)}" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select" name="status" required>
                <option value="draft" ${data.status === 'draft' ? 'selected' : ''}>Draft</option>
                <option value="published" ${data.status === 'published' ? 'selected' : ''}>Published</option>
              </select>
            </div>
          `;

            // Hide loading and show content
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          })
          .catch(error => {
            console.error('Error detail:', error);
            contentDiv.innerHTML = `
            <div class="alert alert-danger">
              <i class="ti ti-alert-circle"></i> Gagal memuat data: ${error.message}
              <br><small>URL: ${baseUrl}/${id}/edit</small>
              <br><small>Cek console browser (F12) untuk detail</small>
            </div>
          `;
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          });
      }

      // Detail function - DIPERBAIKI dengan error handling lengkap
      function showDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailBeritaModal'));
        const loadingDiv = document.getElementById('loadingDetail');
        const contentDiv = document.getElementById('detailBeritaContent');

        // Show loading
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';

        // Show modal
        modal.show();

        // Fetch and display detail - GUNAKAN baseUrl
        fetch(`${baseUrl}/${id}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            const tanggal = new Date(data.tanggal);
            const formattedDate = tanggal.toLocaleDateString('id-ID', {
              day: 'numeric',
              month: 'long',
              year: 'numeric'
            });

            const statusBadge = data.status === 'published'
              ? '<span class="badge bg-success">Published</span>'
              : '<span class="badge bg-warning">Draft</span>';

            const kategoriBadge = `<span class="badge bg-primary">${data.kategori.charAt(0).toUpperCase() + data.kategori.slice(1)}</span>`;

            contentDiv.innerHTML = `
            <div class="text-center mb-4">
              <img src="{{ asset('storage') }}/${data.gambar}" alt="${escapeHtml(data.judul)}" class="img-fluid rounded" style="max-height: 400px;">
            </div>

            <div class="mb-3">
              <h4 class="fw-bold">${escapeHtml(data.judul)}</h4>
            </div>

            <div class="d-flex gap-2 mb-3">
              ${kategoriBadge}
              ${statusBadge}
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <small class="text-muted">
                  <i class="ti ti-user"></i> ${escapeHtml(data.penulis)}
                </small>
              </div>
              <div class="col-md-6 text-end">
                <small class="text-muted">
                  <i class="ti ti-calendar"></i> ${formattedDate}
                </small>
              </div>
            </div>

            ${data.excerpt ? `
            <div class="mb-3">
              <h6 class="fw-semibold">Ringkasan:</h6>
              <p class="text-muted">${escapeHtml(data.excerpt)}</p>
            </div>
            ` : ''}

            <div class="mb-3">
              <h6 class="fw-semibold">Konten:</h6>
              <div style="white-space: pre-wrap;">${escapeHtml(data.konten)}</div>
            </div>
          `;

            // Hide loading and show content
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
          })
          .catch(error => {
            console.error('Error detail:', error);
            contentDiv.innerHTML = `
            <div class="alert alert-danger">
              <i class="ti ti-alert-circle"></i> Gagal memuat data: ${error.message}
              <br><small>URL: ${baseUrl}/${id}</small>
              <br><small>Cek console browser (F12) untuk detail</small>
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
      });
    </script>
  @endpush
@endsection