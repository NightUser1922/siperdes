@extends('layouts.app')

@section('title', 'Bantuan Sosial')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bantuan Sosial</li>
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
                    <span class="module-icon fs-3"><i class="bi bi-people"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Menu Utama</span>
                        <h4 class="fw-bold text-success mb-1">Bantuan Sosial</h4>
                        <p class="text-muted mb-0">Kelola data bantuan sosial dan jumlah penerima sesuai catatan administrasi desa.</p>
                    </div>
                </div>
                <a href="/bantuan-sosial/create" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Bantuan
                </a>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Bantuan Sosial</h5>
                    <p class="text-muted small mb-0" id="bantuanTableInfo">Menampilkan {{ $bantuanSosial->count() }} data bantuan</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="bantuanSearch" placeholder="Cari bantuan, instansi, tanggal...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Nama Bantuan</th>
                            <th>Instansi Pemberi</th>
                            <th>Tanggal Bantuan</th>
                            <th>Jumlah Penerima</th>
                        </tr>
                    </thead>
                    <tbody id="bantuanTableBody" data-admin-table data-search-input="#bantuanSearch" data-pagination="#bantuanPagination" data-table-info="#bantuanTableInfo" data-empty-row="#bantuanSearchEmpty" data-item-label="data bantuan">
                        @forelse($bantuanSosial as $bantuan)
                            <tr data-search-row="{{ strtolower($bantuan->nama_bantuan.' '.$bantuan->instansi_pemberi.' '.$bantuan->tanggal_bantuan.' '.$bantuan->jumlah_penerima) }}">
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a href="/bantuan-sosial/edit/{{ $bantuan->id_bantuan }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="/bantuan-sosial/delete/{{ $bantuan->id_bantuan }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus bantuan {{ $bantuan->nama_bantuan }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">{{ $bantuan->nama_bantuan }}</td>
                                <td>{{ $bantuan->instansi_pemberi }}</td>
                                <td>{{ \Carbon\Carbon::parse($bantuan->tanggal_bantuan)->format('d-m-Y') }}</td>
                                <td><span class="badge status-badge text-bg-info">{{ $bantuan->jumlah_penerima }} penerima</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="bi bi-people d-block mb-2"></i>
                                        <strong>Belum ada data bantuan sosial</strong>
                                        <p class="mb-0 small">Silakan tambah data bantuan sosial untuk mulai mengelola penerima bantuan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="bantuanSearchEmpty" class="d-none">
                            <td colspan="5">
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
                <small class="text-muted">Data ditampilkan langsung dari tabel Bantuan Sosial.</small>
                <nav aria-label="Navigasi data bantuan sosial">
                    <ul class="pagination pagination-sm mb-0" id="bantuanPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection