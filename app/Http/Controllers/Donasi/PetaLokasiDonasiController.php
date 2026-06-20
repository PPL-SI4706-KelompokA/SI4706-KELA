<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;

class PetaLokasiDonasiController extends Controller
{
    public function index()
    {
        // Mengambil semua data donasi beserta lokasinya yang statusnya bukan Distributed
        $donasis = Donasi::with(['lokasi', 'user'])
            ->where('status_donasi', '!=', 'Distributed')
            ->get();

        // Self-healing fallback for database records with 0,0 coordinates
        foreach ($donasis as $donasi) {
            if ($donasi->lokasi && floatval($donasi->lokasi->latitude) == 0 && floatval($donasi->lokasi->longitude) == 0) {
                // Assign a deterministic offset using id so they don't stack on top of each other in Bandung
                $id = $donasi->id_donasi;
                $donasi->lokasi->latitude = -6.917464 + (($id * 17) % 100 - 50) / 15000;
                $donasi->lokasi->longitude = 107.619123 + (($id * 23) % 100 - 50) / 15000;
            }
        }

        return view('donasi.peta', compact('donasis'));
    }
}