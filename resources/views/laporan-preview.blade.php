@extends('layouts.app')

@section('title', $report['title'])

@section('content')
@php
    $printQuery = request()->query();
    $printUrl = url('/laporan/' . $jenis . '/print') . (count($printQuery) ? '?' . http_build_query($printQuery) : '');
    $periodeText = ($filters['tanggal_mulai'] || $filters['tanggal_selesai'])
        ? (($filters['tanggal_mulai'] ?: 'Awal data') . ' s/d ' . ($filters['tanggal_selesai'] ?: 'Akhir data'))
        : 'Seluruh data';
@endphp

<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3 no-print">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/laporan') }}" class="text-decoration-none text-success">Laporan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Preview</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-3 no-print">
        <div>
            <h4 class="fw-bold text-success mb-1">Preview {{ $report['title'] }}</h4>
            <p class="text-muted mb-0">Data preview sama dengan data pada halaman print.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/laporan') }}" class="btn btn-light border rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <a href="{{ $printUrl }}" target="_blank" class="btn btn-success rounded-pill px-4"><i class="bi bi-printer me-1"></i>Print</a>
        </div>
    </div>

    @if($report['period'])
        <div class="card module-card shadow-sm border-0 mb-4 no-print">
            <div class="card-body p-4">
                <form action="{{ url('/laporan/' . $jenis . '/preview') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] }}" required>
                    </div>
                    <div class="col-md-4 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-success rounded-pill px-4"><i class="bi bi-funnel me-1"></i>Tampilkan</button>
                        <button type="submit" class="btn btn-light border rounded-pill px-4" formaction="{{ url('/laporan/' . $jenis . '/print') }}" formtarget="_blank"><i class="bi bi-printer me-1"></i>Print</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card module-card shadow-sm border-0 report-preview-card">
        <div class="card-body p-4">
            @include('partials.laporan-table', [
                'title' => $report['title'],
                'description' => $report['description'],
                'periodeText' => $periodeText,
                'columns' => $columns,
                'rows' => $rows,
                'summary' => $summary,
            ])
        </div>
    </div>
</div>

<style>
    .report-preview-card table th { white-space: nowrap; }
    .report-preview-card table td { vertical-align: top; }
    @media print {
        @page { size: A4 landscape; margin: 12mm; }
        body { background: #fff !important; }
        .no-print, .sidebar, .navbar, .topbar, .breadcrumb { display: none !important; }
        .content-wrapper, .main-content, .container-fluid { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { border: 0 !important; box-shadow: none !important; }
        .card-body { padding: 0 !important; }
        table { page-break-inside: auto; font-size: 11px; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; page-break-after: auto; }
    }
</style>
@endsection
