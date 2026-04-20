<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;

class PengelolaanStatusDonasiController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $donasi = Donation::findOrFail($id);

        if ($donasi->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah status ini.');
        }

        $request->validate([
            'status' => 'required|in:tersedia,habis,selesai'
        ]);

        $donasi->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status donasi berhasil diperbarui menjadi ' . $request->status);
    }
}