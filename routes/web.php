<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donasi\MenambahkanDonasiMakananController;
use App\Http\Controllers\Donasi\PengelolaanStatusDonasiController;
use App\Http\Controllers\Penerima\RatingDanUlasanController;

// Route untuk form rating
Route::get('/rating/donasi/{donasi}', [RatingDanUlasanController::class, 'create'])->name('rating.create');
Route::post('/rating/donasi/{donasi}', [RatingDanUlasanController::class, 'store'])->name('rating.store');
Route::get('/rating', function () {
    return view('donasi.Rating');
});



/*
|--------------------------------------------------------------------------
| Web Routes - Project FoodShare
|--------------------------------------------------------------------------
*/

// Halaman Utama Laravel (Opsional)
Route::get('/', function () {
    return view('welcome');
});

// ROUTE TESTING: Dilepas dari middleware 'auth' agar Anda bisa langsung buka
// Akses di: http://127.0.0.1:8000/donasi/tambah
Route::any('/donasi/tambah', MenambahkanDonasiMakananController::class)->name('donasi.tambah');

// Route untuk Update Status Donasi (FR17)
Route::patch('/donasi/update-status/{id}', [PengelolaanStatusDonasiController::class, 'updateStatus'])->name('donasi.updateStatus');


// Kelompok Route yang membutuhkan Login (FR02)[cite: 2]
Route::middleware(['auth'])->group(function () {
    // Jika Revaldo sudah menyelesaikan sistem Auth, pindahkan route donasi ke dalam sini[cite: 2]
    
    Route::get('/dashboard', function () {
        return "Halaman Dashboard";
    })->name('dashboard');
});

// Route Login Manual (Hanya agar tidak error "Route login not defined")
Route::get('/login', function () {
    return "Silakan login terlebih dahulu jika middleware auth diaktifkan.";
})->name('login');
