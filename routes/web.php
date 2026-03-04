<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 

Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    
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

    // Route Surat Keluar
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index']);
    Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create']);
    Route::post('/surat-keluar/store', [SuratKeluarController::class, 'store']);
    
    // RUTE BARU: Edit, Update, dan Delete Surat Keluar
    Route::get('/surat-keluar/edit/{id}', [SuratKeluarController::class, 'edit']);
    Route::put('/surat-keluar/update/{id}', [SuratKeluarController::class, 'update']);
    Route::delete('/surat-keluar/delete/{id}', [SuratKeluarController::class, 'destroy']);
});

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