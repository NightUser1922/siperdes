<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SIPERDES</title>
</head>
<body>
    <table>
        <tr><th colspan="7">LAPORAN SIPERDES</th></tr>
        <tr><td colspan="7">Pemerintah Desa Amawang Kanan</td></tr>
        <tr><td colspan="7">Jenis Data: {{ ucwords(str_replace('_', ' ', $filters['jenis_data'] ?? 'semua')) }}</td></tr>
        <tr><td colspan="7">Periode: {{ $filters['tanggal_mulai'] ?: 'Awal data' }} s/d {{ $filters['tanggal_selesai'] ?: 'Akhir data' }}</td></tr>
        <tr><td colspan="7">Bulan/Tahun: {{ $filters['bulan'] ?: 'Semua Bulan' }} / {{ $filters['tahun'] ?: 'Semua Tahun' }}</td></tr>
        <tr><td colspan="7">Total Data: {{ $ringkasan['total'] }}</td></tr>
        <tr>
            <td>Surat Masuk: {{ $ringkasan['surat_masuk'] }}</td>
            <td>Surat Keluar: {{ $ringkasan['surat_keluar'] }}</td>
            <td>Kegiatan Desa: {{ $ringkasan['kegiatan_desa'] }}</td>
            <td>Bantuan Sosial: {{ $ringkasan['bantuan_sosial'] }}</td>
        </tr>
    </table>
    <table border="1">
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
                <tr><td colspan="7">Tidak ada data laporan.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>