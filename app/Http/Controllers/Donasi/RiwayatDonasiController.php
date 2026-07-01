<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatDonasiController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap parameter filter status dari URL (misal: ?status=Selesai)
        $statusFilter = $request->query('status');

        // Hanya tampilkan donasi milik donatur yang sedang login
        $query = Donasi::with(['permintaans.user', 'lokasi'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status_donasi jika ada
        if ($statusFilter) {
            $query->where('status_donasi', $statusFilter);
        }

        // Eksekusi query
        $riwayatDonasi = $query->get();

        // Mengirim data ke view riwayat.blade.php
        return view('donasi.riwayatdonasi', [
            'riwayatDonasi' => $riwayatDonasi,
            'statusAktif' => $statusFilter // Untuk menandai tombol filter yang sedang aktif
        ]);
    }
}