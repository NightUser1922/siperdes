@extends('layouts.app')

@section('title', 'Kegiatan Desa')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kegiatan Desa</li>
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
                    <span class="module-icon fs-3"><i class="bi bi-calendar-event"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Menu Utama</span>
                        <h4 class="fw-bold text-success mb-1">Kegiatan Desa</h4>
                        <p class="text-muted mb-0">Kelola data kegiatan desa sesuai agenda administrasi pemerintahan desa.</p>
                    </div>
                </div>
                <a href="/kegiatan-desa/create" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kegiatan
                </a>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Kegiatan Desa</h5>
                    <p class="text-muted small mb-0" id="kegiatanTableInfo">Menampilkan {{ $kegiatanDesa->count() }} data kegiatan</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="kegiatanSearch" placeholder="Cari kegiatan, lokasi, keterangan...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Tim Pelaksana</th>
                            <th>Penanggung Jawab</th>
                            <th>Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody id="kegiatanTableBody" data-admin-table data-search-input="#kegiatanSearch" data-pagination="#kegiatanPagination" data-table-info="#kegiatanTableInfo" data-empty-row="#kegiatanSearchEmpty" data-item-label="data kegiatan">
                        @forelse($kegiatanDesa as $kegiatan)
                            <tr data-search-row="{{ strtolower($kegiatan->nama_kegiatan.' '.$kegiatan->lokasi.' '.$kegiatan->keterangan.' '.$kegiatan->tim_pelaksana.' '.$kegiatan->penanggung_jawab) }}">
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a href="/kegiatan-desa/edit/{{ $kegiatan->id_kegiatan }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="/kegiatan-desa/delete/{{ $kegiatan->id_kegiatan }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan {{ $kegiatan->nama_kegiatan }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">{{ $kegiatan->nama_kegiatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d-m-Y') }}</td>
                                <td>{{ $kegiatan->lokasi }}</td>
                                <td class="text-start">{{ $kegiatan->keterangan }}</td>
                                <td class="text-start">{{ $kegiatan->tim_pelaksana }}</td>
                                <td class="text-start">{{ $kegiatan->penanggung_jawab }}</td>
                                <td>
                                    @if(!empty($kegiatan->dokumentasi))
                                        <a href="{{ url('/uploads/kegiatan_dokumentasi/' . $kegiatan->dokumentasi) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a>
                                        <a href="{{ url('/uploads/kegiatan_dokumentasi/' . $kegiatan->dokumentasi) }}" download class="btn btn-outline-success btn-sm"><i class="bi bi-download"></i></a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-calendar-event d-block mb-2"></i>
                                        <strong>Belum ada data kegiatan desa</strong>
                                        <p class="mb-0 small">Silakan tambah kegiatan baru untuk mengisi agenda desa.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="kegiatanSearchEmpty" class="d-none">
                            <td colspan="8">
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
                <small class="text-muted">Data ditampilkan langsung dari tabel Kegiatan Desa.</small>
                <nav aria-label="Navigasi data kegiatan desa">
                    <ul class="pagination pagination-sm mb-0" id="kegiatanPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection