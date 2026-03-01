<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Pastikan baris ini mengarah ke UserController, bukan view('welcome')
Route::get('/', [UserController::class, 'index'])->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// Route sementara agar tidak 404 setelah login
Route::get('/admin/dashboard', function () {
    return "<h1>Selamat Datang, Admin!</h1><p>Halaman Dashboard sedang dalam pembangunan.</p><a href='/'>Kembali</a>";
})->middleware('auth');

Route::get('/kades/dashboard', function () {
    return "<h1>Selamat Datang, Kades!</h1><p>Halaman Dashboard sedang dalam pembangunan.</p><a href='/'>Kembali</a>";
})->middleware('auth');