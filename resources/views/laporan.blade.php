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

    <div class="card module-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                <div class="d-flex gap-3 align-items-start">
                    <span class="module-icon fs-3"><i class="bi bi-file-earmark-bar-graph"></i></span>
                    <div>
                        <span class="badge text-bg-success mb-2">Report Center</span>
                        <h4 class="fw-bold text-success mb-1">Laporan SIPERDES</h4>
                        <p class="text-muted mb-0">Pilih jenis laporan, tampilkan preview, lalu cetak langsung dari browser.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-primary">Surat Masuk {{ $ringkasan['surat_masuk'] }}</span>
                    <span class="badge text-bg-success">Surat Keluar {{ $ringkasan['surat_keluar'] }}</span>
                    <span class="badge text-bg-warning">Kegiatan {{ $ringkasan['kegiatan_desa'] }}</span>
                    <span class="badge text-bg-info">Bantuan {{ $ringkasan['bantuan_sosial'] }}</span>
                    <span class="badge text-bg-dark">Audit {{ $ringkasan['audit_log'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($reportTypes as $key => $report)
            <div class="col-md-6 col-xl-4">
                <div class="card module-card shadow-sm border-0 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex gap-3 align-items-start mb-3">
                            <span class="module-icon"><i class="bi {{ $report['icon'] }}"></i></span>
                            <div>
                                <span class="badge {{ $report['period'] ? 'text-bg-warning' : 'text-bg-success' }} mb-2">{{ $report['period'] ? 'Periode' : 'Data' }}</span>
                                <h5 class="fw-bold mb-1">{{ $report['title'] }}</h5>
                                <p class="text-muted small mb-0">{{ $report['description'] }}</p>
                            </div>
                        </div>

                        <form action="{{ url('/laporan/' . $key . '/preview') }}" method="GET" class="mt-auto">
                            @if($report['period'])
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label for="tanggal_mulai_{{ $key }}" class="form-label small fw-semibold">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="tanggal_mulai_{{ $key }}" name="tanggal_mulai" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="tanggal_selesai_{{ $key }}" class="form-label small fw-semibold">Tanggal Selesai</label>
                                        <input type="date" class="form-control" id="tanggal_selesai_{{ $key }}" name="tanggal_selesai" required>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-success rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i>Preview
                                </button>
                                @if($report['period'])
                                    <button type="submit" class="btn btn-light border rounded-pill px-3" formaction="{{ url('/laporan/' . $key . '/print') }}" formtarget="_blank">
                                        <i class="bi bi-printer me-1"></i>Print
                                    </button>
                                @else
                                    <a href="{{ url('/laporan/' . $key . '/print') }}" target="_blank" class="btn btn-light border rounded-pill px-3">
                                        <i class="bi bi-printer me-1"></i>Print
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
