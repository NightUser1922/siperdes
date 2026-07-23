@extends('layouts.app')

@section('title', 'Tambah Surat Keluar')

@section('content')
<div class="container-fluid px-0">
    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-send"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Surat Keluar</span>
                    <h4 class="fw-bold text-success mb-1">Tambah Surat Keluar</h4>
                    <p class="text-muted mb-0">Lengkapi informasi surat keluar sesuai dokumen yang diterbitkan desa.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-file-earmark-plus"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Form Input</p>
                            <h5 class="mb-0 fw-bold text-success">Data Surat Keluar Baru</h5>
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

                    <form action="{{ url('/surat-keluar/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nomor_surat" class="form-label fw-semibold">Nomor Surat <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $noOtomatis ?? '') }}" maxlength="100" required>
                            <small class="text-muted">Nomor rekomendasi otomatis dapat diubah jika diperlukan.</small>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat <span class="required-dot">*</span></label>
                            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tujuan" class="form-label fw-semibold">Tujuan / Penerima <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" value="{{ old('tujuan') }}" maxlength="100" placeholder="Masukkan nama instansi atau penerima tujuan" required>
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="required-dot">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="3" placeholder="Masukkan perihal atau ringkasan isi surat" maxlength="255" required>{{ old('perihal') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file_surat" class="form-label fw-semibold">Upload Berkas Dokumen <span class="required-dot">*</span></label>
                            <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                            <small class="text-muted">Format yang diizinkan: PDF, JPG, PNG, DOC/DOCX, XLS/XLSX. Maksimal 5MB.</small>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/surat-keluar') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-success px-5 rounded-pill"><i class="bi bi-save me-1"></i> Simpan Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection