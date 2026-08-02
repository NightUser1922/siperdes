@extends('layouts.app')

@section('title', 'Upload Arsip Digital')

@section('content')
@php
    $dashboardUrl = Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard');
@endphp

<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ $dashboardUrl }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/arsip-digital') }}" class="text-decoration-none text-success">Arsip Digital</a></li>
            <li class="breadcrumb-item active" aria-current="page">Upload</li>
        </ol>
    </nav>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0 rounded-4" role="alert">
            <div class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Periksa kembali input berikut:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-cloud-upload"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Google Drive Private</span>
                    <h4 class="fw-bold text-success mb-1">Upload Arsip Digital</h4>
                    <p class="text-muted mb-0">File disimpan secara private di Google Drive dan hanya dapat diakses melalui SIPERDES.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card form-card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ url('/arsip-digital/store') }}" method="POST" enctype="multipart/form-data" class="row g-4">
                @csrf

                <div class="col-12">
                    <div class="form-section-title mb-2">Metadata Arsip</div>
                </div>

                <div class="col-md-6">
                    <label for="nomor_arsip" class="form-label fw-semibold">Nomor Arsip <span class="required-dot">*</span></label>
                    <input type="text" class="form-control @error('nomor_arsip') is-invalid @enderror" id="nomor_arsip" name="nomor_arsip" value="{{ old('nomor_arsip') }}" required>
                    @error('nomor_arsip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="nama_arsip" class="form-label fw-semibold">Nama Arsip <span class="required-dot">*</span></label>
                    <input type="text" class="form-control @error('nama_arsip') is-invalid @enderror" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip') }}" required>
                    @error('nama_arsip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="kategori" class="form-label fw-semibold">Kategori Arsip <span class="required-dot">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="file_arsip" class="form-label fw-semibold">File Arsip <span class="required-dot">*</span></label>
                    <input type="file" class="form-control @error('file_arsip') is-invalid @enderror" id="file_arsip" name="file_arsip" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal 10 MB.</small>
                    @error('file_arsip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Tuliskan keterangan arsip bila diperlukan">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                    <a href="{{ url('/arsip-digital') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection