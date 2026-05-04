<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use Illuminate\Http\Request;

class KonfirmasiPermintaanMakananController extends Controller
{
    public function update(Request $request, $id)
    {
        // Donatur menyetujui atau menolak permintaan
        $data = permintaan::findOrFail($id);
        $data->update([
            'status' => $request->status, // 'Disetujui' atau 'Ditolak'
            'tanggal_acc' => $request->status == 'Disetujui' ? now() : null,
            'tanggal_tolak' => $request->status == 'Ditolak' ? now() : null,
        ]);

        return back()->with('success', 'Status permintaan berhasil diperbarui.');
    }
}