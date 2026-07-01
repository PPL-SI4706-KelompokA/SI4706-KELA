<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use App\Models\Donasi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PemesananMakananController extends Controller
{
    public function create($id_donasi)
    {
        $donasi = Donasi::findOrFail($id_donasi);
        return view('penerima.create', compact('donasi'));
    }

    public function store(Request $request, $id_donasi)
    {
        $donasi = Donasi::findOrFail($id_donasi);

        // Validasi input form dari user (Required, Regex, dan Numeric)
        $request->validate([
            'jumlah_permintaan' => 'required|integer|min:1',
            'nama_penerima'     => 'required|regex:/^[a-zA-Z\s]+$/',
            'nomor_telepon'     => 'required|numeric',
        ], [
            'jumlah_permintaan.required' => 'Jumlah porsi wajib diisi.',
            'nama_penerima.required'     => 'Nama penerima wajib diisi.',
            'nama_penerima.regex'        => 'Nama penerima hanya boleh berisi huruf.',
            'nomor_telepon.required'     => 'Nomor telepon wajib diisi.',
            'nomor_telepon.numeric'      => 'Nomor telepon hanya boleh berisi angka.',
        ]);

        // Validasi jumlah tidak melebihi stok tersedia (TC-05)
        $jumlahDiminta = (int) $request->jumlah_permintaan;
        if ($jumlahDiminta > $donasi->jumlah) {
            return back()->withErrors(['jumlah_permintaan' => 'Jumlah yang diminta ('.$jumlahDiminta.' porsi) melebihi stok tersedia ('.$donasi->jumlah.' porsi).'])->withInput();
        }

        // Simpan data permintaan
        permintaan::create([
            'id_user'           => auth()->id() ?? 1,
            'id_donasi'         => $id_donasi,
            'jumlah_permintaan' => $jumlahDiminta,
            'catatan'           => $request->catatan,
            'status'            => 'Pending',
        ]);

        // Kurangi jumlah stok donasi
        $donasi->jumlah -= $jumlahDiminta;

        // Update status otomatis berdasarkan sisa stok
        if ($donasi->jumlah <= 0) {
            $donasi->jumlah = 0;
            $donasi->status_donasi = 'Distributed'; // Stok habis, tandai selesai
        } else {
            $donasi->status_donasi = 'Booked'; // Masih ada sisa, tandai sudah ada yang pesan
        }

        $donasi->save();

        // Kirim notifikasi ke Donatur pemilik makanan
        $permintaanBaru = \App\Models\permintaan::where('id_donasi', $id_donasi)
            ->where('id_user', auth()->id() ?? 1)
            ->latest()->first();

        Notifikasi::create([
            'id_user'           => $donasi->id_user, // Donatur pemilik makanan
            'id_permintaan'     => $permintaanBaru->id_permintaan,
            'pesan'             => 'Ada permintaan baru untuk donasi "'.$donasi->nama_makanan.'" sebanyak '.$jumlahDiminta.' porsi.',
            'tanggal_notifikasi'=> now()->toDateString(),
            'status_baca'       => 0,
            'tipe_notifikasi'   => 'Permintaan Baru',
        ]);

        return back()->with('success', 'Permintaan makanan berhasil dikirim! Sisa porsi: '.$donasi->jumlah.' porsi.');
    }
}