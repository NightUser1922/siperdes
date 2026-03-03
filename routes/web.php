<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk akses database langsung

Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    
    // Route Dashboard Admin
    Route::get('/admin/dashboard', function () {
        try {
            // Menghitung otomatis dari tabel database
            $totalMasuk = DB::table('tb_surat_masuk')->count();
            $totalKeluar = DB::table('tb_surat_keluar')->count();
        } catch (\Exception $e) {
            // Jika tabel belum ada, otomatis set ke 0 agar tidak 404/500
            $totalMasuk = 0;
            $totalKeluar = 0;
        }
        $totalArsip = $totalMasuk + $totalKeluar;

        return view('dashboard', compact('totalMasuk', 'totalKeluar', 'totalArsip'));
    });

    // Route Dashboard Kades
    Route::get('/kades/dashboard', function () {
        try {
            $totalMasuk = DB::table('tb_surat_masuk')->count();
            $totalKeluar = DB::table('tb_surat_keluar')->count();
        } catch (\Exception $e) {
            $totalMasuk = 0;
            $totalKeluar = 0;
        }
        $totalArsip = $totalMasuk + $totalKeluar;

        return view('dashboard', compact('totalMasuk', 'totalKeluar', 'totalArsip'));
    });

    // Route Menu Lainnya
    Route::get('/surat-masuk', function () {
        return view('surat-masuk');
    });
});