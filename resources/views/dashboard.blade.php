@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Surat Masuk</h6>
                        <h3 class="fw-bold text-success mb-0">124</h3>
                    </div>
                    <div class="fs-1 text-success opacity-50">
                        <i class="bi bi-inbox"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Surat Keluar</h6>
                        <h3 class="fw-bold text-primary mb-0">89</h3>
                    </div>
                    <div class="fs-1 text-primary opacity-50">
                        <i class="bi bi-send"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Arsip Dokumen</h6>
                        <h3 class="fw-bold text-warning mb-0">45</h3>
                    </div>
                    <div class="fs-1 text-warning opacity-50">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-2">
    <div class="card-body p-4">
        <h5 class="card-title text-success fw-bold">Selamat Datang di SIPERDES!</h5>
        <p class="card-text text-muted">
            Anda berhasil login sebagai <strong>{{ Auth::user()->level ?? 'Pengguna' }}</strong>. 
            Gunakan menu navigasi di sebelah kiri untuk mengelola sistem pengarsipan berkas administrasi pada Kantor Desa Amawang Kanan.
        </p>
    </div>
</div>
@endsection