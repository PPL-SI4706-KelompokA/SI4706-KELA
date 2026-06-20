<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class MelihatDaftarDonasiController extends Controller
{
    public function index()
    {
        // Menampilkan semua donasi yang masih tersedia (belum habis/distributed)
        $donasis = Donasi::where('jumlah', '>', 0)
                         ->where('status_donasi', '!=', 'Distributed')
                         ->latest()
                         ->get();
        $kategori = ''; // tidak ada filter aktif
        return view('donasi.daftar', compact('donasis', 'kategori'));
    }
}