@extends('layouts.app')

@section('title', 'Tambah Surat Keluar')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-white"><i class="bi bi-file-earmark-plus me-2"></i>Form Input Surat Keluar Baru</h5>
                </div>
                <div class="card-body p-4">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger pb-0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/surat-keluar/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="no_surat" class="form-label fw-semibold">Nomor Surat (Otomatis)</label>
                            <input type="text" class="form-control bg-light" id="no_surat" name="no_surat" value="{{ $noOtomatis ?? 'Nomor akan dibuat otomatis' }}" readonly>
                            <small class="text-muted">Nomor surat akan digenerate otomatis oleh sistem.</small>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_keluar" class="form-label fw-semibold">Tanggal Surat <span class="text-danger">*</span></label>
                            {{-- PERBAIKAN: name dan old() disesuaikan menjadi tanggal_keluar --}}
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tujuan_surat" class="form-label fw-semibold">Tujuan / Penerima <span class="text-danger">*</span></label>
                            {{-- PERBAIKAN: name dan old() disesuaikan menjadi tujuan_surat --}}
                            <input type="text" class="form-control" id="tujuan_surat" name="tujuan_surat" value="{{ old('tujuan_surat') }}" placeholder="Masukkan nama instansi atau penerima tujuan" required>
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="3" placeholder="Masukkan perihal atau ringkasan isi surat" required>{{ old('perihal') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file_surat" class="form-label fw-semibold">Upload Berkas Scan (Opsional)</label>
                            {{-- PERBAIKAN: name disesuaikan menjadi file_surat --}}
                            <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format file yang diizinkan: PDF, JPG, PNG. Maksimal 2MB.</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url('/surat-keluar') }}" class="btn btn-secondary px-4">Kembali</a>
                            <button type="submit" class="btn btn-success px-5"><i class="bi bi-save me-1"></i> Simpan Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection