<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController; // 1. Tambahkan Import Ini
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 

Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// Group Middleware untuk Keamanan
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    
    // Route Dashboard (Admin & Kades menggunakan logic yang sama)
    Route::get('/admin/dashboard', function () {
        return dashboardData();
    });

    Route::get('/kades/dashboard', function () {
        return dashboardData();
    });

    // Route Surat Masuk
    Route::get('/surat-masuk', [SuratMasukController::class, 'index']);
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create']);
    Route::post('/surat-masuk/store', [SuratMasukController::class, 'store']);

    // Route Surat Keluar (2. Tambahkan Baris Ini)
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index']);
    Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create']);
    Route::post('/surat-keluar/store', [SuratKeluarController::class, 'store']);
});

// Helper function agar tidak menulis ulang kode dashboard (opsional tapi lebih rapi)
function dashboardData() {
    try {
        $totalMasuk = DB::table('tb_surat_masuk')->count();
        $totalKeluar = DB::table('tb_surat_keluar')->count();
    } catch (\Exception $e) {
        $totalMasuk = 0;
        $totalKeluar = 0;
    }
    $totalArsip = $totalMasuk + $totalKeluar;
    return view('dashboard', compact('totalMasuk', 'totalKeluar', 'totalArsip'));
}