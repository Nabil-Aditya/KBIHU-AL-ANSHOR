@extends('layouts.admin')

@section('title', 'Kelola Jamaah KBIHU')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Kelola Jamaah KBIHU</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kelola Jamaah</li>
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

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-light-primary rounded p-3">
                                <i class="ti ti-hajj text-primary fs-6"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">{{ $totalHaji }}</h4>
                            <p class="mb-0 text-muted">Jamaah Haji</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-light-info rounded p-3">
                                <i class="ti ti-mosque text-info fs-6"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">{{ $totalUmrah }}</h4>
                            <p class="mb-0 text-muted">Jamaah Umrah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-light-warning rounded p-3">
                                <i class="ti ti-star text-warning fs-6"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">{{ $totalHajiKhusus }}</h4>
                            <p class="mb-0 text-muted">Haji Khusus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-light-success rounded p-3">
                                <i class="ti ti-check text-success fs-6"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold">{{ $totalLunas }}</h4>
                            <p class="mb-0 text-muted">Lunas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold">Daftar Jamaah</h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.jamaah.export') }}" method="GET" class="d-inline">
                        @if(request('jenis_ibadah') && request('jenis_ibadah') != 'semua')
                        <input type="hidden" name="jenis_ibadah" value="{{ request('jenis_ibadah') }}">
                        @endif
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-download"></i> Export
                        </button>
                    </form>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahJamaahModal">
                        <i class="ti ti-plus"></i> Tambah Jamaah
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Jenis Ibadah</label>
                                    <select name="jenis_ibadah" class="form-select">
                                        <option value="semua">Semua</option>
                                        <option value="haji" {{ request('jenis_ibadah') == 'haji' ? 'selected' : '' }}>Haji</option>
                                        <option value="umrah" {{ request('jenis_ibadah') == 'umrah' ? 'selected' : '' }}>Umrah</option>
                                        <option value="haji_khusus" {{ request('jenis_ibadah') == 'haji_khusus' ? 'selected' : '' }}>Haji Khusus</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status Pendaftaran</label>
                                    <select name="status_pendaftaran" class="form-select">
                                        <option value="semua">Semua</option>
                                        <option value="calon" {{ request('status_pendaftaran') == 'calon' ? 'selected' : '' }}>Calon</option>
                                        <option value="terdaftar" {{ request('status_pendaftaran') == 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                        <option value="berkas_ok" {{ request('status_pendaftaran') == 'berkas_ok' ? 'selected' : '' }}>Berkas OK</option>
                                        <option value="berangkat" {{ request('status_pendaftaran') == 'berangkat' ? 'selected' : '' }}>Berangkat</option>
                                        <option value="selesai" {{ request('status_pendaftaran') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ request('status_pendaftaran') == 'batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status Pembayaran</label>
                                    <select name="status_pembayaran" class="form-select">
                                        <option value="semua">Semua</option>
                                        <option value="belum_bayar" {{ request('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="dp" {{ request('status_pembayaran') == 'dp' ? 'selected' : '' }}>DP</option>
                                        <option value="cicil" {{ request('status_pembayaran') == 'cicil' ? 'selected' : '' }}>Cicil</option>
                                        <option value="lunas" {{ request('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Cari</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Nama/NIK/No HP..." value="{{ request('search') }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <a href="{{ route('admin.jamaah.index') }}" class="btn btn-outline-secondary">
                                            <i class="ti ti-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="jamaahTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Ibadah</th>
                            <th>Kontak</th>
                            <th>Status Pendaftaran</th>
                            <th>Pembayaran</th>
                            <th>Sisa</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jamaahs as $index => $jamaah)
                        <tr>
                            <td>{{ $jamaahs->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong>{{ $jamaah->nama_lengkap }}</strong>
                                    <small class="text-muted">NIK: {{ $jamaah->nik ?? '-' }}</small>
                                    <small class="text-muted">TTL: {{ $jamaah->tempat_lahir }}, {{ date('d/m/Y', strtotime($jamaah->tanggal_lahir)) }}</small>
                                </div>
                            </td>
                            <td>
                                @if($jamaah->jenis_ibadah == 'haji')
                                <span class="badge bg-success">HAJI</span>
                                @if($jamaah->gelombang)
                                <small class="d-block">Gel. {{ $jamaah->gelombang }}</small>
                                @endif
                                @elseif($jamaah->jenis_ibadah == 'umrah')
                                <span class="badge bg-info">UMRAH</span>
                                @elseif($jamaah->jenis_ibadah == 'haji_khusus')
                                <span class="badge bg-warning">HAJI KHUSUS</span>
                                @endif
                                <small class="d-block">{{ $jamaah->paket ?? '-' }}</small>
                                <small class="d-block">{{ $jamaah->tahun_berangkat ?? '-' }}</small>
                            </td>
                            <td>
                                <small class="d-block"><i class="ti ti-phone"></i> {{ $jamaah->no_telepon }}</small>
                                <small class="d-block"><i class="ti ti-mail"></i> {{ $jamaah->email ?? '-' }}</small>
                                <small class="d-block text-truncate" style="max-width: 200px;">
                                    <i class="ti ti-map-pin"></i> {{ Str::limit($jamaah->alamat, 50) }}
                                </small>
                            </td>
                            <td>
                                @php
                                $statusColors = [
                                    'calon' => 'secondary',
                                    'terdaftar' => 'info',
                                    'berkas_ok' => 'primary',
                                    'berangkat' => 'warning',
                                    'selesai' => 'success',
                                    'batal' => 'danger'
                                ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$jamaah->status_pendaftaran] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $jamaah->status_pendaftaran)) }}
                                </span>
                                @if($jamaah->is_mahram)
                                <br><small class="text-success"><i class="ti ti-check"></i> Mahram</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $jamaah->status_pembayaran_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $jamaah->status_pembayaran)) }}
                                </span>
                                <div class="mt-1">
                                    <small class="text-muted">Total: Rp {{ number_format($jamaah->total_biaya, 0, ',', '.') }}</small>
                                    <br>
                                    <small class="text-success">Terbayar: Rp {{ number_format($jamaah->terbayar, 0, ',', '.') }}</small>
                                </div>
                            </td>
                            <td class="text-end">
                                <strong class="{{ $jamaah->sisa_pembayaran > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($jamaah->sisa_pembayaran, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-light-info" onclick="showDetail({{ $jamaah->id }})" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light-warning" onclick="editJamaah({{ $jamaah->id }})" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.jamaah.destroy', $jamaah->id) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data jamaah ini?')">
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
                                    <i class="ti ti-users" style="font-size: 50px; color: #ddd;"></i>
                                </div>
                                <p class="text-muted">Belum ada data jamaah</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($jamaahs->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $jamaahs->firstItem() }} - {{ $jamaahs->lastItem() }} dari {{ $jamaahs->total() }} data
                </div>
                {{ $jamaahs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Jamaah -->
<div class="modal fade" id="tambahJamaahModal" tabindex="-1" aria-labelledby="tambahJamaahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahJamaahModalLabel">Tambah Jamaah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.jamaah.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Data Pribadi -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-user"></i> Data Pribadi
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                           name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                    @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">NIK (16 digit)</label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                           name="nik" value="{{ old('nik') }}" maxlength="16">
                                    @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">No. Paspor</label>
                                    <input type="text" class="form-control @error('no_paspor') is-invalid @enderror" 
                                           name="no_paspor" value="{{ old('no_paspor') }}">
                                    @error('no_paspor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                                        <option value="">Pilih</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                           name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                    @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                           name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                    @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="form-check pt-4">
                                        <input class="form-check-input" type="checkbox" name="is_mahram" id="is_mahram_tambah" 
                                               value="1" {{ old('is_mahram') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_mahram_tambah">
                                            Status Mahram
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                              name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">No. Telepon/HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                           name="no_telepon" value="{{ old('no_telepon') }}" required>
                                    @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mahram Dengan (jika wanita)</label>
                                    <input type="text" class="form-control @error('mahram_dengan') is-invalid @enderror" 
                                           name="mahram_dengan" value="{{ old('mahram_dengan') }}">
                                    @error('mahram_dengan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data KBIHU -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-mosque"></i> Data KBIHU
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Jenis Ibadah</label>
                                    <select class="form-select @error('jenis_ibadah') is-invalid @enderror" name="jenis_ibadah" id="jenisIbadahTambah">
                                        <option value="">Pilih Ibadah</option>
                                        <option value="haji" {{ old('jenis_ibadah') == 'haji' ? 'selected' : '' }}>Haji</option>
                                        <option value="umrah" {{ old('jenis_ibadah') == 'umrah' ? 'selected' : '' }}>Umrah</option>
                                        <option value="haji_khusus" {{ old('jenis_ibadah') == 'haji_khusus' ? 'selected' : '' }}>Haji Khusus</option>
                                    </select>
                                    @error('jenis_ibadah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gelombang (Haji)</label>
                                    <select class="form-select @error('gelombang') is-invalid @enderror" name="gelombang" id="gelombangTambah">
                                        <option value="">Pilih Gelombang</option>
                                        <option value="1" {{ old('gelombang') == '1' ? 'selected' : '' }}>Gelombang 1</option>
                                        <option value="2" {{ old('gelombang') == '2' ? 'selected' : '' }}>Gelombang 2</option>
                                    </select>
                                    @error('gelombang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Paket</label>
                                    <input type="text" class="form-control @error('paket') is-invalid @enderror" 
                                           name="paket" value="{{ old('paket') }}" placeholder="Nama Paket">
                                    @error('paket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tahun Berangkat</label>
                                    <input type="number" class="form-control @error('tahun_berangkat') is-invalid @enderror" 
                                           name="tahun_berangkat" value="{{ old('tahun_berangkat') }}" 
                                           min="2023" max="2100" placeholder="2024">
                                    @error('tahun_berangkat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status Pendaftaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_pendaftaran') is-invalid @enderror" name="status_pendaftaran" required>
                                        <option value="">Pilih Status</option>
                                        <option value="calon" {{ old('status_pendaftaran') == 'calon' ? 'selected' : '' }}>Calon</option>
                                        <option value="terdaftar" {{ old('status_pendaftaran') == 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                        <option value="berkas_ok" {{ old('status_pendaftaran') == 'berkas_ok' ? 'selected' : '' }}>Berkas OK</option>
                                        <option value="berangkat" {{ old('status_pendaftaran') == 'berangkat' ? 'selected' : '' }}>Berangkat</option>
                                        <option value="selesai" {{ old('status_pendaftaran') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ old('status_pendaftaran') == 'batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    @error('status_pendaftaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_pembayaran') is-invalid @enderror" name="status_pembayaran" required id="statusPembayaranTambah">
                                        <option value="">Pilih Status</option>
                                        <option value="belum_bayar" {{ old('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="dp" {{ old('status_pembayaran') == 'dp' ? 'selected' : '' }}>DP</option>
                                        <option value="cicil" {{ old('status_pembayaran') == 'cicil' ? 'selected' : '' }}>Cicil</option>
                                        <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    </select>
                                    @error('status_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Keuangan -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-cash"></i> Data Keuangan
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Total Biaya (Rp)</label>
                                    <input type="number" class="form-control @error('total_biaya') is-invalid @enderror" 
                                           name="total_biaya" value="{{ old('total_biaya') }}" 
                                           min="0" step="100000" id="totalBiayaTambah">
                                    @error('total_biaya')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Uang DP (Rp)</label>
                                    <input type="number" class="form-control @error('uang_dp') is-invalid @enderror" 
                                           name="uang_dp" value="{{ old('uang_dp') }}" 
                                           min="0" step="100000" id="uangDpTambah">
                                    @error('uang_dp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Terbayar (Rp)</label>
                                    <input type="number" class="form-control @error('terbayar') is-invalid @enderror" 
                                           name="terbayar" value="{{ old('terbayar') }}" 
                                           min="0" step="100000" id="terbayarTambah" readonly>
                                    @error('terbayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Tambahan -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-info-circle"></i> Data Tambahan
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelompok</label>
                                    <input type="text" class="form-control @error('kelompok') is-invalid @enderror" 
                                           name="kelompok" value="{{ old('kelompok') }}">
                                    @error('kelompok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pembimbing</label>
                                    <input type="text" class="form-control @error('pembimbing') is-invalid @enderror" 
                                           name="pembimbing" value="{{ old('pembimbing') }}">
                                    @error('pembimbing')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jamaah -->
<div class="modal fade" id="editJamaahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Jamaah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditJamaah" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div id="loadingEdit" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="editFormContent">
                        <!-- Content akan diisi via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-device-floppy"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Jamaah -->
<div class="modal fade" id="detailJamaahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Jamaah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadingDetail" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="detailJamaahContent">
                    <!-- Content akan diisi via JavaScript -->
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
    const baseUrl = "{{ url('admin/jamaah') }}";
    
    // Auto-calculate terbayar untuk modal tambah
    document.addEventListener('DOMContentLoaded', function() {
        // Untuk modal tambah
        const statusPembayaranTambah = document.getElementById('statusPembayaranTambah');
        const totalBiayaTambah = document.getElementById('totalBiayaTambah');
        const uangDpTambah = document.getElementById('uangDpTambah');
        const terbayarTambah = document.getElementById('terbayarTambah');
        
        // Untuk modal edit (akan diinisialisasi nanti)
        let statusPembayaranEdit, totalBiayaEdit, uangDpEdit, terbayarEdit;
        
        // Show/hide gelombang berdasarkan jenis ibadah untuk modal tambah
        const jenisIbadahTambah = document.getElementById('jenisIbadahTambah');
        const gelombangTambah = document.getElementById('gelombangTambah');
        
        if (jenisIbadahTambah && gelombangTambah) {
            jenisIbadahTambah.addEventListener('change', function() {
                if (this.value === 'haji') {
                    gelombangTambah.disabled = false;
                    gelombangTambah.parentElement.style.display = 'block';
                } else {
                    gelombangTambah.disabled = true;
                    gelombangTambah.value = '';
                    gelombangTambah.parentElement.style.display = 'block';
                }
            });
        }
        
        function calculateTerbayarTambah() {
            const status = statusPembayaranTambah.value;
            const total = parseFloat(totalBiayaTambah.value) || 0;
            const dp = parseFloat(uangDpTambah.value) || 0;
            
            if (status === 'dp') {
                terbayarTambah.value = dp;
            } else if (status === 'lunas') {
                terbayarTambah.value = total;
            } else if (status === 'cicil') {
                // Untuk cicil, biarkan user input manual
                terbayarTambah.removeAttribute('readonly');
            } else {
                terbayarTambah.value = 0;
                terbayarTambah.setAttribute('readonly', true);
            }
        }
        
        if (statusPembayaranTambah && totalBiayaTambah && uangDpTambah && terbayarTambah) {
            statusPembayaranTambah.addEventListener('change', calculateTerbayarTambah);
            totalBiayaTambah.addEventListener('input', calculateTerbayarTambah);
            uangDpTambah.addEventListener('input', calculateTerbayarTambah);
        }
        
        // Fungsi untuk edit jamaah
        window.editJamaah = function(id) {
            const modal = new bootstrap.Modal(document.getElementById('editJamaahModal'));
            const form = document.getElementById('formEditJamaah');
            const loadingDiv = document.getElementById('loadingEdit');
            const contentDiv = document.getElementById('editFormContent');
            
            // Show loading
            loadingDiv.style.display = 'block';
            contentDiv.innerHTML = '';
            
            // Show modal
            modal.show();
            
            // Fetch data
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
                // Set form action
                form.action = `${baseUrl}/${data.id}`;
                
                // Format tanggal untuk input
                const tanggalLahir = new Date(data.tanggal_lahir).toISOString().split('T')[0];
                
                // Populate form
                contentDiv.innerHTML = `
                    <div class="row">
                        <!-- Data Pribadi -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-user"></i> Data Pribadi
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_lengkap" value="${escapeHtml(data.nama_lengkap)}" required>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">NIK (16 digit)</label>
                                    <input type="text" class="form-control" name="nik" value="${escapeHtml(data.nik || '')}" maxlength="16">
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">No. Paspor</label>
                                    <input type="text" class="form-control" name="no_paspor" value="${escapeHtml(data.no_paspor || '')}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select" name="jenis_kelamin" required>
                                        <option value="">Pilih</option>
                                        <option value="L" ${data.jenis_kelamin === 'L' ? 'selected' : ''}>Laki-laki</option>
                                        <option value="P" ${data.jenis_kelamin === 'P' ? 'selected' : ''}>Perempuan</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="tempat_lahir" value="${escapeHtml(data.tempat_lahir)}" required>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_lahir" value="${tanggalLahir}" required>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="form-check pt-4">
                                        <input class="form-check-input" type="checkbox" name="is_mahram" id="is_mahram_edit" 
                                               value="1" ${data.is_mahram ? 'checked' : ''}>
                                        <label class="form-check-label" for="is_mahram_edit">
                                            Status Mahram
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="alamat" rows="2" required>${escapeHtml(data.alamat)}</textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">No. Telepon/HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_telepon" value="${escapeHtml(data.no_telepon)}" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="${escapeHtml(data.email || '')}">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mahram Dengan (jika wanita)</label>
                                    <input type="text" class="form-control" name="mahram_dengan" value="${escapeHtml(data.mahram_dengan || '')}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data KBIHU -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-mosque"></i> Data KBIHU
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Jenis Ibadah</label>
                                    <select class="form-select" name="jenis_ibadah" id="jenisIbadahEdit">
                                        <option value="">Pilih Ibadah</option>
                                        <option value="haji" ${data.jenis_ibadah === 'haji' ? 'selected' : ''}>Haji</option>
                                        <option value="umrah" ${data.jenis_ibadah === 'umrah' ? 'selected' : ''}>Umrah</option>
                                        <option value="haji_khusus" ${data.jenis_ibadah === 'haji_khusus' ? 'selected' : ''}>Haji Khusus</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gelombang (Haji)</label>
                                    <select class="form-select" name="gelombang" id="gelombangEdit" ${data.jenis_ibadah !== 'haji' ? 'disabled' : ''}>
                                        <option value="">Pilih Gelombang</option>
                                        <option value="1" ${data.gelombang === '1' ? 'selected' : ''}>Gelombang 1</option>
                                        <option value="2" ${data.gelombang === '2' ? 'selected' : ''}>Gelombang 2</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Paket</label>
                                    <input type="text" class="form-control" name="paket" value="${escapeHtml(data.paket || '')}" placeholder="Nama Paket">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tahun Berangkat</label>
                                    <input type="number" class="form-control" name="tahun_berangkat" value="${data.tahun_berangkat || ''}" 
                                           min="2023" max="2100" placeholder="2024">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status Pendaftaran <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status_pendaftaran" required>
                                        <option value="">Pilih Status</option>
                                        <option value="calon" ${data.status_pendaftaran === 'calon' ? 'selected' : ''}>Calon</option>
                                        <option value="terdaftar" ${data.status_pendaftaran === 'terdaftar' ? 'selected' : ''}>Terdaftar</option>
                                        <option value="berkas_ok" ${data.status_pendaftaran === 'berkas_ok' ? 'selected' : ''}>Berkas OK</option>
                                        <option value="berangkat" ${data.status_pendaftaran === 'berangkat' ? 'selected' : ''}>Berangkat</option>
                                        <option value="selesai" ${data.status_pendaftaran === 'selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="batal" ${data.status_pendaftaran === 'batal' ? 'selected' : ''}>Batal</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status_pembayaran" required id="statusPembayaranEdit">
                                        <option value="">Pilih Status</option>
                                        <option value="belum_bayar" ${data.status_pembayaran === 'belum_bayar' ? 'selected' : ''}>Belum Bayar</option>
                                        <option value="dp" ${data.status_pembayaran === 'dp' ? 'selected' : ''}>DP</option>
                                        <option value="cicil" ${data.status_pembayaran === 'cicil' ? 'selected' : ''}>Cicil</option>
                                        <option value="lunas" ${data.status_pembayaran === 'lunas' ? 'selected' : ''}>Lunas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Keuangan -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-cash"></i> Data Keuangan
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Total Biaya (Rp)</label>
                                    <input type="number" class="form-control" name="total_biaya" value="${data.total_biaya || 0}" 
                                           min="0" step="100000" id="totalBiayaEdit">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Uang DP (Rp)</label>
                                    <input type="number" class="form-control" name="uang_dp" value="${data.uang_dp || 0}" 
                                           min="0" step="100000" id="uangDpEdit">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Terbayar (Rp)</label>
                                    <input type="number" class="form-control" name="terbayar" value="${data.terbayar || 0}" 
                                           min="0" step="100000" id="terbayarEdit">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Tambahan -->
                        <div class="col-md-12 mb-4">
                            <h6 class="card-title mb-3 border-bottom pb-2">
                                <i class="ti ti-info-circle"></i> Data Tambahan
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelompok</label>
                                    <input type="text" class="form-control" name="kelompok" value="${escapeHtml(data.kelompok || '')}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pembimbing</label>
                                    <input type="text" class="form-control" name="pembimbing" value="${escapeHtml(data.pembimbing || '')}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="2">${escapeHtml(data.keterangan || '')}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Initialize event listeners for edit modal
                initEditModalListeners();
                
                // Hide loading and show content
                loadingDiv.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                contentDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle"></i> Gagal memuat data: ${error.message}
                    </div>
                `;
                loadingDiv.style.display = 'none';
            });
        };
        
        // Fungsi untuk show detail jamaah
        window.showDetail = function(id) {
            const modal = new bootstrap.Modal(document.getElementById('detailJamaahModal'));
            const loadingDiv = document.getElementById('loadingDetail');
            const contentDiv = document.getElementById('detailJamaahContent');
            
            // Show loading
            loadingDiv.style.display = 'block';
            contentDiv.innerHTML = '';
            
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
                const tanggalLahir = new Date(data.tanggal_lahir);
                const formattedDate = tanggalLahir.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                
                const sisaPembayaran = data.total_biaya - data.terbayar;
                const percentage = data.total_biaya > 0 ? (data.terbayar / data.total_biaya) * 100 : 0;
                const color = percentage == 100 ? 'success' : (percentage >= 50 ? 'warning' : 'danger');
                
                // Status colors
                const statusColors = {
                    'calon': 'secondary',
                    'terdaftar': 'info',
                    'berkas_ok': 'primary',
                    'berangkat': 'warning',
                    'selesai': 'success',
                    'batal': 'danger'
                };
                
                const pembayaranColors = {
                    'belum_bayar': 'danger',
                    'dp': 'warning',
                    'cicil': 'info',
                    'lunas': 'success'
                };
                
                contentDiv.innerHTML = `
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <h6 class="mb-3 border-bottom pb-2">
                                <i class="ti ti-user"></i> Data Pribadi
                            </h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nama Lengkap</th>
                                    <td><strong>${escapeHtml(data.nama_lengkap)}</strong></td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>${escapeHtml(data.nik || '-')}</td>
                                </tr>
                                <tr>
                                    <th>No. Paspor</th>
                                    <td>${escapeHtml(data.no_paspor || '-')}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                                </tr>
                                <tr>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <td>${escapeHtml(data.tempat_lahir)}, ${formattedDate}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>${escapeHtml(data.alamat)}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon/HP</th>
                                    <td>${escapeHtml(data.no_telepon)}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>${escapeHtml(data.email || '-')}</td>
                                </tr>
                                <tr>
                                    <th>Status Mahram</th>
                                    <td>
                                        ${data.is_mahram ? 
                                            '<span class="badge bg-success">Ya</span>' + 
                                            (data.mahram_dengan ? `<span class="ms-2">dengan ${escapeHtml(data.mahram_dengan)}</span>` : '') 
                                            : '<span class="badge bg-secondary">Tidak</span>'}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-12 mb-4">
                            <h6 class="mb-3 border-bottom pb-2">
                                <i class="ti ti-mosque"></i> Data KBIHU
                            </h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Jenis Ibadah</th>
                                    <td>
                                        ${data.jenis_ibadah === 'haji' ? 
                                            '<span class="badge bg-success">HAJI</span>' : 
                                            data.jenis_ibadah === 'umrah' ? 
                                            '<span class="badge bg-info">UMRAH</span>' : 
                                            data.jenis_ibadah === 'haji_khusus' ? 
                                            '<span class="badge bg-warning">HAJI KHUSUS</span>' : 
                                            '<span class="badge bg-secondary">-</span>'}
                                        ${data.gelombang ? `<br><small>Gel. ${data.gelombang}</small>` : ''}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Paket</th>
                                    <td>${escapeHtml(data.paket || '-')}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Berangkat</th>
                                    <td>${data.tahun_berangkat || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Status Pendaftaran</th>
                                    <td>
                                        <span class="badge bg-${statusColors[data.status_pendaftaran] || 'secondary'}">
                                            ${data.status_pendaftaran.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kelompok</th>
                                    <td>${escapeHtml(data.kelompok || '-')}</td>
                                </tr>
                                <tr>
                                    <th>Pembimbing</th>
                                    <td>${escapeHtml(data.pembimbing || '-')}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-12 mb-4">
                            <h6 class="mb-3 border-bottom pb-2">
                                <i class="ti ti-cash"></i> Data Keuangan
                            </h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Status Pembayaran</th>
                                    <td>
                                        <span class="badge bg-${pembayaranColors[data.status_pembayaran] || 'secondary'}">
                                            ${data.status_pembayaran.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Biaya</th>
                                    <td><strong>Rp ${formatRupiah(data.total_biaya)}</strong></td>
                                </tr>
                                <tr>
                                    <th>Uang DP</th>
                                    <td>Rp ${formatRupiah(data.uang_dp)}</td>
                                </tr>
                                <tr>
                                    <th>Terbayar</th>
                                    <td><strong class="text-success">Rp ${formatRupiah(data.terbayar)}</strong></td>
                                </tr>
                                <tr>
                                    <th>Sisa Pembayaran</th>
                                    <td>
                                        <strong class="${sisaPembayaran > 0 ? 'text-danger' : 'text-success'}">
                                            Rp ${formatRupiah(sisaPembayaran)}
                                        </strong>
                                    </td>
                                </tr>
                            </table>
                            
                            ${data.total_biaya > 0 ? `
                            <div class="mt-3">
                                <label class="form-label">Progress Pembayaran</label>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-${color}" role="progressbar" 
                                         style="width: ${percentage}%;" 
                                         aria-valuenow="${percentage}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        ${percentage.toFixed(1)}%
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small>Rp ${formatRupiah(data.terbayar)}</small>
                                    <small>Rp ${formatRupiah(data.total_biaya)}</small>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        
                        ${data.keterangan ? `
                        <div class="col-md-12 mb-4">
                            <h6 class="mb-3 border-bottom pb-2">
                                <i class="ti ti-info-circle"></i> Keterangan
                            </h6>
                            <p>${escapeHtml(data.keterangan)}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                // Hide loading
                loadingDiv.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                contentDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle"></i> Gagal memuat data: ${error.message}
                    </div>
                `;
                loadingDiv.style.display = 'none';
            });
        };
        
        // Helper function untuk escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Helper function untuk format Rupiah
        function formatRupiah(amount) {
            if (!amount) return '0';
            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Function untuk inisialisasi event listeners di modal edit
        function initEditModalListeners() {
            // Show/hide gelombang berdasarkan jenis ibadah untuk modal edit
            const jenisIbadahEdit = document.getElementById('jenisIbadahEdit');
            const gelombangEdit = document.getElementById('gelombangEdit');
            
            if (jenisIbadahEdit && gelombangEdit) {
                jenisIbadahEdit.addEventListener('change', function() {
                    if (this.value === 'haji') {
                        gelombangEdit.disabled = false;
                    } else {
                        gelombangEdit.disabled = true;
                        gelombangEdit.value = '';
                    }
                });
            }
            
            // Auto-calculate terbayar untuk modal edit
            statusPembayaranEdit = document.getElementById('statusPembayaranEdit');
            totalBiayaEdit = document.getElementById('totalBiayaEdit');
            uangDpEdit = document.getElementById('uangDpEdit');
            terbayarEdit = document.getElementById('terbayarEdit');
            
            if (statusPembayaranEdit && totalBiayaEdit && uangDpEdit && terbayarEdit) {
                function calculateTerbayarEdit() {
                    const status = statusPembayaranEdit.value;
                    const total = parseFloat(totalBiayaEdit.value) || 0;
                    const dp = parseFloat(uangDpEdit.value) || 0;
                    
                    if (status === 'dp') {
                        terbayarEdit.value = dp;
                    } else if (status === 'lunas') {
                        terbayarEdit.value = total;
                    }
                    // Untuk cicil dan lainnya, biarkan user input
                }
                
                statusPembayaranEdit.addEventListener('change', calculateTerbayarEdit);
                totalBiayaEdit.addEventListener('input', calculateTerbayarEdit);
                uangDpEdit.addEventListener('input', calculateTerbayarEdit);
            }
        }
        
        // Auto-dismiss alerts after 5 seconds
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