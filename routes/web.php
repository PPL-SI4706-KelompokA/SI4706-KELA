<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donasi\MenambahkanDonasiMakananController;
use App\Http\Controllers\Donasi\PengelolaanStatusDonasiController;
use App\Http\Controllers\Donasi\RiwayatDonasiController;
use App\Http\Controllers\Penerima\RiwayatPenerimaanMakananController;

/*
|--------------------------------------------------------------------------
| Web Routes - Project FoodShare
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');


// ROUTE TESTING: Dilepas dari middleware 'auth' agar Anda bisa langsung buka
// Akses di: http://127.0.0.1:8000/donasi/tambah
Route::any('/donasi/tambah', MenambahkanDonasiMakananController::class)->name('donasi.tambah');

// Route untuk Update Status Donasi (FR17)
Route::patch('/donasi/update-status/{id}', [PengelolaanStatusDonasiController::class, 'updateStatus'])->name('donasi.updateStatus');

// Route untuk Halaman Riwayat Donasi <-- Tambahan rute baru
Route::get('/donasi/riwayat', [RiwayatDonasiController::class, 'index'])->name('donasi.riwayat');

// Route untuk Halaman Riwayat Penerimaan
Route::get('/penerimaan/riwayat', [RiwayatPenerimaanMakananController::class, 'index'])->name('penerimaan.riwayat');


// Kelompok Route yang membutuhkan Login (FR02)
Route::middleware(['auth'])->group(function () {
    // Jika Revaldo sudah menyelesaikan sistem Auth, pindahkan route donasi ke dalam sini
    
    Route::get('/dashboard', function () {
        return "Halaman Dashboard";
    })->name('dashboard');
});

// Route Login Manual (Hanya agar tidak error "Route login not defined")
Route::get('/login', function () {
    return "Silakan login terlebih dahulu jika middleware auth diaktifkan.";
})->name('login');