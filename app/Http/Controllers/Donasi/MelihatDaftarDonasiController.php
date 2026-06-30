<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class MelihatDaftarDonasiController extends Controller
{
    public function index()
    {
        $query = Donasi::with(['user', 'lokasi']);

        // Exclude expired and out of stock donations for guests and Penerima role
        if (!auth()->check() || auth()->user()->role === 'Penerima') {
            $query->where('jumlah', '>', 0)
                  ->where('status_donasi', '!=', 'Distributed')
                  ->where('tanggal_kadaluarsa', '>', now());
        }

        $donasis = $query->latest()->get();
        $kategori = ''; // tidak ada filter aktif
        return view('donasi.daftar', compact('donasis', 'kategori'));
    }
}