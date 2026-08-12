<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BantuanSosial;
use App\Models\KegiatanDesa;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
    private array $jenisData = [
        'semua' => 'Semua',
        'surat_masuk' => 'Surat Masuk',
        'surat_keluar' => 'Surat Keluar',
        'kegiatan_desa' => 'Kegiatan Desa',
        'bantuan_sosial' => 'Bantuan Sosial',
    ];

    private array $bulanList = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    private array $reportTypes = [
        'surat-masuk' => [
            'title' => 'Laporan Data Surat Masuk',
            'description' => 'Daftar seluruh data surat masuk yang tercatat di sistem.',
            'icon' => 'bi-inbox',
            'period' => false,
        ],
        'surat-keluar' => [
            'title' => 'Laporan Data Surat Keluar',
            'description' => 'Daftar seluruh data surat keluar manual maupun berbasis template.',
            'icon' => 'bi-send',
            'period' => false,
        ],
        'kegiatan-desa' => [
            'title' => 'Laporan Data Kegiatan Desa',
            'description' => 'Rekap kegiatan desa berdasarkan data kegiatan yang sudah tersedia.',
            'icon' => 'bi-calendar-event',
            'period' => false,
        ],
        'bantuan-sosial' => [
            'title' => 'Laporan Data Bantuan Sosial',
            'description' => 'Rekap bantuan sosial dari data bantuan yang tercatat.',
            'icon' => 'bi-people',
            'period' => false,
        ],
        'surat-masuk-periode' => [
            'title' => 'Laporan Surat Masuk Berdasarkan Periode',
            'description' => 'Filter surat masuk berdasarkan tanggal mulai dan tanggal selesai.',
            'icon' => 'bi-calendar-range',
            'period' => true,
        ],
        'surat-keluar-periode' => [
            'title' => 'Laporan Surat Keluar Berdasarkan Periode',
            'description' => 'Filter surat keluar berdasarkan tanggal mulai dan tanggal selesai.',
            'icon' => 'bi-calendar-check',
            'period' => true,
        ],
        'administrasi-desa' => [
            'title' => 'Laporan Administrasi Desa',
            'description' => 'Gabungan data surat masuk, surat keluar, kegiatan desa, dan bantuan sosial.',
            'icon' => 'bi-file-earmark-bar-graph',
            'period' => false,
        ],
        'audit-log' => [
            'title' => 'Laporan Audit Log Aktivitas Pengguna',
            'description' => 'Riwayat aktivitas pengguna berdasarkan audit log yang tersedia.',
            'icon' => 'bi-activity',
            'period' => false,
        ],
    ];

    public function index(Request $request)
    {
        $this->authorizeAccess();

        $reportTypes = $this->reportTypes;
        $ringkasan = $this->dashboardRingkasan();

        AuditLog::catat($request, 'Melihat laporan', 'Laporan', 'Melihat daftar laporan SIPERDES.');

        return view('laporan', compact('reportTypes', 'ringkasan'));
    }

    public function preview(Request $request, string $jenis)
    {
        $this->authorizeAccess();

        [$report, $filters, $rows, $columns, $summary] = $this->buildReport($request, $jenis);

        AuditLog::catat($request, 'Preview laporan', 'Laporan', 'Preview ' . $report['title'] . '. ' . $this->reportFilterSummary($filters));

        return view('laporan-preview', compact('jenis', 'report', 'filters', 'rows', 'columns', 'summary'));
    }

    public function printReport(Request $request, string $jenis)
    {
        $this->authorizeAccess();

        [$report, $filters, $rows, $columns, $summary] = $this->buildReport($request, $jenis);

        AuditLog::catat($request, 'Print laporan', 'Laporan', 'Mencetak ' . $report['title'] . '. ' . $this->reportFilterSummary($filters));

        return view('print.laporan-detail', compact('jenis', 'report', 'filters', 'rows', 'columns', 'summary'));
    }

    public function print(Request $request)
    {
        $this->authorizeAccess();
        $filters = $this->validatedFilters($request);
        $laporan = $this->collectLaporan($filters);
        $ringkasan = $this->ringkasan($laporan);

        AuditLog::catat($request, 'Print laporan', 'Laporan', 'Mencetak laporan administrasi. ' . $this->filterSummary($filters));

        return view('print.laporan', compact('laporan', 'ringkasan', 'filters'));
    }

    public function exportPdf(Request $request)
    {
        $this->authorizeAccess();
        $filters = $this->validatedFilters($request);
        $laporan = $this->collectLaporan($filters);
        $ringkasan = $this->ringkasan($laporan);

        AuditLog::catat($request, 'Export PDF', 'Laporan', 'Export PDF laporan administrasi. ' . $this->filterSummary($filters));

        return Pdf::loadView('pdf.laporan', compact('laporan', 'ringkasan', 'filters'))
            ->setPaper('a4', 'landscape')
            ->download('laporan-siperdes-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $this->authorizeAccess();
        $filters = $this->validatedFilters($request);
        $laporan = $this->collectLaporan($filters);
        $ringkasan = $this->ringkasan($laporan);
        $filename = 'laporan-siperdes-' . now()->format('Ymd-His') . '.xls';

        AuditLog::catat($request, 'Export Excel', 'Laporan', 'Export Excel laporan administrasi. ' . $this->filterSummary($filters));

        return response()
            ->view('exports.laporan-excel', compact('laporan', 'ringkasan', 'filters'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function authorizeAccess(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Kepala Desa'], true)) {
            abort(403);
        }
    }

    private function buildReport(Request $request, string $jenis): array
    {
        abort_unless(isset($this->reportTypes[$jenis]), 404);

        $report = $this->reportTypes[$jenis];
        $filters = $this->validatedReportFilters($request, (bool) $report['period']);
        [$rows, $columns, $summary] = match ($jenis) {
            'surat-masuk' => $this->suratMasukReport(),
            'surat-keluar' => $this->suratKeluarReport(),
            'kegiatan-desa' => $this->kegiatanDesaReport(),
            'bantuan-sosial' => $this->bantuanSosialReport(),
            'surat-masuk-periode' => $this->suratMasukReport($filters),
            'surat-keluar-periode' => $this->suratKeluarReport($filters),
            'administrasi-desa' => $this->administrasiDesaReport(),
            'audit-log' => $this->auditLogReport(),
        };

        return [$report, $filters, $rows, $columns, $summary];
    }

    private function validatedReportFilters(Request $request, bool $requiresPeriod): array
    {
        $validated = $request->validate([
            'tanggal_mulai' => [$requiresPeriod ? 'required' : 'nullable', 'date'],
            'tanggal_selesai' => [$requiresPeriod ? 'required' : 'nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi untuk laporan berdasarkan periode.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi untuk laporan berdasarkan periode.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ]);

        return [
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ];
    }

    private function suratMasukReport(array $filters = []): array
    {
        $rows = $this->applyReportPeriod(SuratMasuk::query(), 'tanggal_surat', $filters)
            ->orderBy('tanggal_surat', 'desc')
            ->orderBy('id_surat_masuk', 'desc')
            ->get()
            ->map(fn (SuratMasuk $item) => [
                'tanggal' => $this->formatDate($item->tanggal_surat),
                'nomor_surat' => $item->nomor_surat,
                'pengirim' => $item->pengirim,
                'perihal' => $item->perihal,
                'status' => $item->status_verifikasi ?? '-',
            ]);

        return [$rows, [
            'tanggal' => 'Tanggal Surat',
            'nomor_surat' => 'Nomor Surat',
            'pengirim' => 'Pengirim',
            'perihal' => 'Perihal',
            'status' => 'Status Verifikasi',
        ], $this->simpleSummary($rows)];
    }

    private function suratKeluarReport(array $filters = []): array
    {
        $rows = $this->applyReportPeriod(SuratKeluar::with('templateSurat'), 'tanggal_surat', $filters)
            ->orderBy('tanggal_surat', 'desc')
            ->orderBy('id_surat_keluar', 'desc')
            ->get()
            ->map(fn (SuratKeluar $item) => [
                'tanggal' => $this->formatDate($item->tanggal_surat),
                'nomor_surat' => $item->nomor_surat,
                'tujuan' => $item->tujuan,
                'perihal' => $item->perihal,
                'metode' => $item->metode_pembuatan ?? ($item->id_template ? 'Template' : 'Upload'),
                'status_persetujuan' => $item->status_persetujuan ?? '-',
                'status_dokumen' => $item->status_dokumen ?? '-',
            ]);

        return [$rows, [
            'tanggal' => 'Tanggal Surat',
            'nomor_surat' => 'Nomor Surat',
            'tujuan' => 'Tujuan',
            'perihal' => 'Perihal',
            'metode' => 'Metode',
            'status_persetujuan' => 'Status Persetujuan',
            'status_dokumen' => 'Status Dokumen',
        ], $this->simpleSummary($rows)];
    }

    private function kegiatanDesaReport(): array
    {
        $rows = KegiatanDesa::query()
            ->orderBy('tanggal_kegiatan', 'desc')
            ->orderBy('id_kegiatan', 'desc')
            ->get()
            ->map(fn (KegiatanDesa $item) => [
                'tanggal' => $this->formatDate($item->tanggal_kegiatan),
                'nama_kegiatan' => $item->nama_kegiatan,
                'lokasi' => $item->lokasi,
                'penanggung_jawab' => $item->penanggung_jawab ?: '-',
                'tim_pelaksana' => $item->tim_pelaksana ?: '-',
                'keterangan' => $item->keterangan,
            ]);

        return [$rows, [
            'tanggal' => 'Tanggal Kegiatan',
            'nama_kegiatan' => 'Nama Kegiatan',
            'lokasi' => 'Lokasi',
            'penanggung_jawab' => 'Penanggung Jawab',
            'tim_pelaksana' => 'Tim Pelaksana',
            'keterangan' => 'Keterangan',
        ], $this->simpleSummary($rows)];
    }

    private function bantuanSosialReport(): array
    {
        $rows = BantuanSosial::query()
            ->orderBy('tanggal_bantuan', 'desc')
            ->orderBy('id_bantuan', 'desc')
            ->get()
            ->map(fn (BantuanSosial $item) => [
                'tanggal' => $this->formatDate($item->tanggal_bantuan),
                'nama_bantuan' => $item->nama_bantuan,
                'instansi_pemberi' => $item->instansi_pemberi,
                'jumlah_penerima' => number_format((int) $item->jumlah_penerima, 0, ',', '.'),
            ]);

        return [$rows, [
            'tanggal' => 'Tanggal Bantuan',
            'nama_bantuan' => 'Nama Bantuan',
            'instansi_pemberi' => 'Instansi Pemberi',
            'jumlah_penerima' => 'Jumlah Penerima',
        ], [
            'total' => $rows->count(),
            'total_penerima' => BantuanSosial::query()->sum('jumlah_penerima'),
        ]];
    }

    private function administrasiDesaReport(): array
    {
        $filters = [
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
            'bulan' => null,
            'tahun' => null,
            'jenis_data' => 'semua',
        ];
        $laporan = $this->collectLaporan($filters);
        $rows = $laporan->map(fn (array $item) => [
            'tanggal' => $this->formatDate($item['tanggal']),
            'jenis' => $item['jenis'],
            'nomor' => $item['nomor'],
            'judul' => $item['judul'],
            'pihak' => $item['pihak'],
            'status' => $item['status'],
            'keterangan' => $item['keterangan'],
        ]);

        return [$rows, [
            'tanggal' => 'Tanggal',
            'jenis' => 'Jenis Data',
            'nomor' => 'Nomor',
            'judul' => 'Judul / Perihal',
            'pihak' => 'Pihak / Lokasi',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ], $this->ringkasan($laporan)];
    }

    private function auditLogReport(): array
    {
        $rows = AuditLog::with('user')
            ->orderBy('waktu_akses', 'desc')
            ->orderBy('id_log', 'desc')
            ->get()
            ->map(fn (AuditLog $item) => [
                'waktu' => $item->waktu_akses ? $item->waktu_akses->format('d-m-Y H:i:s') : '-',
                'pengguna' => $item->user?->nama ?? '-',
                'role' => $item->user?->role ?? '-',
                'aktivitas' => $item->aktivitas_label,
                'modul' => $item->modul,
                'keterangan' => $item->keterangan,
                'ip_address' => $item->ip_address,
            ]);

        return [$rows, [
            'waktu' => 'Waktu Akses',
            'pengguna' => 'Pengguna',
            'role' => 'Role',
            'aktivitas' => 'Aktivitas',
            'modul' => 'Modul',
            'keterangan' => 'Keterangan',
            'ip_address' => 'IP Address',
        ], $this->simpleSummary($rows)];
    }

    private function applyReportPeriod(Builder $query, string $column, array $filters): Builder
    {
        if (!empty($filters['tanggal_mulai'])) {
            $query->whereDate($column, '>=', $filters['tanggal_mulai']);
        }

        if (!empty($filters['tanggal_selesai'])) {
            $query->whereDate($column, '<=', $filters['tanggal_selesai']);
        }

        return $query;
    }

    private function simpleSummary(Collection $rows): array
    {
        return ['total' => $rows->count()];
    }

    private function dashboardRingkasan(): array
    {
        return [
            'surat_masuk' => SuratMasuk::count(),
            'surat_keluar' => SuratKeluar::count(),
            'kegiatan_desa' => KegiatanDesa::count(),
            'bantuan_sosial' => BantuanSosial::count(),
            'audit_log' => AuditLog::count(),
        ];
    }

    private function formatDate($date): string
    {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)->format('d-m-Y');
    }

    private function reportFilterSummary(array $filters): string
    {
        if (empty($filters['tanggal_mulai']) && empty($filters['tanggal_selesai'])) {
            return 'Tanpa filter periode.';
        }

        return 'Periode: ' . ($filters['tanggal_mulai'] ?: 'awal') . ' s/d ' . ($filters['tanggal_selesai'] ?: 'akhir') . '.';
    }

    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'jenis_data' => 'nullable|in:semua,surat_masuk,surat_keluar,kegiatan_desa,bantuan_sosial',
        ]);

        return [
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'bulan' => isset($validated['bulan']) ? (int) $validated['bulan'] : null,
            'tahun' => isset($validated['tahun']) ? (int) $validated['tahun'] : null,
            'jenis_data' => $validated['jenis_data'] ?? 'semua',
        ];
    }

    private function collectLaporan(array $filters): Collection
    {
        $jenisData = $filters['jenis_data'];
        $items = collect();

        if ($jenisData === 'semua' || $jenisData === 'surat_masuk') {
            $items = $items->merge($this->suratMasuk($filters));
        }

        if ($jenisData === 'semua' || $jenisData === 'surat_keluar') {
            $items = $items->merge($this->suratKeluar($filters));
        }

        if ($jenisData === 'semua' || $jenisData === 'kegiatan_desa') {
            $items = $items->merge($this->kegiatanDesa($filters));
        }

        if ($jenisData === 'semua' || $jenisData === 'bantuan_sosial') {
            $items = $items->merge($this->bantuanSosial($filters));
        }

        return $items->sortByDesc('tanggal_sort')->values();
    }

    private function suratMasuk(array $filters): Collection
    {
        return $this->applyDateFilter(SuratMasuk::query(), 'tanggal_surat', $filters)
            ->orderBy('tanggal_surat', 'desc')
            ->get()
            ->map(fn ($item) => [
                'jenis' => 'Surat Masuk',
                'badge' => 'text-bg-primary',
                'tanggal' => $item->tanggal_surat,
                'tanggal_sort' => $item->tanggal_surat,
                'nomor' => $item->nomor_surat,
                'judul' => $item->perihal,
                'pihak' => $item->pengirim,
                'keterangan' => 'Pengirim: ' . $item->pengirim,
                'status' => $item->status_verifikasi ?? '-',
            ]);
    }

    private function suratKeluar(array $filters): Collection
    {
        return $this->applyDateFilter(SuratKeluar::query(), 'tanggal_surat', $filters)
            ->orderBy('tanggal_surat', 'desc')
            ->get()
            ->map(fn ($item) => [
                'jenis' => 'Surat Keluar',
                'badge' => 'text-bg-success',
                'tanggal' => $item->tanggal_surat,
                'tanggal_sort' => $item->tanggal_surat,
                'nomor' => $item->nomor_surat,
                'judul' => $item->perihal,
                'pihak' => $item->tujuan,
                'keterangan' => 'Tujuan: ' . $item->tujuan,
                'status' => $item->status_persetujuan ?? '-',
            ]);
    }

    private function kegiatanDesa(array $filters): Collection
    {
        return $this->applyDateFilter(KegiatanDesa::query(), 'tanggal_kegiatan', $filters)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get()
            ->map(fn ($item) => [
                'jenis' => 'Kegiatan Desa',
                'badge' => 'text-bg-warning',
                'tanggal' => $item->tanggal_kegiatan,
                'tanggal_sort' => $item->tanggal_kegiatan,
                'nomor' => '-',
                'judul' => $item->nama_kegiatan,
                'pihak' => $item->lokasi,
                'keterangan' => $item->keterangan,
                'status' => 'Tercatat',
            ]);
    }

    private function bantuanSosial(array $filters): Collection
    {
        return $this->applyDateFilter(BantuanSosial::query(), 'tanggal_bantuan', $filters)
            ->orderBy('tanggal_bantuan', 'desc')
            ->get()
            ->map(fn ($item) => [
                'jenis' => 'Bantuan Sosial',
                'badge' => 'text-bg-info',
                'tanggal' => $item->tanggal_bantuan,
                'tanggal_sort' => $item->tanggal_bantuan,
                'nomor' => '-',
                'judul' => $item->nama_bantuan,
                'pihak' => $item->instansi_pemberi,
                'keterangan' => 'Jumlah penerima: ' . $item->jumlah_penerima,
                'status' => 'Tercatat',
            ]);
    }

    private function applyDateFilter($query, string $column, array $filters)
    {
        if ($filters['tanggal_mulai']) {
            $query->whereDate($column, '>=', $filters['tanggal_mulai']);
        }

        if ($filters['tanggal_selesai']) {
            $query->whereDate($column, '<=', $filters['tanggal_selesai']);
        }

        if ($filters['bulan']) {
            $query->whereMonth($column, $filters['bulan']);
        }

        if ($filters['tahun']) {
            $query->whereYear($column, $filters['tahun']);
        }

        return $query;
    }

    private function paginate(Collection $items, Request $request): LengthAwarePaginator
    {
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($currentItems, $items->count(), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    private function ringkasan(Collection $items): array
    {
        return [
            'total' => $items->count(),
            'surat_masuk' => $items->where('jenis', 'Surat Masuk')->count(),
            'surat_keluar' => $items->where('jenis', 'Surat Keluar')->count(),
            'kegiatan_desa' => $items->where('jenis', 'Kegiatan Desa')->count(),
            'bantuan_sosial' => $items->where('jenis', 'Bantuan Sosial')->count(),
        ];
    }

    private function filterSummary(array $filters): string
    {
        $parts = [];
        $parts[] = 'Jenis: ' . ($this->jenisData[$filters['jenis_data']] ?? 'Semua');

        if ($filters['tanggal_mulai'] || $filters['tanggal_selesai']) {
            $parts[] = 'Periode: ' . ($filters['tanggal_mulai'] ?: 'awal') . ' s/d ' . ($filters['tanggal_selesai'] ?: 'akhir');
        }

        if ($filters['bulan']) {
            $parts[] = 'Bulan: ' . ($this->bulanList[$filters['bulan']] ?? $filters['bulan']);
        }

        if ($filters['tahun']) {
            $parts[] = 'Tahun: ' . $filters['tahun'];
        }

        return implode(', ', $parts);
    }
}
