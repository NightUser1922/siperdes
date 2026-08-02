@extends('layouts.app')

@section('title', 'Tambah Template Surat')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/template-surat') }}" class="text-decoration-none text-success">Template Surat</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Template</li>
        </ol>
    </nav>

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-file-earmark-plus"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Template Surat</span>
                    <h4 class="fw-bold text-success mb-1">Tambah Template Surat</h4>
                    <p class="text-muted mb-0">Upload DOCX berisi placeholder agar bisa digunakan untuk generate Surat Keluar.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card shadow-sm border-0">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <div class="fw-semibold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Periksa kembali input berikut:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/template-surat/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_template" class="form-label fw-semibold">Nama Template <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="nama_template" name="nama_template" value="{{ old('nama_template') }}" maxlength="150" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_surat" class="form-label fw-semibold">Jenis Surat <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="jenis_surat" name="jenis_surat" value="{{ old('jenis_surat') }}" maxlength="100" placeholder="Contoh: Surat Keterangan, Surat Undangan" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="required-dot">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif" @selected(old('status', 'Aktif') === 'Aktif')>Aktif</option>
                                <option value="Tidak Aktif" @selected(old('status') === 'Tidak Aktif')>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="file_template" class="form-label fw-semibold">File Template DOCX <span class="required-dot">*</span></label>
                            <input class="form-control" type="file" id="file_template" name="file_template" accept=".docx" required>
                            <small class="text-muted">Gunakan placeholder seperti ${nomor_surat}, ${tanggal_surat}, ${tujuan}, ${perihal}, atau placeholder custom lain.</small>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/template-surat') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-success px-5 rounded-pill"><i class="bi bi-save me-1"></i>Simpan Template</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection