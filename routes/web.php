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

Route::get('/notifikasi/{id}/redirect', function ($id) {
    if (!auth()->check()) return redirect()->route('login');
    $notif = \App\Models\Notifikasi::where('id_notifikasi', $id)->where('id_user', auth()->id())->first();
    if ($notif) {
        $notif->status_baca = 1;
        $notif->save();
        if ($notif->id_permintaan) {
            return redirect()->route('permintaan.show', $notif->id_permintaan);
        }
    }
    return redirect()->back();
})->name('notifikasi.redirect');

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

// Riwayat Donasi
Route::get('/donasi/riwayat', [RiwayatDonasiController::class, 'index'])->name('donasi.riwayat')->middleware('auth');

// --- FITUR PENERIMA (FR06, FR18) ---
// Filter Kategori
Route::get('/donasi/filter', [FilterKategoriMakananController::class, 'index'])->name('donasi.filter');

// Pemesanan Makanan
Route::get('/donasi/{id_donasi}/pesan', [PemesananMakananController::class, 'create'])->name('donasi.pesan.form');
Route::post('/donasi/{id_donasi}/pesan', [PemesananMakananController::class, 'store'])->name('donasi.pesan');

// Riwayat Penerimaan
Route::get('/penerima/riwayat', [RiwayatPenerimaanMakananController::class, 'index'])->name('penerima.riwayatpenerimaan')->middleware('auth');

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
        return view('detailuser');
    })->name('profile.show');

    Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'no_telp' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string|max:255',
            'email'   => 'required|email|unique:users,email,' . auth()->id() . ',id_user',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = auth()->user();
        $data = $request->only('nama', 'no_telp', 'alamat', 'email');

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profile_pictures', 'public');
            $data['foto_profil'] = $path;
        }

        $user->update($data);
        
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    })->name('profile.update');

    // Chat / Messaging Routes
    Route::get('/pesan', [App\Http\Controllers\PesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/conversation/{recipientId}', [App\Http\Controllers\PesanController::class, 'getMessages'])->name('pesan.messages');
    Route::post('/pesan/send', [App\Http\Controllers\PesanController::class, 'sendMessage'])->name('pesan.send');
    Route::delete('/pesan/conversation/{recipientId}', [App\Http\Controllers\PesanController::class, 'deleteConversation'])->name('pesan.delete');

});

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/statistik', [DashboardStatistikController::class, 'index'])->name('admin.statistik');
    Route::get('/manajemen', [UserManajemenController::class, 'index'])->name('admin.manajemen');
    Route::delete('/manajemen/user/{id}', [UserManajemenController::class, 'destroy'])->name('admin.manajemen.destroy');
    Route::post('/manajemen/user/{id}/ban', [UserManajemenController::class, 'ban'])->name('admin.manajemen.ban');
    Route::post('/manajemen/user/{id}/unban', [UserManajemenController::class, 'unban'])->name('admin.manajemen.unban');
    Route::get('/laporan', [LaporanDistribusiMakananController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/print', [LaporanDistribusiMakananController::class, 'print'])->name('admin.laporan.print');
    Route::get('/laporan/export', [LaporanDistribusiMakananController::class, 'export'])->name('admin.laporan.export');
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('admin.verifikasi');
    Route::post('/verifikasi/{id}/setuju', [VerifikasiController::class, 'setuju'])->name('admin.verifikasi.setuju');
    Route::post('/verifikasi/{id}/tolak', [VerifikasiController::class, 'tolak'])->name('admin.verifikasi.tolak');
    
    // Fitur Notifikasi Maintenance & Announcements
    Route::get('/pemberitahuan', [PemberitahuanController::class, 'index'])->name('admin.pemberitahuan');
    Route::post('/pemberitahuan', [PemberitahuanController::class, 'store'])->name('admin.pemberitahuan.store');
    Route::delete('/pemberitahuan/{id}', [PemberitahuanController::class, 'destroy'])->name('admin.pemberitahuan.destroy');
});