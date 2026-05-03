<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donasi; // Diubah dari Donation agar sesuai dengan Model yang kita buat
use Illuminate\Support\Facades\Auth;

class MenambahkanDonasiMakananController extends Controller {

    /**
     * Menangani permintaan penambahan donasi (FR03)
     * Menggunakan __invoke agar controller ini bisa langsung dipanggil di route
     */
    public function __invoke(Request $request) {
        // Jika request GET, tampilkan halaman form tambah donasi (Mockup hal. 36)
        if ($request->isMethod('get')) {
            return view('donasi.create'); 
        }

        // 1. Validasi Input sesuai rancangan kebutuhan fungsional
        $request->validate([
            'nama_makanan' => 'required|max:100',
            'kategori'     => 'required',
            'jumlah'       => 'required|integer',
            'tanggal_kadaluarsa' => 'required|date',
            'deskripsi'    => 'required|max:255',
            'id_lokasi'    => 'required|exists:lokasis,id_lokasi', // Memastikan lokasi valid[cite: 2]
        ]);

        // 2. Simpan Data ke Tabel donasis (Sesuai Sequence Diagram)[cite: 2]
        Donasi::create([
            'id_user'           => Auth::id(), // Donatur yang sedang login[cite: 2]
            'id_lokasi'         => $request->id_lokasi,
            'nama_makanan'      => $request->nama_makanan,
            'kategori'          => $request->kategori,
            'jumlah'            => $request->jumlah,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'deskripsi'         => $request->deskripsi,
            'status_donasi'     => 'Available', // Status default sesuai mockup[cite: 2]
        ]);

        // 3. Redirect ke riwayat atau daftar donasi[cite: 2]
        return redirect()->route('donasi.daftar')->with('success', 'Donasi berhasil ditambahkan!');
    }
}