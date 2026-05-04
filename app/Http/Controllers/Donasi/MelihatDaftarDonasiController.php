<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class MelihatDaftarDonasiController extends Controller
{
    public function index()
    {
        // Menampilkan semua donasi dengan status tersedia
        $donasis = Donasi::where('status_donasi', 'Tersedia')->get();
        return view('donasi.daftar', compact('donasis'));
    }
}