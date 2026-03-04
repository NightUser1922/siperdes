@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning py-3">
                    <h5 class="mb-0 text-dark"><i class="bi bi-pencil-square me-2"></i>Form Edit Surat Keluar</h5>
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

                    <form action="{{ url('/surat-keluar/update/' . $suratKeluar->id_surat_keluar) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="no_surat" class="form-label fw-semibold">Nomor Surat</label>
                            <input type="text" class="form-control bg-light" id="no_surat" name="no_surat" value="{{ old('no_surat', $suratKeluar->no_surat) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_keluar" class="form-label fw-semibold">Tanggal Surat <span class="text-danger">*</span></label>
                            {{-- PERBAIKAN: name diubah menjadi tanggal_keluar --}}
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', $suratKeluar->tanggal_keluar) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tujuan_surat" class="form-label fw-semibold">Tujuan / Penerima <span class="text-danger">*</span></label>
                            {{-- PERBAIKAN: name diubah menjadi tujuan_surat --}}
                            <input type="text" class="form-control" id="tujuan_surat" name="tujuan_surat" value="{{ old('tujuan_surat', $suratKeluar->tujuan_surat) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="3" required>{{ old('perihal', $suratKeluar->perihal) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file_surat" class="form-label fw-semibold">Upload Berkas Scan Baru (Opsional)</label>
                            {{-- PERBAIKAN: name diubah menjadi file_surat --}}
                            <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file scan yang sudah ada.</small>
                            
                            @if($suratKeluar->file_surat)
                                <div class="mt-2">
                                    <span class="badge bg-info text-dark">
                                        File saat ini: 
                                        <a href="{{ asset('uploads/surat_keluar/'.$suratKeluar->file_surat) }}" target="_blank" class="text-dark text-decoration-none">
                                            {{ $suratKeluar->file_surat }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url('/surat-keluar') }}" class="btn btn-secondary px-4">Kembali</a>
                            <button type="submit" class="btn btn-warning px-5"><i class="bi bi-save me-1"></i> Update Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection