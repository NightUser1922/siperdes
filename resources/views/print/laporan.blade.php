<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan SIPERDES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #111827; }
        .print-wrapper { max-width: 1180px; margin: 24px auto; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08); }
        .print-header { text-align: center; border-bottom: 2px solid #14532d; padding-bottom: 16px; margin-bottom: 20px; }
        .summary-badge { border: 1px solid #bbf7d0; background: #f0fdf4; color: #14532d; border-radius: 999px; padding: 6px 12px; display: inline-block; margin: 0 6px 8px 0; font-size: 13px; }
        table th { background: #dcfce7 !important; color: #14532d !important; }
        @media print {
            body { background: #fff; }
            .print-wrapper { margin: 0; padding: 0; max-width: none; box-shadow: none; border-radius: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="print-wrapper">
        <div class="d-flex justify-content-end gap-2 mb-3 no-print">
            <button type="button" class="btn btn-success rounded-pill px-4" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
            <button type="button" class="btn btn-light border rounded-pill px-4" onclick="window.close()">Tutup</button>
        </div>

        <div class="print-header">
            <h3 class="fw-bold mb-1">LAPORAN SIPERDES</h3>
            <p class="mb-0">Pemerintah Desa Amawang Kanan</p>
        </div>

        <div class="mb-3 small text-muted">
            Jenis Data: <strong>{{ ucwords(str_replace('_', ' ', $filters['jenis_data'] ?? 'semua')) }}</strong><br>
            Periode: <strong>{{ $filters['tanggal_mulai'] ?: 'Awal data' }}</strong> s/d <strong>{{ $filters['tanggal_selesai'] ?: 'Akhir data' }}</strong><br>
            Bulan/Tahun: <strong>{{ $filters['bulan'] ?: 'Semua Bulan' }}</strong> / <strong>{{ $filters['tahun'] ?: 'Semua Tahun' }}</strong>
        </div>

        <div class="mb-3">
            <span class="summary-badge">Total {{ $ringkasan['total'] }}</span>
            <span class="summary-badge">Surat Masuk {{ $ringkasan['surat_masuk'] }}</span>
            <span class="summary-badge">Surat Keluar {{ $ringkasan['surat_keluar'] }}</span>
            <span class="summary-badge">Kegiatan Desa {{ $ringkasan['kegiatan_desa'] }}</span>
            <span class="summary-badge">Bantuan Sosial {{ $ringkasan['bantuan_sosial'] }}</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
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
                <tbody>
                    @forelse($laporan as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                            <td>{{ $item['jenis'] }}</td>
                            <td>{{ $item['nomor'] }}</td>
                            <td>{{ $item['judul'] }}</td>
                            <td>{{ $item['pihak'] }}</td>
                            <td>{{ $item['status'] }}</td>
                            <td>{{ $item['keterangan'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>