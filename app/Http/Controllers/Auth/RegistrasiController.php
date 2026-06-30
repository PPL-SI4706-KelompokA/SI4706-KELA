<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistrasiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input (Nama field harus sama dengan atribut 'name' di form Blade kamu)
        $request->validate([
            'name'      => 'required|string|max:255', // Gunakan 'name' agar seragam
            'email'     => 'required|string|email|max:255|unique:users',
            'phone'     => 'required|string|max:15',
            'address'   => 'required|string',
            'role'      => 'required|string',
            'password'  => 'required|string|min:8|confirmed',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 2. Simpan ke Database
        $id_lokasi = null;
        if ($request->latitude && $request->longitude) {
            $lokasi = \App\Models\Lokasi::create([
                'alamat'    => $request->address,
                'kota'      => 'Bandung', // Default kota
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            $id_lokasi = $lokasi->id_lokasi;
        }

        // KIRI: Nama kolom di tabel MySQL | KANAN: Data dari input form
        $user = User::create([
            'nama'      => $request->name,    // Pastikan kolom di DB adalah 'nama'
            'email'     => $request->email,
            'password'  => Hash::make($request->password), // Password wajib di-hash
            'role'      => $request->role,
            'no_telp'   => $request->phone,
            'alamat'    => $request->address,
            'id_lokasi' => $id_lokasi,
        ]);

        // Create verification notification for Admin
        if (in_array(strtolower($user->role), ['donatur', 'penerima'])) {
            $admins = User::whereIn('role', ['Admin', 'admin'])->get();
            foreach ($admins as $admin) {
                \App\Models\Notifikasi::create([
                    'id_user' => $admin->id_user,
                    'id_permintaan' => null,
                    'pesan' => ucfirst(strtolower($user->role)) . ' baru mendaftar: ' . $user->nama . '. Harap lakukan verifikasi.',
                    'tanggal_notifikasi' => now()->toDateString(),
                    'status_baca' => 0,
                    'tipe_notifikasi' => 'Permintaan Baru',
                ]);
            }
        }

        // 3. Redirect ke home dengan notifikasi
        return redirect()->route('home')->with('success', 'Akun berhasil didaftarkan! Silakan masuk (login) untuk melanjutkan.');
    }
}