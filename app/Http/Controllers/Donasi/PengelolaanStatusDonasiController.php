<?php

namespace App\Http\Controllers\Donasi; // <-- Namespace harus sesuai nama folder

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Donasi; // Nanti dibuka jika model sudah ada

class PengelolaanStatusDonasiController extends Controller
{
    public function index()
    {
        // Hanya ambil data donasi milik user yang sedang login (kecuali Admin yang bisa lihat semua)
        if (auth()->user() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'admin')) {
            $donasis = \App\Models\Donasi::all();
        } else {
            $donasis = \App\Models\Donasi::where('id_user', auth()->id())->get();
        }
        return view('donasi.kelola', compact('donasis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $donasi = \App\Models\Donasi::findOrFail($id);
        
        // Pastikan hanya pemilik donasi atau Admin yang bisa mengubah status
        if (auth()->user()->role !== 'Admin' && auth()->user()->role !== 'admin' && $donasi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $donasi->status_donasi = $request->status_donasi;
        $donasi->save();
        
        return back()->with('success', 'Status donasi berhasil diperbarui menjadi ' . $request->status_donasi);
    }
}
        