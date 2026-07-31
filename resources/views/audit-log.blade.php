@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Audit Log</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div class="d-flex gap-3 align-items-start">
                    <span class="module-icon fs-3"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Sistem</span>
                        <h4 class="fw-bold text-success mb-1">Audit Log</h4>
                        <p class="text-muted mb-0">Pantau jejak aktivitas login, logout, dan perubahan data administrasi desa.</p>
                    </div>
                </div>
                <span class="badge status-badge text-bg-light text-success border">{{ $auditLogs->count() }} aktivitas</span>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Audit Log</h5>
                    <p class="text-muted small mb-0" id="auditLogTableInfo">Menampilkan {{ $auditLogs->count() }} data audit log</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="auditLogSearch" placeholder="Cari user, role, aktivitas, modul...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Aktivitas</th>
                            <th>Modul</th>
                            <th>IP Address</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="auditLogTableBody" data-admin-table data-search-input="#auditLogSearch" data-pagination="#auditLogPagination" data-table-info="#auditLogTableInfo" data-empty-row="#auditLogSearchEmpty" data-item-label="data audit log">
                        @forelse($auditLogs as $log)
                            @php
                                $userName = $log->user->nama ?? $log->user->username ?? 'User tidak ditemukan';
                                $role = $log->user->role ?? '-';
                                $activityClass = match($log->aktivitas_label) {
                                    'Login' => 'text-bg-success',
                                    'Logout' => 'text-bg-secondary',
                                    'Tambah data' => 'text-bg-primary',
                                    'Edit data' => 'text-bg-warning',
                                    'Hapus data' => 'text-bg-danger',
                                    default => 'text-bg-info',
                                };
                            @endphp
                            <tr data-search-row="{{ strtolower($userName.' '.$role.' '.$log->aktivitas_label.' '.$log->modul.' '.$log->ip_address.' '.$log->keterangan) }}">
                                <td>{{ optional($log->waktu_akses)->format('d-m-Y H:i:s') ?? '-' }}</td>
                                <td class="fw-bold text-success">{{ $userName }}</td>
                                <td>{{ $role }}</td>
                                <td><span class="badge status-badge {{ $activityClass }}">{{ $log->aktivitas_label }}</span></td>
                                <td>{{ $log->modul }}</td>
                                <td><span class="badge status-badge text-bg-light text-dark border">{{ $log->ip_address }}</span></td>
                                <td class="text-start">{{ $log->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-shield-check d-block mb-2"></i>
                                        <strong>Belum ada data audit log</strong>
                                        <p class="mb-0 small">Aktivitas login, logout, dan CRUD akan muncul di sini setelah tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="auditLogSearchEmpty" class="d-none">
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-search d-block mb-2"></i>
                                    <strong>Data tidak ditemukan</strong>
                                    <p class="mb-0 small">Coba gunakan kata kunci pencarian yang berbeda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
                <small class="text-muted">Data ditampilkan langsung dari tabel Audit Log.</small>
                <nav aria-label="Navigasi data audit log">
                    <ul class="pagination pagination-sm mb-0" id="auditLogPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection