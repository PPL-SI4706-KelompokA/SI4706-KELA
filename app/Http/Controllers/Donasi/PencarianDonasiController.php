<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class PencarianDonasiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        // Mencari berdasarkan nama makanan atau deskripsi
        $hasilPencarian = Donasi::where('nama_makanan', 'LIKE', "%{$q}%")
                                ->orWhere('deskripsi', 'LIKE', "%{$q}%")
                                ->get();

        return view('donasi.pencarian', compact('hasilPencarian', 'q'));
    }
}