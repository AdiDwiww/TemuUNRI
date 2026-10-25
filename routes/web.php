<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama (/) langsung ke Beranda Laporan Orang 1 (jika sudah login)
Route::get('/', [ReportController::class, 'index'])->middleware(['auth']);

// Rute untuk semua halaman yang membutuhkan login
Route::middleware('auth')->group(function () {
    // Dashboard Pengguna (Orang 2)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute CRUD Laporan (Orang 1)
    Route::resource('reports', ReportController::class);

    // Rute Profil Pengguna (Orang 2)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Admin — dilindungi oleh middleware auth DAN admin (pemeriksaan role di server)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Detail Laporan untuk Admin
    Route::get('/laporan/{laporan}', [AdminDashboardController::class, 'show'])->name('laporan.show');

    // Setujui laporan pending → status menjadi 'proses'
    Route::post('/laporan/{laporan}/setujui', [AdminDashboardController::class, 'setujui'])->name('laporan.setujui');

    // Tolak laporan pending → status menjadi 'Ditolak'
    Route::post('/laporan/{laporan}/tolak', [AdminDashboardController::class, 'tolak'])->name('laporan.tolak');
});

require __DIR__.'/auth.php';