<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'loginForm'])->name('auth.loginForm');
Route::post('/', [AuthController::class, 'login'])->name('auth.login');
Route::get('/otp', [AuthController::class, 'otpForm'])->name('auth.otpForm');
Route::post('/otp', [AuthController::class, 'otp'])->name('auth.otp');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::prefix('keuangan')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::post('/', [KeuanganController::class, 'search'])->name('keuangan.search');
        Route::post('/store', [KeuanganController::class, 'store'])->name('keuangan.store');
        Route::put('/{id}', [KeuanganController::class, 'update'])->name('keuangan.update');
        Route::delete('/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
    });

    Route::prefix('pengaturan')->group(function(){
        Route::get('/', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('/{id}', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });
});
