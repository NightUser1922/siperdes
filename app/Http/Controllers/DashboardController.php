<?php

namespace App\Http\Controllers;

use App\Models\ArsipDigital;
use App\Models\BantuanSosial;
use App\Models\KegiatanDesa;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function admin()
    {
        if (auth()->user()->role === 'Kepala Desa') {
            return redirect('/kades/dashboard');
        }

        if (auth()->user()->role !== 'Admin') {
            abort(403);
        }

        return $this->dashboardData();
    }

    public function kades()
    {
        if (auth()->user()->role === 'Admin') {
            return redirect('/admin/dashboard');
        }

        if (auth()->user()->role !== 'Kepala Desa') {
            abort(403);
        }

        return $this->dashboardData();
    }

    private function dashboardData()
    {
        try {
            $totalMasuk = SuratMasuk::count();
        } catch (\Exception $e) {
            Log::error($e);
            $totalMasuk = 0;
        }

        try {
            $totalKeluar = SuratKeluar::count();
        } catch (\Exception $e) {
            Log::error($e);
            $totalKeluar = 0;
        }

        try {
            $totalKegiatan = KegiatanDesa::count();
        } catch (\Exception $e) {
            Log::error($e);
            $totalKegiatan = 0;
        }

        try {
            $totalBantuan = BantuanSosial::count();
        } catch (\Exception $e) {
            Log::error($e);
            $totalBantuan = 0;
        }

        try {
            $totalArsip = ArsipDigital::count();
        } catch (\Exception $e) {
            Log::error($e);
            $totalArsip = 0;
        }

        try {
            $pendingVerifikasiMasuk = SuratMasuk::where(function($q) {
                $q->where('status_verifikasi', 'Menunggu')->orWhereNull('status_verifikasi');
            })->count();
        } catch (\Exception $e) {
            Log::error($e);
            $pendingVerifikasiMasuk = 0;
        }

        try {
            $pendingPersetujuanKeluar = SuratKeluar::where(function($q) {
                $q->where('status_persetujuan', 'Menunggu')->orWhereNull('status_persetujuan');
            })->count();
        } catch (\Exception $e) {
            Log::error($e);
            $pendingPersetujuanKeluar = 0;
        }

        try {
            $trendLabels = [];
            $kegiatanTrend = [];
            $bantuanTrend = [];

            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-{$i} months"));
                [$year, $monthNumber] = explode('-', $month);

                $trendLabels[] = date('M Y', strtotime($month . '-01'));
                $kegiatanTrend[] = KegiatanDesa::whereYear('tanggal_kegiatan', $year)
                    ->whereMonth('tanggal_kegiatan', $monthNumber)
                    ->count();
                $bantuanTrend[] = BantuanSosial::whereYear('tanggal_bantuan', $year)
                    ->whereMonth('tanggal_bantuan', $monthNumber)
                    ->count();
            }

            $trendMax = max(array_merge([1], $kegiatanTrend, $bantuanTrend));
            $kemajuanKegiatanBulanIni = end($kegiatanTrend);
            $kemajuanBantuanBulanIni = end($bantuanTrend);
            $kemajuanKegiatanTotal = array_sum($kegiatanTrend);
            $kemajuanBantuanTotal = array_sum($bantuanTrend);
        } catch (\Exception $e) {
            Log::error($e);
            $trendLabels = [];
            $kegiatanTrend = [];
            $bantuanTrend = [];
            $trendMax = 1;
            $kemajuanKegiatanBulanIni = 0;
            $kemajuanBantuanBulanIni = 0;
            $kemajuanKegiatanTotal = 0;
            $kemajuanBantuanTotal = 0;
        }

        return view('dashboard', compact(
            'totalMasuk',
            'totalKeluar',
            'totalKegiatan',
            'totalBantuan',
            'totalArsip',
            'pendingVerifikasiMasuk',
            'pendingPersetujuanKeluar',
            'trendLabels',
            'kegiatanTrend',
            'bantuanTrend',
            'trendMax',
            'kemajuanKegiatanBulanIni',
            'kemajuanBantuanBulanIni',
            'kemajuanKegiatanTotal',
            'kemajuanBantuanTotal'
        ));
    }
}
