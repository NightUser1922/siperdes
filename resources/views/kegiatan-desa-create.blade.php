@extends('layouts.app')

@section('title', 'Tambah Kegiatan Desa')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/kegiatan-desa') }}" class="text-decoration-none text-success">Kegiatan Desa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Kegiatan Desa</li>
        </ol>
    </nav>
    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-calendar-event"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Kegiatan Desa</span>
                    <h4 class="fw-bold text-success mb-1">Tambah Kegiatan Desa</h4>
                    <p class="text-muted mb-0">Lengkapi data kegiatan sesuai agenda desa.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-journal-plus"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Form Input</p>
                            <h5 class="mb-0 fw-bold text-success">Data Kegiatan Baru</h5>
                        </div>
                    </div>
                </div>
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

                    <form action="{{ url('/kegiatan-desa/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" maxlength="150" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kegiatan" class="form-label fw-semibold">Tanggal Kegiatan <span class="required-dot">*</span></label>
                            <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label fw-semibold">Lokasi <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" maxlength="100" required>
                        </div>
                        <div class="mb-4">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan <span class="required-dot">*</span></label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="4" required>{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tim_pelaksana" class="form-label fw-semibold">Tim Pelaksana</label>
                            <input type="text" class="form-control" id="tim_pelaksana" name="tim_pelaksana" value="{{ old('tim_pelaksana') }}" maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="penanggung_jawab" class="form-label fw-semibold">Penanggung Jawab</label>
                            <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" maxlength="150">
                        </div>
                        <div class="mb-4">
                            <label for="dokumentasi" class="form-label fw-semibold">Dokumentasi (Upload file)</label>
                            <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Maks 10MB. Tipe: pdf, doc, docx, jpg, jpeg, png.</small>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/kegiatan-desa') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-success px-5 rounded-pill"><i class="bi bi-save me-1"></i> Simpan Kegiatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection