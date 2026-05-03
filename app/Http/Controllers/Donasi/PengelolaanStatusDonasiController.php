<?php

namespace App\Http\Controllers\Donasi; // <-- Namespace harus sesuai nama folder

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Donasi; // Nanti dibuka jika model sudah ada

class PengelolaanStatusDonasiController extends Controller // <-- Class harus sama persis dengan nama file
{
    public function index()
    {
        $donasis = []; 
        return view('donasi.kelola', compact('donasis'));
    }

    public function updateStatus(Request $request, $id)
    {
        return back()->with('success', 'Status donasi berhasil diperbarui menjadi ' . $request->status);
    }
}
