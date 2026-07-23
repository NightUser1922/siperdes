@extends('layouts.app')

@section('title', 'Tambah Bantuan Sosial')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/bantuan-sosial') }}" class="text-decoration-none text-success">Bantuan Sosial</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Bantuan Sosial</li>
        </ol>
    </nav>
    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-3 align-items-start">
                <span class="module-icon fs-3"><i class="bi bi-people"></i></span>
                <div>
                    <span class="badge text-bg-success mb-2">Bantuan Sosial</span>
                    <h4 class="fw-bold text-success mb-1">Tambah Bantuan Sosial</h4>
                    <p class="text-muted mb-0">Lengkapi data bantuan sosial sesuai dokumen administrasi.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="module-icon"><i class="bi bi-person-plus"></i></span>
                        <div>
                            <p class="form-section-title mb-1">Form Input</p>
                            <h5 class="mb-0 fw-bold text-success">Data Bantuan Baru</h5>
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

                    <form action="{{ url('/bantuan-sosial/store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_bantuan" class="form-label fw-semibold">Nama Bantuan <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="nama_bantuan" name="nama_bantuan" value="{{ old('nama_bantuan') }}" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="instansi_pemberi" class="form-label fw-semibold">Instansi Pemberi <span class="required-dot">*</span></label>
                            <input type="text" class="form-control" id="instansi_pemberi" name="instansi_pemberi" value="{{ old('instansi_pemberi') }}" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_bantuan" class="form-label fw-semibold">Tanggal Bantuan <span class="required-dot">*</span></label>
                            <input type="date" class="form-control" id="tanggal_bantuan" name="tanggal_bantuan" value="{{ old('tanggal_bantuan', date('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="jumlah_penerima" class="form-label fw-semibold">Jumlah Penerima <span class="required-dot">*</span></label>
                            <input type="number" class="form-control" id="jumlah_penerima" name="jumlah_penerima" value="{{ old('jumlah_penerima') }}" min="0" required>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="{{ url('/bantuan-sosial') }}" class="btn btn-light border px-4 rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-success px-5 rounded-pill"><i class="bi bi-save me-1"></i> Simpan Bantuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection