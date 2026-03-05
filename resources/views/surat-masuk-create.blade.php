@extends('layouts.app')

@section('title', 'Tambah Surat Masuk')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-white"><i class="bi bi-box-arrow-in-right me-2"></i>Form Input Surat Masuk Baru</h5>
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

                    <form action="{{ url('/surat-masuk/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="no_surat" class="form-label fw-semibold">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" value="{{ old('no_surat') }}" placeholder="Masukkan nomor surat dari pengirim" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label fw-semibold">Tanggal Masuk / Diterima <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="pengirim" class="form-label fw-semibold">Pengirim / Asal Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pengirim" name="pengirim" value="{{ old('pengirim') }}" placeholder="Masukkan instansi atau nama pengirim" required>
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="3" placeholder="Masukkan perihal atau ringkasan isi surat" required>{{ old('perihal') }}</textarea>
                        </div>

<div class="mb-4">
                            <label for="file_surat" class="form-label fw-semibold">Upload Berkas Dokumen (Opsional)</label>
                            
                            {{-- PERBAIKAN: Tambahkan doc, docx, xls, xlsx pada accept --}}
                            <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                            
                            {{-- PERBAIKAN: Teks keterangan disesuaikan --}}
                            <small class="text-muted">Format yang diizinkan: PDF, JPG, PNG, DOC/DOCX, XLS/XLSX. Maksimal 5MB.</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url('/surat-masuk') }}" class="btn btn-secondary px-4">Kembali</a>
                            <button type="submit" class="btn btn-success px-5"><i class="bi bi-save me-1"></i> Simpan Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection