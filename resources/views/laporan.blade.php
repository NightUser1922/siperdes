@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ Auth::check() && Auth::user()->role === 'Kepala Desa' ? url('/kades/dashboard') : url('/admin/dashboard') }}" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Laporan</li>
        </ol>
    </nav>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <div class="fw-semibold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Filter laporan belum valid:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $exportQuery = request()->except('page');
        $exportSuffix = count($exportQuery) ? '?' . http_build_query($exportQuery) : '';
    @endphp

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div class="d-flex gap-3 align-items-start">
                    <span class="module-icon fs-3"><i class="bi bi-file-earmark-bar-graph"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Rekapitulasi</span>
                        <h4 class="fw-bold text-success mb-1">Laporan SIPERDES</h4>
                        <p class="text-muted mb-0">Rekap seluruh data administrasi desa berdasarkan periode, bulan, tahun, dan jenis data.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ url('/laporan/print') . $exportSuffix }}" target="_blank" class="btn btn-light border rounded-pill px-3">
                        <i class="bi bi-printer me-1"></i>Print
                    </a>
                    <a href="{{ url('/laporan/export/pdf') . $exportSuffix }}" class="btn btn-danger rounded-pill px-3">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                    </a>
                    <a href="{{ url('/laporan/export/excel') . $exportSuffix }}" class="btn btn-success rounded-pill px-3">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="module-icon"><i class="bi bi-collection"></i></span>
                    <div>
                        <p class="text-muted small mb-1">Total Data</p>
                        <h4 class="fw-bold text-success mb-0">{{ $ringkasan['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="module-icon"><i class="bi bi-inbox"></i></span>
                    <div>
                        <p class="text-muted small mb-1">Surat Masuk</p>
                        <h4 class="fw-bold text-primary mb-0">{{ $ringkasan['surat_masuk'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="module-icon"><i class="bi bi-send"></i></span>
                    <div>
                        <p class="text-muted small mb-1">Surat Keluar</p>
                        <h4 class="fw-bold text-success mb-0">{{ $ringkasan['surat_keluar'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="module-icon"><i class="bi bi-people"></i></span>
                    <div>
                        <p class="text-muted small mb-1">Kegiatan & Bantuan</p>
                        <h4 class="fw-bold text-info mb-0">{{ $ringkasan['kegiatan_desa'] + $ringkasan['bantuan_sosial'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Filter Laporan</h5>
                    <p class="text-muted small mb-0">Gunakan periode tanggal atau kombinasi bulan/tahun untuk membatasi data.</p>
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-start">
                    <span class="badge text-bg-success">Total {{ $ringkasan['total'] }}</span>
                    <span class="badge text-bg-primary">Masuk {{ $ringkasan['surat_masuk'] }}</span>
                    <span class="badge text-bg-success">Keluar {{ $ringkasan['surat_keluar'] }}</span>
                    <span class="badge text-bg-warning">Kegiatan {{ $ringkasan['kegiatan_desa'] }}</span>
                    <span class="badge text-bg-info">Bantuan {{ $ringkasan['bantuan_sosial'] }}</span>
                </div>
            </div>

            <form action="{{ url('/laporan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-2">
                    <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $filters['tanggal_mulai']) }}">
                </div>
                <div class="col-md-6 col-xl-2">
                    <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $filters['tanggal_selesai']) }}">
                </div>
                <div class="col-md-6 col-xl-2">
                    <label for="bulan" class="form-label fw-semibold">Bulan</label>
                    <select class="form-select" id="bulan" name="bulan">
                        <option value="">Semua Bulan</option>
                        @foreach($bulanList as $value => $label)
                            <option value="{{ $value }}" @selected((int) $filters['bulan'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label for="tahun" class="form-label fw-semibold">Tahun</label>
                    <select class="form-select" id="tahun" name="tahun">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" @selected((int) $filters['tahun'] === $tahun)>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label for="jenis_data" class="form-label fw-semibold">Jenis Data</label>
                    <select class="form-select" id="jenis_data" name="jenis_data">
                        @foreach($jenisData as $value => $label)
                            <option value="{{ $value }}" @selected($filters['jenis_data'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-xl-2">
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-success rounded-pill px-4"><i class="bi bi-funnel me-1"></i>Tampilkan</button>
                        <a href="{{ url('/laporan') }}" class="btn btn-light border rounded-pill px-4"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card module-card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="module-toolbar mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Data Laporan</h5>
                    <p class="text-muted small mb-0" id="laporanTableInfo">Menampilkan {{ $laporan->count() }} dari {{ $laporan->total() }} data laporan</p>
                </div>
                <div class="module-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" id="laporanSearch" placeholder="Cari laporan...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table data-table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nomor</th>
                            <th>Judul / Perihal</th>
                            <th>Pihak / Lokasi</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="laporanTableBody" data-admin-table data-search-input="#laporanSearch" data-pagination="#laporanClientPagination" data-table-info="#laporanTableInfo" data-empty-row="#laporanSearchEmpty" data-item-label="data laporan">
                        @forelse($laporan as $item)
                            <tr data-search-row="{{ strtolower($item['tanggal'].' '.$item['jenis'].' '.$item['nomor'].' '.$item['judul'].' '.$item['pihak'].' '.$item['status'].' '.$item['keterangan']) }}">
                                <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                                <td><span class="badge status-badge {{ $item['badge'] }}">{{ $item['jenis'] }}</span></td>
                                <td class="fw-semibold text-success">{{ $item['nomor'] }}</td>
                                <td class="text-start">{{ $item['judul'] }}</td>
                                <td>{{ $item['pihak'] }}</td>
                                <td><span class="badge status-badge text-bg-light text-dark border">{{ $item['status'] }}</span></td>
                                <td class="text-start">{{ $item['keterangan'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-file-earmark-bar-graph d-block mb-2"></i>
                                        <strong>Belum ada data laporan</strong>
                                        <p class="mb-0 small">Gunakan filter lain atau isi data administrasi terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="laporanSearchEmpty" class="d-none">
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
                <small class="text-muted">Data berasal dari Surat Masuk, Surat Keluar, Kegiatan Desa, dan Bantuan Sosial.</small>
                <div class="d-flex flex-column align-items-md-end gap-2">
                    <nav aria-label="Navigasi data laporan client">
                        <ul class="pagination pagination-sm mb-0" id="laporanClientPagination"></ul>
                    </nav>
                    @if($laporan->hasPages())
                        <div class="small">{{ $laporan->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection