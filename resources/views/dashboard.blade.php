@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="card dashboard-info-card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-7">
                <span class="badge text-bg-success mb-2">SIPERDES</span>
                <h4 class="fw-bold text-success mb-2">Ringkasan Administrasi Desa</h4>
                <p class="text-muted mb-0">
                    Pantau data surat, kegiatan desa, bantuan sosial, dan audit sistem secara ringkas melalui dashboard utama.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="user-info-box">
                            <small class="text-muted d-block">Nama</small>
                            <strong>{{ Auth::user()->nama ?? Auth::user()->username ?? 'Pengguna' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="user-info-box">
                            <small class="text-muted d-block">Role</small>
                            <strong>{{ Auth::user()->role ?? 'Pengguna' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6 col-xl-4">
        <div class="card summary-card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Surat Masuk</h6>
                        <h3 class="fw-bold text-success mb-0">{{ $totalMasuk }}</h3>
                    </div>
                    <div class="summary-icon fs-1 text-success">
                        <i class="bi bi-inbox"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card summary-card shadow-sm border-0 border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Surat Keluar</h6>
                        <h3 class="fw-bold text-primary mb-0">{{ $totalKeluar }}</h3>
                    </div>
                    <div class="summary-icon fs-1 text-primary">
                        <i class="bi bi-send"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card summary-card shadow-sm border-0 border-start border-info border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Kegiatan Desa</h6>
                        <h3 class="fw-bold text-info mb-0">{{ $totalKegiatan }}</h3>
                    </div>
                    <div class="summary-icon fs-1 text-info">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card summary-card shadow-sm border-0 border-start border-danger border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Bantuan Sosial</h6>
                        <h3 class="fw-bold text-danger mb-0">{{ $totalBantuan }}</h3>
                    </div>
                    <div class="summary-icon fs-1 text-danger">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card summary-card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Arsip Administrasi</h6>
                        <h3 class="fw-bold text-warning mb-0">{{ $totalArsip }}</h3>
                    </div>
                    <div class="summary-icon fs-1 text-warning">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!is_null($totalAudit))
        <div class="col-md-6 col-xl-4">
            <div class="card summary-card shadow-sm border-0 border-start border-secondary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Audit Log</h6>
                            <h3 class="fw-bold text-secondary mb-0">{{ $totalAudit }}</h3>
                        </div>
                        <div class="summary-icon fs-1 text-secondary">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="card dashboard-info-card shadow-sm border-0 mt-4">
    <div class="card-body p-4">
        <h5 class="card-title text-success fw-bold">Selamat Datang di SIPERDES!</h5>
        <p class="card-text text-muted">
            Anda berhasil login sebagai <strong>{{ Auth::user()->role ?? 'Pengguna' }}</strong>. 
            Gunakan menu navigasi di sebelah kiri untuk mengelola sistem pengarsipan berkas administrasi pada Kantor Desa Amawang Kanan.
        </p>
    </div>
</div>
@endsection
