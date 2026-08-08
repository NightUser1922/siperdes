@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
@php
    $totalMasuk = $totalMasuk ?? 0;
    $totalKeluar = $totalKeluar ?? 0;
    $totalKegiatan = $totalKegiatan ?? 0;
    $totalBantuan = $totalBantuan ?? 0;
    $totalArsip = $totalArsip ?? 0;
    $totalAdministrasi = $totalMasuk + $totalKeluar + $totalKegiatan + $totalBantuan + $totalArsip;

    $dashboardCards = [
        [
            'label' => 'Surat Masuk',
            'count' => $totalMasuk,
            'url' => url('/surat-masuk'),
            'icon' => 'bi-inbox',
            'color' => 'success',
            'caption' => 'Arsip surat diterima',
        ],
        [
            'label' => 'Surat Keluar',
            'count' => $totalKeluar,
            'url' => url('/surat-keluar'),
            'icon' => 'bi-send',
            'color' => 'primary',
            'caption' => 'Arsip surat diterbitkan',
        ],
        [
            'label' => 'Kegiatan Desa',
            'count' => $totalKegiatan,
            'url' => url('/kegiatan-desa'),
            'icon' => 'bi-calendar-event',
            'color' => 'info',
            'caption' => 'Agenda kegiatan tercatat',
        ],
        [
            'label' => 'Bantuan Sosial',
            'count' => $totalBantuan,
            'url' => url('/bantuan-sosial'),
            'icon' => 'bi-people',
            'color' => 'danger',
            'caption' => 'Data bantuan tersedia',
        ],
        [
            'label' => 'Arsip Digital',
            'count' => $totalArsip,
            'url' => url('/arsip-digital'),
            'icon' => 'bi-archive',
            'color' => 'warning',
            'caption' => 'File arsip tersimpan',
        ],
    ];

    $quickActions = [
        [
            'label' => 'Tambah Surat Masuk',
            'url' => url('/surat-masuk/create'),
            'icon' => 'bi-file-earmark-plus',
            'color' => 'success',
        ],
        [
            'label' => 'Tambah Surat Keluar',
            'url' => url('/surat-keluar/create'),
            'icon' => 'bi-send-plus',
            'color' => 'primary',
        ],
        [
            'label' => 'Tambah Kegiatan',
            'url' => url('/kegiatan-desa/create'),
            'icon' => 'bi-calendar-plus',
            'color' => 'info',
        ],
        [
            'label' => 'Tambah Bantuan',
            'url' => url('/bantuan-sosial/create'),
            'icon' => 'bi-person-plus',
            'color' => 'danger',
        ],
        [
            'label' => 'Upload Arsip',
            'url' => url('/arsip-digital/create'),
            'icon' => 'bi-cloud-upload',
            'color' => 'warning',
        ],
    ];

    $userRole = Auth::user()->role ?? 'Pengguna';
@endphp

<div class="container-fluid px-0">
    <div class="card module-hero dashboard-info-card shadow-sm border-0 mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <span class="badge text-bg-success mb-3">Dashboard SIPERDES</span>
                    <h3 class="fw-bold text-success mb-2">Ringkasan Administrasi Desa</h3>
                    <p class="text-muted mb-0">
                        Pantau jumlah data terbaru dan buka modul administrasi desa dari satu halaman utama.
                    </p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="user-info-box h-100 bg-white">
                                <small class="text-muted d-block">Total Data</small>
                                <strong class="fs-4 text-success">{{ $totalAdministrasi }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="user-info-box h-100 bg-white">
                                <small class="text-muted d-block">Role</small>
                                <strong>{{ Auth::user()->role ?? 'Pengguna' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach($dashboardCards as $card)
            <div class="col-md-6 col-xl-3">
                <a href="{{ $card['url'] }}" class="text-decoration-none text-reset d-block h-100" aria-label="Buka {{ $card['label'] }}">
                    <div class="card summary-card shadow-sm border-0 border-start border-{{ $card['color'] }} border-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <h6 class="text-muted mb-2">{{ $card['label'] }}</h6>
                                    <h3 class="fw-bold text-{{ $card['color'] }} mb-1">{{ $card['count'] }}</h3>
                                    <small class="text-muted d-block">{{ $card['caption'] }}</small>
                                </div>
                                <div class="summary-icon fs-1 text-{{ $card['color'] }}">
                                    <i class="bi {{ $card['icon'] }}"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-4 text-{{ $card['color'] }} fw-semibold small">
                                <span>Buka data</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card dashboard-info-card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold text-success mb-1">Quick Action</h5>
                            <p class="text-muted small mb-0">Akses cepat untuk input data administrasi.</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-lightning-charge"></i></span>
                    </div>
                    <div class="row g-3">
                        @foreach($quickActions as $action)
                            <div class="col-sm-6">
                                <a href="{{ $action['url'] }}" class="btn btn-light border w-100 text-start p-3 rounded-4 d-flex align-items-center gap-3">
                                    <span class="summary-icon text-{{ $action['color'] }} fs-4">
                                        <i class="bi {{ $action['icon'] }}"></i>
                                    </span>
                                    <span class="fw-semibold">{{ $action['label'] }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card dashboard-info-card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold text-success mb-1">Aktivitas Terbaru</h5>
                            <p class="text-muted small mb-0">Ringkasan modul berdasarkan data saat ini.</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-clock-history"></i></span>
                    </div>

                    <div class="d-grid gap-3">
                        @foreach($dashboardCards as $card)
                            <a href="{{ $card['url'] }}" class="text-decoration-none text-reset d-flex align-items-center justify-content-between gap-3 border-bottom pb-3">
                                <span class="d-flex align-items-center gap-3">
                                    <span class="summary-icon text-{{ $card['color'] }} fs-5">
                                        <i class="bi {{ $card['icon'] }}"></i>
                                    </span>
                                    <span>
                                        <strong class="d-block">{{ $card['label'] }}</strong>
                                        <small class="text-muted">{{ $card['count'] }} data tercatat</small>
                                    </span>
                                </span>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($userRole === 'Kepala Desa')
        <div class="card dashboard-info-card shadow-sm border-0 mt-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h5 class="fw-bold text-success mb-1">Monitoring Kepala Desa</h5>
                        <p class="text-muted small mb-0">Pantau status administrasi dan surat yang membutuhkan perhatian.</p>
                    </div>
                    <span class="module-icon"><i class="bi bi-eye-fill"></i></span>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="user-info-box h-100">
                            <small class="text-muted d-block">Surat Masuk Menunggu Verifikasi</small>
                            <strong class="fs-4 text-success">{{ $pendingVerifikasiMasuk }}</strong>
                            <p class="text-muted mb-0">Surat masuk yang masih perlu dicek statusnya.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="user-info-box h-100">
                            <small class="text-muted d-block">Surat Keluar Menunggu Persetujuan</small>
                            <strong class="fs-4 text-success">{{ $pendingPersetujuanKeluar }}</strong>
                            <p class="text-muted mb-0">Surat keluar yang masih menunggu tindak lanjut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card dashboard-info-card shadow-sm border-0 mt-4">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <h5 class="card-title text-success fw-bold mb-2">Informasi Ringkas</h5>
                    <p class="card-text text-muted mb-0">
                        Anda berhasil login sebagai <strong>{{ Auth::user()->role ?? 'Pengguna' }}</strong>. Gunakan card, quick action, atau sidebar untuk membuka modul administrasi yang tersedia.
                    </p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="user-info-box h-100">
                                <small class="text-muted d-block">Pengguna</small>
                                <strong>{{ Auth::user()->nama ?? Auth::user()->username ?? 'Pengguna' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="user-info-box h-100">
                                <small class="text-muted d-block">Modul Aktif</small>
                                <strong>5 Modul</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection