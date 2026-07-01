<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class FilterKategoriMakananController extends Controller
{
    public function index(Request $request)
    {
        // Map label tampilan ke nilai database
        $kategoriMap = [
            'Makanan' => 'Makanan Berat',
            'Snack'   => 'Cemilan / Snack',
            'Minuman' => 'Minuman',
        ];

        $kategoriLabel = $request->query('kategori', '');
        $kategoriDB    = $kategoriMap[$kategoriLabel] ?? $kategoriLabel;

        $query = Donasi::where('jumlah', '>', 0)
                       ->where('status_donasi', '!=', 'Distributed');

        if ($kategoriDB) {
            $query->where('kategori', $kategoriDB);
        }

        $donasis = $query->latest()->get();
        $kategori = $kategoriLabel; // untuk highlight tombol aktif di view

        return view('donasi.daftar', compact('donasis', 'kategori'));
    }
}