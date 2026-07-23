@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Surat Keluar</li>
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
                    <span class="module-icon fs-3"><i class="bi bi-send"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Menu Utama</span>
                        <h4 class="fw-bold text-success mb-1">Arsip Surat Keluar</h4>
                        <p class="text-muted mb-0">Kelola dan pantau dokumen surat keluar yang diterbitkan oleh desa.</p>
                    </div>
                </div>
                <a href="/surat-keluar/create" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Surat Keluar
                </a>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Surat Keluar</h5>
                    <p class="text-muted small mb-0" id="suratKeluarTableInfo">Menampilkan {{ $suratKeluar->count() }} data surat keluar</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="suratKeluarSearch" placeholder="Cari nomor, tujuan, perihal...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Nomor Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th>Dokumen</th>
                        </tr>
                    </thead>
                    <tbody id="suratKeluarTableBody" data-admin-table data-search-input="#suratKeluarSearch" data-pagination="#suratKeluarPagination" data-table-info="#suratKeluarTableInfo" data-empty-row="#suratKeluarSearchEmpty" data-item-label="data surat keluar">
                        @forelse($suratKeluar as $surat)
                            <tr data-search-row="{{ strtolower($surat->nomor_surat.' '.$surat->tujuan.' '.$surat->perihal.' '.($surat->status_persetujuan ?? 'Menunggu')) }}">
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a href="/surat-keluar/edit/{{ $surat->id_surat_keluar }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="/surat-keluar/delete/{{ $surat->id_surat_keluar }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat keluar {{ $surat->nomor_surat }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">{{ $surat->nomor_surat }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                                <td>{{ $surat->tujuan }}</td>
                                <td class="text-start">{{ $surat->perihal }}</td>
                                <td>
                                    @php
                                        $statusClass = match($surat->status_persetujuan ?? 'Menunggu') {
                                            'Disetujui' => 'text-bg-success',
                                            'Ditolak' => 'text-bg-danger',
                                            default => 'text-bg-warning',
                                        };
                                    @endphp
                                    <span class="badge status-badge {{ $statusClass }}">{{ $surat->status_persetujuan ?? 'Menunggu' }}</span>
                                </td>
                                <td>
                                    @if($surat->file_surat)
                                        <a href="{{ asset('uploads/surat_keluar/'.$surat->file_surat) }}" target="_blank" class="btn btn-info btn-sm file-link-btn">
                                            <i class="bi bi-eye me-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-send d-block mb-2"></i>
                                        <strong>Belum ada data surat keluar</strong>
                                        <p class="mb-0 small">Silakan tambah data baru untuk mulai mengarsipkan surat keluar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="suratKeluarSearchEmpty" class="d-none">
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
                <small class="text-muted">Data ditampilkan langsung dari arsip Surat Keluar.</small>
                <nav aria-label="Navigasi data surat keluar">
                    <ul class="pagination pagination-sm mb-0" id="suratKeluarPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection