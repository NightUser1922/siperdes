@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="container-fluid px-0">
    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-pencil-square"></i></span>
                <div>
                    <span class="badge text-bg-warning mb-2">Surat Keluar</span>
                    <h4 class="fw-bold text-success mb-1">Edit Surat Keluar</h4>
                    <p class="text-muted mb-0">Perbarui informasi surat keluar tanpa mengubah proses arsip yang sudah berjalan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-file-earmark-text"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Form Edit</p>
                            <h5 class="mb-0 fw-bold text-success">Data Surat Keluar</h5>
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

                    <form action="{{ url('/surat-keluar/update/' . $suratKeluar->id_surat_keluar) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nomor_surat" class="form-label fw-semibold">Nomor Surat <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat <span class="required-dot">*</span></label>
                            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tujuan" class="form-label fw-semibold">Tujuan / Penerima <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" value="{{ old('tujuan', $suratKeluar->tujuan) }}" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="required-dot">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="3" maxlength="255" required>{{ old('perihal', $suratKeluar->perihal) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file_surat" class="form-label fw-semibold">Upload Berkas Dokumen Baru (Opsional)</label>
                            <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file yang sudah ada. Format: PDF, JPG, PNG, DOC/DOCX, XLS/XLSX. Maksimal 5MB.</small>

                            @if($suratKeluar->file_surat)
                                <div class="mt-2">
                                    <span class="badge current-file-badge">
                                        File saat ini:
                                        <a href="{{ asset('uploads/surat_keluar/'.$suratKeluar->file_surat) }}" target="_blank" class="text-decoration-none text-reset">
                                            {{ $suratKeluar->file_surat }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/surat-keluar') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-warning px-5 rounded-pill"><i class="bi bi-save me-1"></i> Update Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection