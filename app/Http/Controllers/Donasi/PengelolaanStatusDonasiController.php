<?php

namespace App\Http\Controllers\Donasi; // <-- Namespace harus sesuai nama folder

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Donasi; // Nanti dibuka jika model sudah ada

class PengelolaanStatusDonasiController extends Controller
{
    public function index()
    {
        // Ambil data donasi milik user yang login (karena ini fitur kelola donasi oleh donatur)
        $donasis = \App\Models\Donasi::all(); // Sementara all(), idealnya where id_user
        return view('donasi.kelola', compact('donasis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $donasi = \App\Models\Donasi::findOrFail($id);
        $donasi->status_donasi = $request->status_donasi;
        $donasi->save();
        
        return back()->with('success', 'Status donasi berhasil diperbarui menjadi ' . $request->status_donasi);
    }
}
        