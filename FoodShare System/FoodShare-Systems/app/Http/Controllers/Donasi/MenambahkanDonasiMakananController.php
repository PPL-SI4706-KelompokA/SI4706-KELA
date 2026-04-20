<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation; 

class MenambahkanDonasiMakananController extends Controller {
    public function __invoke(Request $request) {
        if ($request->isMethod('get')) {
            return view('donasi.create');
        }

        $request->validate([
            'nama_makanan' => 'required',
            'deskripsi' => 'required',
        ]);

        Donation::create([
            'nama_makanan' => $request->nama_makanan,
            'deskripsi' => $request->deskripsi,
            'user_id' => auth()->id(), 
            'status' => 'tersedia',
        ]);

        return redirect()->route('donasi.daftar')->with('success', 'Donasi berhasil ditambahkan!');
    }
}