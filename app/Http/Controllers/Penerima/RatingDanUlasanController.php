<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Rating; // Pastikan file modelnya bernama Rating.php (huruf R besar)

class RatingDanUlasanController extends Controller
{
    // Menampilkan halaman form rating
    public function create(Donasi $donasi)
    {
        // Mengirim data donasi spesifik ke view
        return view('donasi.Rating', compact('donasi'));
    }

    // Menyimpan data rating ke database
    public function store(Request $request, Donasi $donasi)
    {
        // 1. Validasi input dari form
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // 2. Simpan ke tabel ratings
        Rating::create([
            'donasi_id' => $donasi->id,
            'rating'    => $request->rating,
            'review'    => $request->review,
            // Jika fitur login belum selesai, kita pasang angka 1 sementara agar tidak error
            'user_id'   => auth()->id() ?? 1 
        ]);

        // 3. Kembalikan ke halaman form dengan pesan sukses
        return redirect()->route('rating.create', $donasi->id)
                         ->with('success', 'Terima kasih! Ulasan berhasil dikirim.');
    }
}