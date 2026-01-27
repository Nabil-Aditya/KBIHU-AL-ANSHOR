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
                                <li class="breadcrumb-item"><a class="text-muted"
                                        href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Kelola Doa</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{ asset('assets/images/breadcrumb/ChatBc.png') }}" alt=""
                                class="img-fluid mb-n4">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDoaModal">
                        <i class="ti ti-plus"></i> Tambah Doa
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari doa..." id="searchInput">
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
                    <table class="table table-hover align-middle text-nowrap" id="doaTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul Doa</th>
                                <th>Kategori</th>
                                <th>File Doa</th>
                                <th>Tanggal Upload</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doa ?? [] as $index => $item)
                                <tr data-kategori="{{ $item->kategori }}" data-status="{{ $item->status }}">
                                    <td>{{ $doa->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ Str::limit($item->judul, 50) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-light-primary text-primary">{{ ucfirst($item->kategori) }}</span>
                                    </td>
                                    <td>
                                        @if ($item->doa)
                                            <a href="{{ asset('storage/' . $item->doa) }}" target="_blank" 
                                               class="badge bg-light-info text-info">
                                                <i class="ti ti-file-text"></i> Lihat PDF
                                            </a>
                                            <small class="d-block text-muted mt-1">
                                                {{ \Illuminate\Support\Str::limit(basename($item->doa), 20) }}
                                            </small>
                                        @else
                                            <span class="badge bg-light-danger text-danger">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                    <td>
                                        @if ($item->status == 'published')
                                            <span class="badge bg-light-success text-success">Published</span>
                                        @else
                                            <span class="badge bg-light-warning text-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ asset('storage/' . $item->doa) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-light-info"
                                               title="Lihat PDF" 
                                               @if(!$item->doa) disabled @endif>
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-light-warning"
                                                onclick="editDoa({{ $item->id }})" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.doa.destroy', $item->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus doa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger"
                                                    title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img src="{{ asset('assets/images/backgrounds/no-data.svg') }}" alt="No Data"
                                            width="200" class="mb-3">
                                        <p class="text-muted">Belum ada data doa</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if (isset($doa) && $doa->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Menampilkan {{ $doa->firstItem() }} - {{ $doa->lastItem() }} dari
                            {{ $doa->total() }} data
                        </div>
                        {{ $doa->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Tambah Doa -->
    <div class="modal fade" id="tambahDoaModal" tabindex="-1" aria-labelledby="tambahDoaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDoaModalLabel">Tambah Doa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.doa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Doa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                name="judul" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" name="kategori"
                                required>
                                <option value="">Pilih Kategori</option>
                                <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>
                                    Kegiatan</option>
                                <option value="pengumuman" {{ old('kategori') == 'pengumuman' ? 'selected' : '' }}>
                                    Pengumuman</option>
                                <option value="artikel" {{ old('kategori') == 'artikel' ? 'selected' : '' }}>Artikel
                                </option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Doa (PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('doa') is-invalid @enderror"
                                name="doa" accept=".pdf" required>
                            <small class="text-muted">Format: PDF. Maksimal 10MB</small>
                            @error('doa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> Simpan Doa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Doa -->
    <div class="modal fade" id="editDoaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Doa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditDoa" method="POST" enctype="multipart/form-data">
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
                            <i class="ti ti-device-floppy"></i> Update Doa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Base URL untuk AJAX
        const baseUrl = "{{ url('admin/doa') }}";

        // Edit function
        function editDoa(id) {
            const modal = new bootstrap.Modal(document.getElementById('editDoaModal'));
            const form = document.getElementById('formEditDoa');
            const loadingDiv = document.getElementById('loadingEdit');
            const contentDiv = document.getElementById('editFormContent');

            // Show loading
            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';
            contentDiv.innerHTML = '';

            // Show modal
            modal.show();

            // Fetch data - PASTIKAN URL BENAR
            fetch(`${baseUrl}/${id}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data); // Debug
                    
                    // Set form action
                    form.action = `${baseUrl}/${data.id}`;

                    // Populate form
                    contentDiv.innerHTML = `
                        <div class="mb-3">
                            <label class="form-label">Judul Doa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" value="${escapeHtml(data.judul)}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="kegiatan" ${data.kategori === 'kegiatan' ? 'selected' : ''}>Kegiatan</option>
                                <option value="pengumuman" ${data.kategori === 'pengumuman' ? 'selected' : ''}>Pengumuman</option>
                                <option value="artikel" ${data.kategori === 'artikel' ? 'selected' : ''}>Artikel</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Doa (PDF)</label>
                            <input type="file" class="form-control" name="doa" accept=".pdf">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah file. Format: PDF. Maksimal 10MB</small>
                            ${data.doa ? `
                                <div class="mt-2">
                                    <small class="text-muted">File saat ini:</small>
                                    <br>
                                    <a href="{{ asset('storage') }}/${data.doa}" target="_blank" class="badge bg-light-info text-info">
                                        <i class="ti ti-file-text"></i> ${escapeHtml(basename(data.doa))}
                                    </a>
                                </div>
                            ` : ''}
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
                            <br><small>Pastikan route /admin/doa/${id}/edit ada dan dapat diakses</small>
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

        // Helper function untuk mendapatkan nama file dari path
        function basename(path) {
            return path ? path.split('/').pop() : '';
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