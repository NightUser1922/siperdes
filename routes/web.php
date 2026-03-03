<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// Route Dashboard diarahkan ke view 'dashboard' yang baru dibuat
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/kades/dashboard', function () {
    return view('dashboard');
})->middleware('auth');