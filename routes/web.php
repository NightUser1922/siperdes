<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\KegiatanDesaController;
use App\Http\Controllers\BantuanSosialController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TemplateSuratController;
use App\Models\BantuanSosial;
use App\Models\KegiatanDesa;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role === 'Kepala Desa') {
            return redirect('/kades/dashboard');
        }

        if (auth()->user()->role !== 'Admin') {
            abort(403);
        }

        return dashboardData();
    });

    Route::get('/kades/dashboard', function () {
        if (auth()->user()->role === 'Admin') {
            return redirect('/admin/dashboard');
        }

        if (auth()->user()->role !== 'Kepala Desa') {
            abort(403);
        }

        return dashboardData();
    });

    // ==========================================
    // ROUTE SURAT MASUK
    // ==========================================
    Route::get('/surat-masuk', [SuratMasukController::class, 'index']);
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create']);
    Route::post('/surat-masuk/store', [SuratMasukController::class, 'store']);
    Route::get('/surat-masuk/edit/{id}', [SuratMasukController::class, 'edit']);
    Route::put('/surat-masuk/update/{id}', [SuratMasukController::class, 'update']);
    Route::delete('/surat-masuk/delete/{id}', [SuratMasukController::class, 'destroy']);

    // ==========================================
    // ROUTE TEMPLATE SURAT
    // ==========================================
    Route::get('/template-surat', [TemplateSuratController::class, 'index']);
    Route::get('/template-surat/create', [TemplateSuratController::class, 'create']);
    Route::post('/template-surat/store', [TemplateSuratController::class, 'store']);
    Route::get('/template-surat/edit/{id}', [TemplateSuratController::class, 'edit']);
    Route::put('/template-surat/update/{id}', [TemplateSuratController::class, 'update']);
    Route::get('/template-surat/download/{id}', [TemplateSuratController::class, 'download']);
    Route::delete('/template-surat/delete/{id}', [TemplateSuratController::class, 'destroy']);

    // ==========================================
    // ROUTE SURAT KELUAR
    // ==========================================
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index']);
    Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create']);
    Route::post('/surat-keluar/store', [SuratKeluarController::class, 'store']);
    Route::post('/surat-keluar/preview-template', [SuratKeluarController::class, 'previewTemplate']);
    Route::post('/surat-keluar/download-template', [SuratKeluarController::class, 'downloadTemplate']);
    Route::get('/surat-keluar/edit/{id}', [SuratKeluarController::class, 'edit']);
    Route::put('/surat-keluar/update/{id}', [SuratKeluarController::class, 'update']);
    Route::get('/surat-keluar/{id}/preview', [SuratKeluarController::class, 'preview']);
    Route::get('/surat-keluar/{id}/download', [SuratKeluarController::class, 'download']);
    Route::post('/surat-keluar/{id}/generate', [SuratKeluarController::class, 'generate']);
    Route::delete('/surat-keluar/delete/{id}', [SuratKeluarController::class, 'destroy']);

    // ==========================================
    // ROUTE KEGIATAN DESA
    // ==========================================
    Route::get('/kegiatan-desa', [KegiatanDesaController::class, 'index']);
    Route::get('/kegiatan-desa/create', [KegiatanDesaController::class, 'create']);
    Route::post('/kegiatan-desa/store', [KegiatanDesaController::class, 'store']);
    Route::get('/kegiatan-desa/edit/{id}', [KegiatanDesaController::class, 'edit']);
    Route::put('/kegiatan-desa/update/{id}', [KegiatanDesaController::class, 'update']);
    Route::delete('/kegiatan-desa/delete/{id}', [KegiatanDesaController::class, 'destroy']);

    // ==========================================
    // ROUTE BANTUAN SOSIAL
    // ==========================================
    Route::get('/bantuan-sosial', [BantuanSosialController::class, 'index']);
    Route::get('/bantuan-sosial/create', [BantuanSosialController::class, 'create']);
    Route::post('/bantuan-sosial/store', [BantuanSosialController::class, 'store']);
    Route::get('/bantuan-sosial/edit/{id}', [BantuanSosialController::class, 'edit']);
    Route::put('/bantuan-sosial/update/{id}', [BantuanSosialController::class, 'update']);
    Route::delete('/bantuan-sosial/delete/{id}', [BantuanSosialController::class, 'destroy']);


    // ==========================================
    // ROUTE LAPORAN
    // ==========================================
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/print', [LaporanController::class, 'print']);
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf']);
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel']);
    // ==========================================
    // ROUTE AUDIT LOG
    // ==========================================
    Route::get('/audit-log', [AuditLogController::class, 'index']);
});

function dashboardData()
{
    try {
        $totalMasuk = SuratMasuk::count();
    } catch (\Exception $e) {
        $totalMasuk = 0;
    }

    try {
        $totalKeluar = SuratKeluar::count();
    } catch (\Exception $e) {
        $totalKeluar = 0;
    }

    try {
        $totalKegiatan = KegiatanDesa::count();
    } catch (\Exception $e) {
        $totalKegiatan = 0;
    }

    try {
        $totalBantuan = BantuanSosial::count();
    } catch (\Exception $e) {
        $totalBantuan = 0;
    }


    return view('dashboard', compact(
        'totalMasuk',
        'totalKeluar',
        'totalKegiatan',
        'totalBantuan'
    ));
}