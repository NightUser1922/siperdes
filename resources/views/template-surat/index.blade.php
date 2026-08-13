@extends('layouts.app')

@section('title', 'Template Surat')

@section('content')
@php($isAdmin = Auth::check() && Auth::user()->role === 'Admin')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Template Surat</li>
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
                    <span class="module-icon fs-3"><i class="bi bi-file-earmark-richtext"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Template Surat</span>
                        <h4 class="fw-bold text-success mb-1">Template Surat</h4>
                        <p class="text-muted mb-0">Kelola file DOCX berisi placeholder untuk generate Surat Keluar.</p>
                    </div>
                </div>
                @if($isAdmin)
                    <a href="{{ url('/template-surat/create') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Template
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Template Surat</h5>
                    <p class="text-muted small mb-0" id="templateTableInfo">Menampilkan {{ $templates->count() }} template surat</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="templateSearch" placeholder="Cari template, jenis, status...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            @if($isAdmin)
                                <th>Aksi</th>
                            @endif
                            <th>Nama Template</th>
                            <th>Jenis Surat</th>
                            <th>Status</th>
                            <th>Placeholder</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody id="templateTableBody" data-admin-table data-search-input="#templateSearch" data-pagination="#templatePagination" data-table-info="#templateTableInfo" data-empty-row="#templateSearchEmpty" data-item-label="template surat">
                        @forelse($templates as $template)
                            <tr data-search-row="{{ strtolower($template->nama_template.' '.$template->jenis_surat.' '.$template->status.' '.implode(' ', $template->placeholder ?? [])) }}">
                                @if($isAdmin)
                                    <td>
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ url('/template-surat/edit/' . $template->id_template) }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ url('/template-surat/delete/' . $template->id_template) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus template {{ $template->nama_template }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                                <td class="fw-bold text-success text-start">{{ $template->nama_template }}</td>
                                <td>{{ $template->jenis_surat }}</td>
                                <td>
                                    <span class="badge status-badge {{ $template->status === 'Aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $template->status }}</span>
                                </td>
                                <td class="text-start">
                                    @forelse(($template->placeholder ?? []) as $placeholder)
                                        <span class="badge text-bg-light text-dark border mb-1">${{ '{' . $placeholder . '}' }}</span>
                                    @empty
                                        <span class="text-muted small">Tidak ada placeholder</span>
                                    @endforelse
                                </td>
                                <td>
                                    <a href="{{ url('/template-surat/download/' . $template->id_template) }}" class="btn btn-info btn-sm file-link-btn">
                                        <i class="bi bi-download me-1"></i>DOCX
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 6 : 5 }}">
                                    <div class="empty-state">
                                        <i class="bi bi-file-earmark-richtext d-block mb-2"></i>
                                        <strong>Belum ada template surat</strong>
                                        <p class="mb-0 small">Upload template DOCX untuk mulai membuat Surat Keluar dari template.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="templateSearchEmpty" class="d-none">
                            <td colspan="{{ $isAdmin ? 6 : 5 }}">
                                <div class="empty-state">
                                    <i class="bi bi-search d-block mb-2"></i>
                                    <strong>Template tidak ditemukan</strong>
                                    <p class="mb-0 small">Coba gunakan kata kunci pencarian yang berbeda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
                <small class="text-muted">Gunakan placeholder DOCX dengan format ${nama_placeholder}.</small>
                <nav aria-label="Navigasi template surat">
                    <ul class="pagination pagination-sm mb-0" id="templatePagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection