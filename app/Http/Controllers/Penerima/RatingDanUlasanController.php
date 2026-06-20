<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\rating;
use App\Models\permintaan;

class RatingDanUlasanController extends Controller
{
    // Menampilkan halaman form rating
    public function create(Donasi $donasi)
    {
        // Cek apakah user sudah pernah rating donasi ini
        $permintaan = permintaan::where('id_donasi', $donasi->id_donasi)
            ->where('id_user', auth()->id())
            ->latest()->first();

        $existingRating = null;
        if ($permintaan) {
            $existingRating = rating::where('id_permintaan', $permintaan->id_permintaan)
                ->where('id_user', auth()->id())
                ->first();
        }

        return view('penerima.rating', compact('donasi', 'permintaan', 'existingRating'));
    }

    // Menyimpan data rating ke database
    public function store(Request $request, Donasi $donasi)
    {
        // 1. Validasi input dari form
        $request->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'nullable|string|max:1000',
            'id_permintaan' => 'required|exists:permintaans,id_permintaan',
        ]);

        // 2. Simpan atau update rating (updateOrCreate agar tidak duplikat)
        rating::updateOrCreate(
            [
                'id_user'       => auth()->id(),
                'id_permintaan' => $request->id_permintaan,
            ],
            [
                'nilai_rating'  => $request->rating,
                'komentar'      => $request->review,
            ]
        );

        // 3. Redirect ke halaman riwayat penerimaan dengan pesan sukses
        return redirect()->route('penerima.riwayatpenerimaan')
                         ->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim. ⭐');
    }
}