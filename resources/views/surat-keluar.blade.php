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
                        <p class="text-muted mb-0">Kelola surat keluar dari template DOCX atau upload dokumen manual.</p>
                    </div>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ url('/template-surat') }}" class="btn btn-light border rounded-pill px-4">
                        <i class="bi bi-file-earmark-richtext me-2"></i>Template Surat
                    </a>
                    <a href="{{ url('/surat-keluar/create') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Surat Keluar
                    </a>
                </div>
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
                        <input type="search" class="form-control" id="suratKeluarSearch" placeholder="Cari nomor, tujuan, perihal, template...">
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
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Dokumen</th>
                        </tr>
                    </thead>
                    <tbody id="suratKeluarTableBody" data-admin-table data-search-input="#suratKeluarSearch" data-pagination="#suratKeluarPagination" data-table-info="#suratKeluarTableInfo" data-empty-row="#suratKeluarSearchEmpty" data-item-label="data surat keluar">
                        @forelse($suratKeluar as $surat)
                            <tr data-search-row="{{ strtolower($surat->nomor_surat.' '.$surat->tujuan.' '.$surat->perihal.' '.($surat->status_persetujuan ?? 'Menunggu').' '.($surat->metode_pembuatan ?? 'Upload').' '.optional($surat->templateSurat)->nama_template) }}">
                                <td>
                                    <div class="d-inline-flex flex-wrap gap-1">
                                        <a href="{{ url('/surat-keluar/edit/' . $surat->id_surat_keluar) }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ url('/surat-keluar/' . $surat->id_surat_keluar . '/preview') }}" target="_blank" class="btn btn-info btn-sm action-btn" title="Preview PDF">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ url('/surat-keluar/' . $surat->id_surat_keluar . '/download') }}" class="btn btn-success btn-sm action-btn" title="Download PDF">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        @if($surat->id_template)
                                            <form action="{{ url('/surat-keluar/' . $surat->id_surat_keluar . '/generate') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm action-btn" title="Generate PDF">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ url('/surat-keluar/delete/' . $surat->id_surat_keluar) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip surat keluar {{ $surat->nomor_surat }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @if(Auth::check() && Auth::user()->role === 'Kepala Desa' && $surat->status === 'menunggu')
                                            <form action="{{ url('/surat-keluar/' . $surat->id_surat_keluar . '/approve') }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui surat keluar {{ $surat->nomor_surat }}?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm action-btn" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <form action="{{ url('/surat-keluar/' . $surat->id_surat_keluar . '/reject') }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak surat keluar {{ $surat->nomor_surat }}?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm action-btn" title="Tolak"><i class="bi bi-x-lg"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="fw-bold text-success">{{ $surat->nomor_surat }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                                <td>{{ $surat->tujuan }}</td>
                                <td class="text-start">{{ $surat->perihal }}</td>
                                <td>
                                    <span class="badge status-badge {{ ($surat->metode_pembuatan ?? 'Upload') === 'Template' ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                        {{ $surat->metode_pembuatan ?? 'Upload' }}
                                    </span>
                                    @if($surat->templateSurat)
                                        <div class="small text-muted mt-1">{{ $surat->templateSurat->nama_template }}</div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $approvalStatus = $surat->status ?? 'menunggu';
                                        $statusClass = match($approvalStatus) {
                                            'disetujui' => 'text-bg-success',
                                            'ditolak' => 'text-bg-danger',
                                            default => 'text-bg-warning',
                                        };
                                    @endphp
                                    <span class="badge status-badge {{ $statusClass }}">{{ ucfirst($approvalStatus) }}</span>
                                </td>
                                <td>
                                    @if($surat->file_surat)
                                        <span class="badge text-bg-light text-dark border">{{ $surat->file_surat }}</span>
                                    @else
                                        <span class="text-muted small">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-send d-block mb-2"></i>
                                        <strong>Belum ada data surat keluar</strong>
                                        <p class="mb-0 small">Silakan tambah data baru dari template atau upload file manual.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="suratKeluarSearchEmpty" class="d-none">
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
                <small class="text-muted">Data ditampilkan langsung dari arsip Surat Keluar.</small>
                <nav aria-label="Navigasi data surat keluar">
                    <ul class="pagination pagination-sm mb-0" id="suratKeluarPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection