<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardStatistikController;
use App\Http\Controllers\Admin\UserManajemenController;
use App\Http\Controllers\Admin\LaporanDistribusiMakananController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\PemberitahuanController;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

// MENANGANI PENGIRIMAN DATA (Tambahkan ini agar tidak error 405)
// Sesuaikan nama Controller dengan yang ada di proyek tim Anda (misal: LoginController atau RegistrasiController)
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
Route::post('/register', [App\Http\Controllers\Auth\RegistrasiController::class, 'store']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Route polling notifikasi (dipanggil oleh JavaScript setiap beberapa detik)
Route::get('/notifikasi/check', function () {
    if (!auth()->check()) return response()->json(['count' => 0, 'items' => []]);
    $items = \App\Models\Notifikasi::where('id_user', auth()->id())
        ->latest()->take(6)->get(['id_notifikasi', 'id_permintaan', 'pesan', 'tipe_notifikasi', 'status_baca', 'created_at']);
    $count = \App\Models\Notifikasi::where('id_user', auth()->id())->where('status_baca', 0)->count();
    return response()->json(['count' => $count, 'items' => $items]);
})->name('notifikasi.check');

// Route untuk menandai notifikasi sebagai dibaca secara AJAX (tanpa redirect)
Route::get('/notifikasi/{id}/read', function ($id) {
    if (!auth()->check()) return response()->json(['error' => 'Unauthorized'], 401);
    $notif = \App\Models\Notifikasi::where('id_notifikasi', $id)->where('id_user', auth()->id())->first();
    if ($notif) {
        $notif->status_baca = 1;
        $notif->save();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false, 'error' => 'Not found'], 404);
})->name('notifikasi.read');

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
Route::get('/permintaan/{id_permintaan}', [KonfirmasiPermintaanMakananController::class, 'show'])->name('permintaan.show');
Route::get('/donasi/{id_donasi}/detail', [KonfirmasiPermintaanMakananController::class, 'showDonasi'])->name('donasi.detail');


// Riwayat Donasi
Route::get('/donasi/riwayat', [RiwayatDonasiController::class, 'index'])->name('donasi.riwayat');

// --- FITUR PENERIMA (FR06, FR18) ---
// Filter Kategori
Route::get('/donasi/filter', [FilterKategoriMakananController::class, 'index'])->name('donasi.filter');

// Pemesanan Makanan
Route::get('/donasi/{id_donasi}/pesan', [PemesananMakananController::class, 'create'])->name('donasi.pesan.form');
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

    Route::get('/detailuser', function () {
        if (!auth()->user()) {
            auth()->logout();
            return redirect()->route('login')->with('warning', 'Akun tidak ditemukan. Silakan login kembali.');
        }
        return view('detailuser');
    })->name('profile.show');

    Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'no_telp'     => 'nullable|string|max:20',
            'alamat'      => 'nullable|string|max:255',
            'email'       => 'required|email|unique:users,email,' . auth()->id() . ',id_user',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = auth()->user();
        $data = $request->only('nama', 'no_telp', 'alamat', 'email');
        
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_url) {
                $oldPath = public_path($user->foto_url);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $path = $request->file('foto_profil')->store('profile', 'public');
            $data['foto_url'] = '/storage/' . $path;
        }
        
        $user->update($data);
        
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    })->name('profile.update');
});

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/statistik', [DashboardStatistikController::class, 'index'])->name('admin.statistik');
    Route::post('/statistik/target', [DashboardStatistikController::class, 'updateTarget'])->name('admin.statistik.update-target');
    Route::get('/manajemen', [UserManajemenController::class, 'index'])->name('admin.manajemen');
    Route::post('/manajemen/{id_user}/ban', [UserManajemenController::class, 'ban'])->name('admin.user.ban');
    Route::post('/manajemen/{id_user}/unban', [UserManajemenController::class, 'unban'])->name('admin.user.unban');
    Route::get('/laporan', [LaporanDistribusiMakananController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/export-pdf', [LaporanDistribusiMakananController::class, 'exportPdf'])->name('admin.laporan.export-pdf');
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('admin.verifikasi');
    Route::post('/verifikasi/{id}/setuju', [VerifikasiController::class, 'setuju'])->name('admin.verifikasi.setuju');
    Route::post('/verifikasi/{id}/tolak', [VerifikasiController::class, 'tolak'])->name('admin.verifikasi.tolak');
    
    // Fitur Notifikasi Maintenance & Announcements
    Route::get('/pemberitahuan', [PemberitahuanController::class, 'index'])->name('admin.pemberitahuan');
    Route::post('/pemberitahuan', [PemberitahuanController::class, 'store'])->name('admin.pemberitahuan.store');
    Route::delete('/pemberitahuan/{id}', [PemberitahuanController::class, 'destroy'])->name('admin.pemberitahuan.destroy');
});