<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\lokasi; // Sesuai nama file di strukturmu yang huruf kecil

class PetaLokasiDonasiController extends Controller
{
    public function index()
    {
        // Mengambil semua data lokasi untuk ditampilkan di peta
        $lokasis = lokasi::all();
        return view('donasi.peta', compact('lokasis'));
    }
}