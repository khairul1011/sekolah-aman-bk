<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

// Halaman Utama (Dashboard)
Route::get('/', [DashboardController::class, 'index']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin']);
Route::get('/register', [AuthController::class, 'showRegister']);

// Laporan Routes
Route::get('/lapor', [LaporanController::class, 'create']);
Route::get('/riwayat', [LaporanController::class, 'riwayat']);
Route::get('/tindak-lanjut/{id}', [LaporanController::class, 'tindakLanjut']);

// Profile Route
Route::get('/profile', [ProfileController::class, 'index']);