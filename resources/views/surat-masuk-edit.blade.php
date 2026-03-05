@extends('layouts.app')

@section('title', 'Edit Surat Masuk')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning py-3">
                    <h5 class="mb-0 text-dark"><i class="bi bi-pencil-square me-2"></i>Form Edit Surat Masuk</h5>
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

                    <form action="{{ url('/surat-masuk/update/' . $suratMasuk->id_surat_masuk) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="no_surat" class="form-label fw-semibold">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" value="{{ old('no_surat', $suratMasuk->no_surat) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label fw-semibold">Tanggal Masuk / Diterima <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', $suratMasuk->tanggal_masuk) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="pengirim" class="form-label fw-semibold">Pengirim / Asal Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pengirim" name="pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">Perihal Surat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="3" required>{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file_surat" class="form-label fw-semibold">Upload Berkas Dokumen Baru (Opsional)</label>
                            
                            {{-- PERBAIKAN: Tambahkan ekstensi office pada atribut accept --}}
                            <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                            
                            {{-- PERBAIKAN: Perbarui keterangan --}}
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file scan yang sudah ada. Format: PDF, JPG, PNG, DOC/X, XLS/X. Maks 5MB.</small>
                            
                            @if($suratMasuk->file_surat)
                                <div class="mt-2">
                                    <span class="badge bg-info text-dark">
                                        File saat ini: 
                                        <a href="{{ asset('uploads/surat_masuk/'.$suratMasuk->file_surat) }}" target="_blank" class="text-dark text-decoration-none">
                                            {{ $suratMasuk->file_surat }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url('/surat-masuk') }}" class="btn btn-secondary px-4">Kembali</a>
                            <button type="submit" class="btn btn-warning px-5"><i class="bi bi-save me-1"></i> Update Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection