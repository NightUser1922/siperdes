<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SIPERDES</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        .header { text-align: center; border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 16px; }
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 4px 0 0; }
        .summary { margin-bottom: 14px; }
        .summary span { display: inline-block; margin-right: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px; vertical-align: top; }
        th { background: #dcfce7; color: #14532d; text-align: left; }
        .meta { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN SIPERDES</h2>
        <p>Pemerintah Desa Amawang Kanan</p>
    </div>

    <div class="meta">
        Periode:
        {{ $filters['tanggal_mulai'] ? \Carbon\Carbon::parse($filters['tanggal_mulai'])->format('d-m-Y') : 'Awal data' }}
        s/d
        {{ $filters['tanggal_selesai'] ? \Carbon\Carbon::parse($filters['tanggal_selesai'])->format('d-m-Y') : 'Akhir data' }}
    </div>

    <div class="summary">
        <span>Total: {{ $ringkasan['total'] }}</span>
        <span>Surat Masuk: {{ $ringkasan['surat_masuk'] }}</span>
        <span>Surat Keluar: {{ $ringkasan['surat_keluar'] }}</span>
        <span>Kegiatan Desa: {{ $ringkasan['kegiatan_desa'] }}</span>
        <span>Bantuan Sosial: {{ $ringkasan['bantuan_sosial'] }}</span>
    </div>

    <table>
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
                    <td colspan="7">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>