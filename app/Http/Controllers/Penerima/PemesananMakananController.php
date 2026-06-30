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
        $donasi = Donasi::with(['user', 'lokasi'])->findOrFail($id_donasi);
        return view('penerima.create', compact('donasi'));
    }

    public function store(Request $request, $id_donasi)
    {
        $donasi = Donasi::findOrFail($id_donasi);
        
        // Validasi kadaluarsa
        if (\Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast()) {
            return back()->withErrors(['jumlah_permintaan' => 'Donasi makanan ini sudah kadaluarsa dan tidak dapat dipesan.'])->withInput();
        }

        // Validasi input
        $request->validate([
            'jumlah_permintaan' => 'required|integer|min:1|max:' . $donasi->jumlah,
            'nama_penerima'     => 'required|string|max:255',
            'nomor_telepon'     => ['required', 'string', 'regex:/^08[0-9]{9,11}$/'],
        ], [
            'jumlah_permintaan.required' => 'Jumlah porsi harus diisi.',
            'jumlah_permintaan.integer' => 'Jumlah porsi harus berupa angka.',
            'jumlah_permintaan.min' => 'Jumlah porsi harus lebih dari 0.',
            'jumlah_permintaan.max' => 'Jumlah porsi tidak boleh melebihi porsi yang tersedia (' . $donasi->jumlah . ' porsi).',
            'nama_penerima.required' => 'Nama penerima harus diisi.',
            'nomor_telepon.required' => 'Nomor telepon harus diisi.',
            'nomor_telepon.regex' => 'Nomor telepon harus berawalan 08 dan memiliki panjang 11-13 digit.',
        ]);

        $jumlahDiminta = (int) $request->jumlah_permintaan;

        // Update profil user (nama dan nomor telepon)
        $user = auth()->user() ?: \App\Models\User::find($donasi->id_user);
        if ($user) {
            $user->update([
                'nama' => $request->input('nama_penerima'),
                'no_telp' => $request->input('nomor_telepon')
            ]);
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