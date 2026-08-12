@extends('layouts.app')

@section('title', 'Pilih Jenis Surat Keluar')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/surat-keluar') }}" class="text-decoration-none text-success">Surat Keluar</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pilih Jenis Input</li>
        </ol>
    </nav>

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-send"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Surat Keluar</span>
                    <h4 class="fw-bold text-success mb-1">Pilih Cara Membuat Surat Keluar</h4>
                    <p class="text-muted mb-0">Pilih upload dokumen manual atau buat surat dari Template Surat.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-5">
            <div class="card module-card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <span class="module-icon fs-3"><i class="bi bi-upload"></i></span>
                        <div>
                            <h5 class="fw-bold text-success mb-1">Tambah Surat Keluar Manual</h5>
                            <p class="text-muted mb-0">Gunakan untuk mengunggah dokumen surat yang sudah jadi di luar sistem.</p>
                        </div>
                    </div>
                    <ul class="text-muted small ps-3 mb-4">
                        <li>Upload PDF, DOC/DOCX, gambar, atau XLS/XLSX.</li>
                        <li>Flow upload manual yang sudah berjalan tetap dipakai.</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ url('/surat-keluar/create/manual') }}" class="btn btn-success rounded-pill px-4 w-100">
                            <i class="bi bi-upload me-2"></i>Tambah Surat Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card module-card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <span class="module-icon fs-3"><i class="bi bi-file-earmark-richtext"></i></span>
                        <div>
                            <h5 class="fw-bold text-success mb-1">Buat Surat dari Template</h5>
                            <p class="text-muted mb-0">Gunakan Template Surat DOCX, isi placeholder, lalu generate PDF.</p>
                        </div>
                    </div>
                    <ul class="text-muted small ps-3 mb-4">
                        <li>Placeholder template tetap muncul otomatis.</li>
                        <li>Preview, download, dan simpan PDF tetap tersedia.</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ url('/surat-keluar/create/template') }}" class="btn btn-outline-success rounded-pill px-4 w-100">
                            <i class="bi bi-file-earmark-richtext me-2"></i>Buat Surat dari Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection