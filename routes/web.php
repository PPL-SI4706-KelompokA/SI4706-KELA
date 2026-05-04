<?php

use Illuminate\Support\Facades\Route;

// Import Modul Donasi
use App\Http\Controllers\Donasi\MelihatDaftarDonasiController;
use App\Http\Controllers\Donasi\MenambahkanDonasiMakananController;
use App\Http\Controllers\Donasi\PencarianDonasiController;
use App\Http\Controllers\Donasi\PetaLokasiDonasiController;
use App\Http\Controllers\Donasi\KonfirmasiPermintaanMakananController;
use App\Http\Controllers\Donasi\PengelolaanStatusDonasiController;
use App\Http\Controllers\Donasi\RiwayatDonasiController;

// Import Modul Penerima
use App\Http\Controllers\Penerima\FilterKategoriMakananController;
use App\Http\Controllers\Penerima\PemesananMakananController;
use App\Http\Controllers\Penerima\RiwayatPenerimaanMakananController;
use App\Http\Controllers\Penerima\RatingDanUlasanController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes - Project FoodShare
|--------------------------------------------------------------------------
*/

// --- HALAMAN UTAMA & AUTH ---

// Menampilkan Form (Sudah ada di kode Anda)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// MENANGANI PENGIRIMAN DATA (Tambahkan ini agar tidak error 405)
// Sesuaikan nama Controller dengan yang ada di proyek tim Anda (misal: LoginController atau RegistrasiController)
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'store']);
Route::post('/register', [App\Http\Controllers\Auth\RegistrasiController::class, 'store']);

// --- FITUR UTAMA DONASI (FR04, FR05, FR16) ---
// Melihat Daftar Donasi
Route::get('/donasi', [MelihatDaftarDonasiController::class, 'index'])->name('donasi.daftar');

// Pencarian Donasi
Route::get('/donasi/cari', [PencarianDonasiController::class, 'index'])->name('donasi.cari');

// Peta Lokasi Donasi
Route::get('/donasi/peta', [PetaLokasiDonasiController::class, 'index'])->name('donasi.peta');

// --- FITUR MANAJEMEN DONASI (DONATUR) ---
// Tambah Donasi (Akses Tanpa Auth untuk Testing)
Route::any('/donasi/tambah', MenambahkanDonasiMakananController::class)->name('donasi.tambah');

// Kelola Status & Konfirmasi Permintaan (FR17 & FR07)
Route::get('/donasi/kelola', [PengelolaanStatusDonasiController::class, 'index'])->name('donasi.kelola');
Route::patch('/donasi/{id}/status', [PengelolaanStatusDonasiController::class, 'updateStatus'])->name('donasi.update-status');
Route::patch('/permintaan/{id_permintaan}/konfirmasi', [KonfirmasiPermintaanMakananController::class, 'update'])->name('permintaan.konfirmasi');

// Riwayat Donasi
Route::get('/donasi/riwayat', [RiwayatDonasiController::class, 'index'])->name('donasi.riwayat');

// --- FITUR PENERIMA (FR06, FR18) ---
// Filter Kategori
Route::get('/donasi/filter', [FilterKategoriMakananController::class, 'index'])->name('donasi.filter');

// Pemesanan Makanan
Route::post('/donasi/{id_donasi}/pesan', [PemesananMakananController::class, 'store'])->name('donasi.pesan');

// Riwayat Penerimaan
Route::get('/penerima/riwayat', [RiwayatPenerimaanMakananController::class, 'index'])->name('penerima.riwayatpenerimaan');

// --- RATING & ULASAN (FR11) ---
Route::get('/rating/donasi/{donasi}', [RatingDanUlasanController::class, 'create'])->name('rating.create');
Route::post('/rating/donasi/{donasi}', [RatingDanUlasanController::class, 'store'])->name('rating.store');
Route::get('/rating', function () {
    return view('penerima.rating');
})->name('rating.index');

// --- MIDDLEWARE AUTH (FR02) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return "Halaman Dashboard";
    })->name('dashboard');
});