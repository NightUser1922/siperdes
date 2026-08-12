<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{ $report['title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        body { background: #f8fafc; color: #111827; font-size: 12px; }
        .print-wrapper { max-width: 1180px; margin: 20px auto; background: #fff; padding: 28px; border-radius: 8px; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08); }
        .no-print { display: flex; }
        table { page-break-inside: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        @media print {
            body { background: #fff; }
            .print-wrapper { margin: 0; padding: 0; max-width: none; box-shadow: none; border-radius: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    @php
        $periodeText = ($filters['tanggal_mulai'] || $filters['tanggal_selesai'])
            ? (($filters['tanggal_mulai'] ?: 'Awal data') . ' s/d ' . ($filters['tanggal_selesai'] ?: 'Akhir data'))
            : 'Seluruh data';
    @endphp

    <div class="print-wrapper">
        <div class="justify-content-end gap-2 mb-3 no-print">
            <button type="button" class="btn btn-success rounded-pill px-4" onclick="window.print()">Print</button>
            <button type="button" class="btn btn-light border rounded-pill px-4" onclick="window.close()">Tutup</button>
        </div>

        @include('partials.laporan-table', [
            'title' => $report['title'],
            'description' => $report['description'],
            'periodeText' => $periodeText,
            'columns' => $columns,
            'rows' => $rows,
            'summary' => $summary,
        ])
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
