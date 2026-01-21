@extends('layouts.admin')

@section('title', 'Kelola Foto')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
      <div class="row align-items-center">
        <div class="col-9">
          <h4 class="fw-semibold mb-8">Kelola Foto</h4>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Kelola Foto</li>
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
        <h5 class="card-title fw-semibold">Daftar Foto</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahFotoModal">
          <i class="ti ti-plus"></i> Tambah Foto
        </button>
      </div>

      <!-- Search and Filter -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari foto..." id="searchInput">
            <button class="btn btn-outline-secondary" type="button">
              <i class="ti ti-search"></i>
            </button>
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select" id="filterKategori">
            <option value="">Semua Kategori</option>
            <option value="kegiatan">Kegiatan</option>
            <option value="fasilitas">Fasilitas</option>
            <option value="dokumentasi">Dokumentasi</option>
            <option value="event">Event</option>
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
        <table class="table table-hover align-middle text-nowrap" id="fotoTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Judul</th>
              <th>Kategori</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($fotos ?? [] as $index => $foto)
            <tr data-kategori="{{ $foto->kategori }}" data-status="{{ $foto->status }}">
              <td>{{ $fotos->firstItem() + $index }}</td>
              <td>
                <img src="{{ $foto->foto_url }}" alt="{{ $foto->judul }}" 
                     class="rounded" width="80" height="60" style="object-fit: cover;"
                     onerror="this.src='{{ asset('assets/images/backgrounds/no-image.jpg') }}'">
              </td>
              <td>
                <div class="d-flex flex-column">
                  <span class="fw-semibold">{{ Str::limit($foto->judul, 50) }}</span>
                  <small class="text-muted">{{ $foto->formatted_date }}</small>
                </div>
              </td>
              <td>
                <span class="badge bg-light-primary text-primary">{{ ucfirst($foto->kategori) }}</span>
              </td>
              <td>{{ \Carbon\Carbon::parse($foto->created_at)->format('d M Y') }}</td>
              <td>
                @if($foto->status == 'published')
                  <span class="badge bg-light-success text-success">Published</span>
                @else
                  <span class="badge bg-light-warning text-warning">Draft</span>
                @endif
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-sm btn-light-info" 
                          onclick="showDetail({{ $foto->id }})"
                          title="Lihat Detail">
                    <i class="ti ti-eye"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-light-warning" 
                          onclick="editFoto({{ $foto->id }})"
                          title="Edit">
                    <i class="ti ti-edit"></i>
                  </button>
                  <form action="{{ route('admin.foto.destroy', $foto->id) }}" method="POST" class="d-inline" 
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">
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
                <img src="{{ asset('assets/images/backgrounds/no-data.svg') }}" alt="No Data" width="200" class="mb-3">
                <p class="text-muted">Belum ada data foto</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if(isset($fotos) && $fotos->hasPages())
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
          Menampilkan {{ $fotos->firstItem() }} - {{ $fotos->lastItem() }} dari {{ $fotos->total() }} data
        </div>
        {{ $fotos->links() }}
      </div>
      @endif
    </div>
  </div>
</div>

<!-- Modal Tambah Foto -->
<div class="modal fade" id="tambahFotoModal" tabindex="-1" aria-labelledby="tambahFotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahFotoModalLabel">Tambah Foto Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.foto.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul Foto <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                   name="judul" value="{{ old('judul') }}" required>
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
                <option value="fasilitas" {{ old('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                <option value="dokumentasi" {{ old('kategori') == 'dokumentasi' ? 'selected' : '' }}>Dokumentasi</option>
                <option value="event" {{ old('kategori') == 'event' ? 'selected' : '' }}>Event</option>
              </select>
              @error('kategori')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
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

          <div class="mb-3">
            <label class="form-label">File Foto <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                   name="foto" accept="image/*" required onchange="previewImage(this, 'previewTambah')">
            <small class="text-muted">Format: JPG, PNG, JPEG, WEBP. Maksimal 5MB</small>
            @error('foto')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="mt-2">
              <img id="previewTambah" src="" alt="Preview" style="max-width: 200px; display: none;" class="rounded">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy"></i> Simpan Foto
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Foto -->
<div class="modal fade" id="editFotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditFoto" method="POST" enctype="multipart/form-data">
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
            <i class="ti ti-device-floppy"></i> Update Foto
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Detail Foto -->
<div class="modal fade" id="detailFotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="loadingDetail" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div id="detailFotoContent" style="display: none;">
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
const baseUrl = "{{ url('admin/foto') }}";

// Image Preview
function previewImage(input, previewId) {
  const preview = document.getElementById(previewId);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
  let filter = this.value.toLowerCase();
  let rows = document.querySelectorAll('#fotoTable tbody tr');
  
  rows.forEach(row => {
    if (row.cells.length > 1) {
      let text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    }
  });
});

// Filter by Category
document.getElementById('filterKategori').addEventListener('change', function() {
  filterTable();
});

// Filter by Status
document.getElementById('filterStatus').addEventListener('change', function() {
  filterTable();
});

function filterTable() {
  let kategori = document.getElementById('filterKategori').value;
  let status = document.getElementById('filterStatus').value;
  let rows = document.querySelectorAll('#fotoTable tbody tr');
  
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
function editFoto(id) {
  const modal = new bootstrap.Modal(document.getElementById('editFotoModal'));
  const form = document.getElementById('formEditFoto');
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
      
      contentDiv.innerHTML = `
        <div class="mb-3">
          <label class="form-label">Judul Foto <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="judul" value="${escapeHtml(data.judul)}" required>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select class="form-select" name="kategori" required>
              <option value="">Pilih Kategori</option>
              <option value="kegiatan" ${data.kategori === 'kegiatan' ? 'selected' : ''}>Kegiatan</option>
              <option value="fasilitas" ${data.kategori === 'fasilitas' ? 'selected' : ''}>Fasilitas</option>
              <option value="dokumentasi" ${data.kategori === 'dokumentasi' ? 'selected' : ''}>Dokumentasi</option>
              <option value="event" ${data.kategori === 'event' ? 'selected' : ''}>Event</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select" name="status" required>
              <option value="draft" ${data.status === 'draft' ? 'selected' : ''}>Draft</option>
              <option value="published" ${data.status === 'published' ? 'selected' : ''}>Published</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">File Foto</label>
          <input type="file" class="form-control" name="foto" accept="image/*" onchange="previewImage(this, 'previewEdit')">
          <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, JPEG, WEBP. Maksimal 5MB</small>
          <div class="mt-2">
            <img id="previewEdit" src="${data.foto_url}" alt="Current Image" style="max-width: 200px;" class="rounded">
          </div>
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
  const modal = new bootstrap.Modal(document.getElementById('detailFotoModal'));
  const loadingDiv = document.getElementById('loadingDetail');
  const contentDiv = document.getElementById('detailFotoContent');
  
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
      
      contentDiv.innerHTML = `
        <div class="text-center mb-4">
          <img src="${data.foto_url}" alt="${escapeHtml(data.judul)}" class="img-fluid rounded" style="max-height: 500px;">
        </div>
        
        <div class="mb-3">
          <h4 class="fw-bold">${escapeHtml(data.judul)}</h4>
        </div>
        
        <div class="d-flex gap-2 mb-3">
          ${kategoriBadge}
          ${statusBadge}
        </div>
        
        <div class="row mb-3">
          <div class="col-md-12">
            <small class="text-muted">
              <i class="ti ti-calendar"></i> ${data.created_at}
            </small>
          </div>
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
document.addEventListener('DOMContentLoaded', function() {
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