@extends('layouts.app')

@section('title', 'Edit Template Surat')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/template-surat') }}" class="text-decoration-none text-success">Template Surat</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Template</li>
        </ol>
    </nav>

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-pencil-square"></i></span>
                <div>
                    <span class="badge text-bg-warning mb-2">Template Surat</span>
                    <h4 class="fw-bold text-success mb-1">Edit Template Surat</h4>
                    <p class="text-muted mb-0">Perbarui metadata atau ganti file DOCX template.</p>
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

                    <form action="{{ url('/template-surat/update/' . $template->id_template) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_template" class="form-label fw-semibold">Nama Template <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="nama_template" name="nama_template" value="{{ old('nama_template', $template->nama_template) }}" maxlength="150" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_surat" class="form-label fw-semibold">Jenis Surat <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="jenis_surat" name="jenis_surat" value="{{ old('jenis_surat', $template->jenis_surat) }}" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="required-dot">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif" @selected(old('status', $template->status) === 'Aktif')>Aktif</option>
                                <option value="Tidak Aktif" @selected(old('status', $template->status) === 'Tidak Aktif')>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="file_template" class="form-label fw-semibold">Ganti File Template DOCX</label>
                            <input class="form-control" type="file" id="file_template" name="file_template" accept=".docx">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file template.</small>
                        </div>

                        <div class="mb-4">
                            <div class="fw-semibold mb-2">Placeholder saat ini</div>
                            @forelse(($template->placeholder ?? []) as $placeholder)
                                <span class="badge text-bg-light text-dark border mb-1">${{ '{' . $placeholder . '}' }}</span>
                            @empty
                                <span class="text-muted small">Tidak ada placeholder terdeteksi.</span>
                            @endforelse
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/template-surat') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-warning px-5 rounded-pill"><i class="bi bi-save me-1"></i>Update Template</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection