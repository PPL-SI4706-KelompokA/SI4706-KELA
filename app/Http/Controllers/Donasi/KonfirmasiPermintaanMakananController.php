<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KonfirmasiPermintaanMakananController extends Controller
{
    public function show($id)
    {
        $permintaan = permintaan::with(['donasi', 'user'])->findOrFail($id);
        
        // Tandai notifikasi sebagai dibaca jika ada
        if (auth()->check()) {
            Notifikasi::where('id_permintaan', $id)
                ->where('id_user', auth()->id())
                ->update(['status_baca' => 1]);

            // Jika user adalah Penerima, arahkan ke fitur rating atau detail
            if (auth()->user()->role === 'Penerima') {
                if ($permintaan->status === 'Disetujui' || $permintaan->status === 'Selesai') {
                    return redirect()->route('rating.create', $permintaan->id_donasi);
                }
                return redirect()->route('donasi.pesan.form', $permintaan->id_donasi);
            }
        }
        
        return view('donasi.konfirmasi', compact('permintaan'));
    }

    public function update(Request $request, $id)
    {
        // Donatur menyetujui atau menolak permintaan
        $data = permintaan::findOrFail($id);
        $data->update([
            'status' => $request->status, // 'Disetujui' atau 'Ditolak'
        ]);

        // Kirim notifikasi ke Penerima
        $pesan = $request->status === 'Disetujui'
            ? 'Selamat! Permintaan donasi makanan Anda telah disetujui oleh donatur. Silakan segera ambil makanannya.'
            : 'Mohon maaf, permintaan donasi makanan Anda ditolak oleh donatur.';

        Notifikasi::create([
            'id_user'            => $data->id_user, // Penerima yang memesan
            'id_permintaan'      => $data->id_permintaan,
            'pesan'              => $pesan,
            'tanggal_notifikasi' => now()->toDateString(),
            'status_baca'        => 0,
            'tipe_notifikasi'    => $request->status === 'Disetujui' ? 'Permintaan Disetujui' : 'Permintaan Ditolak',
        ]);

        return back()->with('success', 'Status permintaan berhasil diperbarui.');
    }
}