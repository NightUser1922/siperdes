<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Pastikan baris ini mengarah ke UserController, bukan view('welcome')
Route::get('/', [UserController::class, 'index'])->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);