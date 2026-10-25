<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama (/) langsung ke Beranda Laporan Orang 1 (jika sudah login)
Route::get('/', [ReportController::class, 'index'])->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk Modul Laporan (Orang 1) & Profile
Route::middleware('auth')->group(function () {
    // Rute CRUD Laporan (Beranda, Buat, Simpan, Detail, Edit, Update, Hapus)
    Route::resource('reports', ReportController::class);
    
    // Rute Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';