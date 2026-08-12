<div class="report-document">
    <div class="text-center border-bottom border-success pb-3 mb-3">
        <h4 class="fw-bold text-uppercase mb-1">{{ $title }}</h4>
        <p class="mb-1">Pemerintah Desa Amawang Kanan</p>
        <p class="text-muted small mb-0">{{ $description }}</p>
    </div>

    <div class="row g-2 mb-3 small">
        <div class="col-md-4"><strong>Periode:</strong> {{ $periodeText }}</div>
        <div class="col-md-4"><strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y H:i') }}</div>
        <div class="col-md-4"><strong>Total Data:</strong> {{ $summary['total'] ?? $rows->count() }}</div>
    </div>

    @if(count($summary) > 1)
        <div class="d-flex flex-wrap gap-2 mb-3 small">
            @foreach($summary as $label => $value)
                <span class="badge text-bg-light text-dark border">{{ ucwords(str_replace('_', ' ', $label)) }}: {{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</span>
            @endforeach
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle report-table mb-0">
            <thead>
                <tr>
                    <th style="width: 44px;">No</th>
                    @foreach($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        @foreach(array_keys($columns) as $key)
                            <td>{{ $row[$key] ?? '-' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-4">Tidak ada data laporan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end mt-5 signature-section">
        <div class="col-5 text-center">
            <p class="mb-5">Amawang Kanan, {{ now()->format('d-m-Y') }}</p>
            <p class="fw-semibold mb-0">Kepala Desa Amawang Kanan</p>
        </div>
    </div>
</div>

<style>
    .report-document { color: #111827; }
    .report-table th { background: #dcfce7; color: #14532d; text-align: center; vertical-align: middle; }
    .report-table td { vertical-align: top; }
    .signature-section { page-break-inside: avoid; }
</style>
