<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use App\Models\Notifikasi;
use App\Models\Donasi;
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
        
        return redirect()->route('donasi.detail', $permintaan->id_donasi);
    }

    public function showDonasi($id_donasi)
    {
        $donasi = Donasi::findOrFail($id_donasi);

        // Jika user adalah Penerima, arahkan ke fitur rating atau detail
        if (auth()->check() && auth()->user()->role === 'Penerima') {
            $userPermintaan = permintaan::where('id_donasi', $id_donasi)
                ->where('id_user', auth()->id())
                ->first();
            if ($userPermintaan) {
                if ($userPermintaan->status === 'Disetujui' || $userPermintaan->status === 'Selesai') {
                    return redirect()->route('rating.create', $id_donasi);
                }
            }
            return redirect()->route('donasi.pesan.form', $id_donasi);
        }

        // Pastikan hanya pemilik donasi atau Admin yang bisa melihat detail/permintaan donasi ini
        if (auth()->check() && auth()->user()->role !== 'Admin' && auth()->user()->role !== 'admin' && $donasi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $allPermintaan = permintaan::with(['user'])
            ->where('id_donasi', $id_donasi)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('donasi.konfirmasi', compact('donasi', 'allPermintaan'));
    }


    public function update(Request $request, $id)
    {
        // Donatur menyetujui atau menolak permintaan
        $data = permintaan::findOrFail($id);

        // Pastikan hanya pemilik donasi atau Admin yang bisa konfirmasi permintaan
        if (auth()->check() && auth()->user()->role !== 'Admin' && auth()->user()->role !== 'admin' && $data->donasi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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