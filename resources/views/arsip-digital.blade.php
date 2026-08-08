@extends('layouts.app')

@section('title', 'Arsip Digital')

@section('content')
@php
    $dashboardUrl = Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard');
@endphp

<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ $dashboardUrl }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Arsip Digital</li>
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
                    <span class="module-icon fs-3"><i class="bi bi-archive"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Pusat Arsip</span>
                        <h4 class="fw-bold text-success mb-1">Arsip Digital</h4>
                        <p class="text-muted mb-0">Kelola arsip private SIPERDES yang tersimpan di Google Drive melalui Laravel.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if(isset($isConnected) && $isConnected)
                        <span class="btn btn-success rounded-pill px-4 disabled" aria-disabled="true"><i class="bi bi-check2-circle me-2"></i>Google Drive Terhubung</span>
                    @else
                        {{-- show connect button to Admin and Kepala Desa --}}
                        @if(Auth::check() && in_array(Auth::user()->role, ['Admin', 'Kepala Desa'], true))
                            <a href="{{ url('/google-drive/connect') }}" class="btn btn-outline-success rounded-pill px-4">
                                <i class="bi bi-google me-2"></i>Hubungkan Google Drive
                            </a>
                        @endif
                    @endif

                    <a href="{{ url('/arsip-digital/create') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Arsip
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(!(isset($isConnected) && $isConnected))
        <div class="alert alert-info rounded-4 mb-4">
            <i class="bi bi-info-circle me-2"></i>Google Drive belum terhubung. Hubungkan Google Drive untuk menyimpan arsip secara private ke Google Drive. Klik tombol "Hubungkan Google Drive".
        </div>
    @endif

    <div class="card module-card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <form action="{{ url('/arsip-digital') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label for="search" class="form-label fw-semibold">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nomor, nama, deskripsi, uploader...">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label for="kategori" class="form-label fw-semibold">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ ($filters['kategori'] ?? '') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-fill">
                        <i class="bi bi-filter me-1"></i>Tampilkan
                    </button>
                    <a href="{{ url('/arsip-digital') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Arsip Digital</h5>
                    <p class="text-muted small mb-0">Menampilkan {{ $arsipDigital->count() }} dari {{ $arsipDigital->total() }} arsip. Total tersimpan: {{ $totalArsip }} arsip.</p>
                </div>
                <span class="badge text-bg-success status-badge">{{ $arsipDigital->total() }} Data</span>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Nomor Arsip</th>
                            <th>Nama Arsip</th>
                            <th>Kategori</th>
                            <th>Uploader</th>
                            <th>Ukuran</th>
                            <th>Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arsipDigital as $arsip)
                            @php
                                $kategoriClass = match($arsip->kategori) {
                                    'Surat Masuk' => 'text-bg-primary',
                                    'Surat Keluar' => 'text-bg-success',
                                    'Kegiatan Desa' => 'text-bg-warning',
                                    'Bantuan Sosial' => 'text-bg-info',
                                    'Laporan' => 'text-bg-danger',
                                    'Template Surat' => 'text-bg-secondary',
                                    default => 'text-bg-light text-dark border',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ url('/arsip-digital/preview/'.$arsip->id_arsip) }}" target="_blank" class="btn btn-info btn-sm action-btn" title="Preview">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ url('/arsip-digital/download/'.$arsip->id_arsip) }}" class="btn btn-success btn-sm action-btn" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a href="{{ url('/arsip-digital/edit/'.$arsip->id_arsip) }}" class="btn btn-warning btn-sm action-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ url('/arsip-digital/delete/'.$arsip->id_arsip) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip {{ $arsip->nomor_arsip }} dari database dan Google Drive?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">{{ $arsip->nomor_arsip }}</td>
                                <td>
                                    <span class="fw-semibold d-block">{{ $arsip->nama_arsip }}</span>
                                    <small class="text-muted">{{ $arsip->original_name ?? 'File arsip' }}</small>
                                </td>
                                <td><span class="badge status-badge {{ $kategoriClass }}">{{ $arsip->kategori }}</span></td>
                                <td>{{ $arsip->uploader }}</td>
                                <td>{{ $arsip->ukuran_format }}</td>
                                <td>{{ optional($arsip->created_at)->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-archive d-block mb-2"></i>
                                        <strong>Belum ada arsip digital</strong>
                                        <p class="mb-0 small">Upload arsip pertama untuk mulai menyimpan dokumen digital SIPERDES.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($arsipDigital->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $arsipDigital->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection