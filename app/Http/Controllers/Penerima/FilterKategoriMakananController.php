<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class FilterKategoriMakananController extends Controller
{
    public function index(Request $request)
    {
        // Memfilter berdasarkan kategori yang dipilih user
        $kategori = $request->query('kategori');
        $donasis = Donasi::where('kategori', $kategori)->get();
        
        return view('donasi.daftar', compact('donasis', 'kategori'));
    }
}