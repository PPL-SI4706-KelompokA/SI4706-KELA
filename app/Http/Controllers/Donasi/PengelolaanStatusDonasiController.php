<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;

class PengelolaanStatusDonasiController extends Controller
{
    /**
     * Memperbarui status donasi makanan (FR17)
     * Sesuai alur proses bisnis targeting di proposal
     */
    public function updateStatus(Request $request, $id)
    {
        // Cari donasi berdasarkan ID
        $donasi = Donasi::where('id_donasi', $id)->firstOrFail();

        // Pastikan hanya Donatur pemilik donasi ini yang bisa mengubah status
        if ($donasi->id_user !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah status ini.');
        }

        // Validasi status baru sesuai Mockup Filter (Tersedia, Dipesan, Selesai)
        $request->validate([
            'status' => 'required|in:Available,Requested,Selesai', 
        ]);

        // Update status di database
        $donasi->update([
            'status_donasi' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status donasi berhasil diubah menjadi ' . $request->status);
    }
}