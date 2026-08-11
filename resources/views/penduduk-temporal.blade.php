@extends('layouts.app')

@section('title', 'Data Penduduk Temporal')

@section('content')
@php
    $dashboardUrl = Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard');
    $oldForm = old('_form');
    $activeTab = session('active_tab', $oldForm === 'tambah' ? 'tambah' : 'cek');
    $showEditForm = $oldForm === 'edit' && $penduduk;
    $jenisKelaminOptions = ['Laki-laki', 'Perempuan'];
    $agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
@endphp

<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ $dashboardUrl }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Penduduk Temporal</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>Periksa kembali isian data penduduk.
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-person-vcard"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Data Bantu</span>
                    <h4 class="fw-bold text-success mb-1">Data Penduduk Temporal</h4>
                    <p class="text-muted mb-0">Kelola data identitas sementara untuk kebutuhan pembuatan surat.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <ul class="nav nav-pills gap-2 mb-4" id="pendudukTemporalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'cek' ? 'active' : '' }}" id="cek-data-tab" data-bs-toggle="pill" data-bs-target="#cek-data" type="button" role="tab" aria-controls="cek-data" aria-selected="{{ $activeTab === 'cek' ? 'true' : 'false' }}">
                        <i class="bi bi-search me-2"></i>Cek Data
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'tambah' ? 'active' : '' }}" id="tambah-data-tab" data-bs-toggle="pill" data-bs-target="#tambah-data" type="button" role="tab" aria-controls="tambah-data" aria-selected="{{ $activeTab === 'tambah' ? 'true' : 'false' }}">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Data
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pendudukTemporalTabsContent">
                <div class="tab-pane fade {{ $activeTab === 'cek' ? 'show active' : '' }}" id="cek-data" role="tabpanel" aria-labelledby="cek-data-tab" tabindex="0">
                    <form action="{{ url('/penduduk-temporal') }}" method="GET" class="row g-3 align-items-end mb-4">
                        <div class="col-lg-8">
                            <label for="nikSearch" class="form-label fw-semibold">NIK</label>
                            <input type="text" name="nik" id="nikSearch" class="form-control" value="{{ $nik }}" maxlength="20" placeholder="Masukkan NIK penduduk">
                        </div>
                        <div class="col-lg-4">
                            <button type="submit" class="btn btn-success rounded-pill px-4 w-100">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                        </div>
                    </form>

                    @if($nik === '')
                        <div class="empty-state border rounded-4">
                            <i class="bi bi-person-vcard d-block mb-2"></i>
                            <strong>Masukkan NIK untuk cek data</strong>
                            <p class="mb-0 small">Data penduduk akan ditampilkan jika NIK sudah tersimpan.</p>
                        </div>
                    @elseif($notFound)
                        <div class="alert alert-warning border-0 rounded-4 mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>Data penduduk dengan NIK <strong>{{ $nik }}</strong> tidak ditemukan.
                        </div>
                    @elseif($penduduk)
                        <div class="border rounded-4 p-4">
                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                                <div>
                                    <h5 class="fw-bold text-success mb-1">{{ $penduduk->nama }}</h5>
                                    <p class="text-muted mb-0">NIK {{ $penduduk->nik }}</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-warning rounded-pill px-4" type="button" data-bs-toggle="collapse" data-bs-target="#editPendudukForm" aria-expanded="{{ $showEditForm ? 'true' : 'false' }}" aria-controls="editPendudukForm">
                                        <i class="bi bi-pencil-square me-2"></i>Edit
                                    </button>
                                    <form action="{{ url('/penduduk-temporal/delete/' . $penduduk->id) }}" method="POST" onsubmit="return confirm('Hapus data penduduk {{ $penduduk->nik }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-xl-4">
                                    <div class="user-info-box h-100">
                                        <small class="text-muted d-block">Jenis Kelamin</small>
                                        <strong>{{ $penduduk->jenis_kelamin ?: '-' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="user-info-box h-100">
                                        <small class="text-muted d-block">Bin/Binti</small>
                                        <strong>{{ $penduduk->bin_binti ?: '-' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="user-info-box h-100">
                                        <small class="text-muted d-block">Tempat/Tanggal Lahir</small>
                                        <strong>{{ $penduduk->tempat_lahir ?: '-' }}{{ $penduduk->tanggal_lahir ? ', ' . $penduduk->tanggal_lahir->format('d-m-Y') : '' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="user-info-box h-100">
                                        <small class="text-muted d-block">Kewarganegaraan</small>
                                        <strong>{{ $penduduk->kewarganegaraan ?: '-' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="user-info-box h-100">
                                        <small class="text-muted d-block">Agama</small>
                                        <strong>{{ $penduduk->agama ?: '-' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="user-info-box h-100">
                                        <small class="text-muted d-block">Pekerjaan</small>
                                        <strong>{{ $penduduk->pekerjaan ?: '-' }}</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="user-info-box">
                                        <small class="text-muted d-block">Alamat</small>
                                        <strong>{{ $penduduk->alamat ?: '-' }}</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Terakhir digunakan/diperbarui: {{ $penduduk->last_used_at ? $penduduk->last_used_at->format('d-m-Y H:i') : '-' }}</small>
                                </div>
                            </div>

                            <div class="collapse {{ $showEditForm ? 'show' : '' }}" id="editPendudukForm">
                                <div class="border-top pt-4">
                                    <h6 class="form-section-title mb-3">Edit Data Penduduk</h6>
                                    <form action="{{ url('/penduduk-temporal/update/' . $penduduk->id) }}" method="POST" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_form" value="edit">

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">NIK <span class="required-dot">*</span></label>
                                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('nik', $penduduk->nik) : $penduduk->nik }}" maxlength="20" required>
                                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nama Lengkap <span class="required-dot">*</span></label>
                                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('nama', $penduduk->nama) : $penduduk->nama }}" maxlength="150" required>
                                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                                            @php $editJenisKelamin = $oldForm === 'edit' ? old('jenis_kelamin', $penduduk->jenis_kelamin) : $penduduk->jenis_kelamin; @endphp
                                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                                <option value="">Pilih jenis kelamin</option>
                                                @foreach($jenisKelaminOptions as $option)
                                                    <option value="{{ $option }}" {{ $editJenisKelamin === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Bin/Binti</label>
                                            <input type="text" name="bin_binti" class="form-control @error('bin_binti') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('bin_binti', $penduduk->bin_binti) : $penduduk->bin_binti }}" maxlength="150">
                                            @error('bin_binti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('tempat_lahir', $penduduk->tempat_lahir) : $penduduk->tempat_lahir }}" maxlength="100">
                                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                                            @php $editTanggalLahir = $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->format('Y-m-d') : ''; @endphp
                                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('tanggal_lahir', $editTanggalLahir) : $editTanggalLahir }}">
                                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kewarganegaraan</label>
                                            <input type="text" name="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('kewarganegaraan', $penduduk->kewarganegaraan) : $penduduk->kewarganegaraan }}" maxlength="100">
                                            @error('kewarganegaraan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Agama</label>
                                            @php $editAgama = $oldForm === 'edit' ? old('agama', $penduduk->agama) : $penduduk->agama; @endphp
                                            <select name="agama" class="form-select @error('agama') is-invalid @enderror">
                                                <option value="">Pilih agama</option>
                                                @foreach($agamaOptions as $option)
                                                    <option value="{{ $option }}" {{ $editAgama === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @error('agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Pekerjaan</label>
                                            <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ $oldForm === 'edit' ? old('pekerjaan', $penduduk->pekerjaan) : $penduduk->pekerjaan }}" maxlength="100">
                                            @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Alamat</label>
                                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ $oldForm === 'edit' ? old('alamat', $penduduk->alamat) : $penduduk->alamat }}</textarea>
                                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade {{ $activeTab === 'tambah' ? 'show active' : '' }}" id="tambah-data" role="tabpanel" aria-labelledby="tambah-data-tab" tabindex="0">
                    <form action="{{ url('/penduduk-temporal/store') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="_form" value="tambah">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="required-dot">*</span></label>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('nik') : '' }}" maxlength="20" required>
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="required-dot">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('nama') : '' }}" maxlength="150" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">Pilih jenis kelamin</option>
                                @foreach($jenisKelaminOptions as $option)
                                    <option value="{{ $option }}" {{ $oldForm === 'tambah' && old('jenis_kelamin') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bin/Binti</label>
                            <input type="text" name="bin_binti" class="form-control @error('bin_binti') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('bin_binti') : '' }}" maxlength="150">
                            @error('bin_binti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('tempat_lahir') : '' }}" maxlength="100">
                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('tanggal_lahir') : '' }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('kewarganegaraan') : '' }}" maxlength="100">
                            @error('kewarganegaraan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Agama</label>
                            <select name="agama" class="form-select @error('agama') is-invalid @enderror">
                                <option value="">Pilih agama</option>
                                @foreach($agamaOptions as $option)
                                    <option value="{{ $option }}" {{ $oldForm === 'tambah' && old('agama') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ $oldForm === 'tambah' ? old('pekerjaan') : '' }}" maxlength="100">
                            @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ $oldForm === 'tambah' ? old('alamat') : '' }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
