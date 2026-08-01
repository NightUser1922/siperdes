<?php

namespace App\Http\Controllers;

use App\Models\BantuanSosial;
use App\Models\KegiatanDesa;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function index(Request $request)
    {
        $this->authorizeAccess();
        $filters = $this->validatedFilters($request);
        $allLaporan = $this->collectLaporan($filters);
        $laporan = $this->paginate($allLaporan, $request);
        $ringkasan = $this->ringkasan($allLaporan);
        $jenisData = $this->jenisData;

        return view('laporan', compact('laporan', 'ringkasan', 'jenisData', 'filters'));
    }

    public function exportPdf(Request $request)
    {
        $this->authorizeAccess();
        $filters = $this->validatedFilters($request);
        $laporan = $this->collectLaporan($filters);
        $ringkasan = $this->ringkasan($laporan);

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

    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jenis_data' => 'nullable|in:semua,surat_masuk,surat_keluar,kegiatan_desa,bantuan_sosial',
        ]);

        return [
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
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
}