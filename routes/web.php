<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SuratMasukController; // Tambahkan controller yang kita buat tadi
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 

Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// TAMBAHKAN 'prevent-back-history' DI SINI
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    
    // Route Dashboard Admin
    Route::get('/admin/dashboard', function () {
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

    // Route Surat Masuk (Menghubungkan ke Controller yang sudah kita buat)
    Route::get('/surat-masuk', [SuratMasukController::class, 'index']); // Halaman tabel
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create']); // Halaman form tambah
    Route::post('/surat-masuk/store', [SuratMasukController::class, 'store']); // Proses simpan data
});