<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use Illuminate\Http\Request;

class PemesananMakananController extends Controller
{
    public function store(Request $request, $id_donasi)
    {
        // Penerima melakukan reservasi/pemesanan makanan
        permintaan::create([
            'id_user' => auth()->id() ?? 1, // Default ke ID 1 jika belum ada auth
            'id_donasi' => $id_donasi,
            'jumlah_permintaan' => $request->jumlah_permintaan,
            'catatan' => $request->catatan,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Permintaan makanan berhasil dikirim.');
    }
}