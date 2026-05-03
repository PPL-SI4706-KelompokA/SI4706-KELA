<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiwayatDonasiController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap parameter filter status dari URL (misal: ?status=Selesai)
        $statusFilter = $request->query('status');

        // Membangun query dasar ke tabel donasis
        $query = DB::table('donasis')->orderBy('created_at', 'desc');

        // CATATAN: Jika tabel donasis sudah memiliki kolom 'user_id', 
        // aktifkan kode di bawah ini agar user hanya melihat riwayat miliknya sendiri:
        // $query->where('user_id', Auth::id());

        // Jika user mengklik tombol filter (Selesai/Diproses)
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Eksekusi query untuk mengambil data
        $riwayatDonasi = $query->get();

        // Mengirim data ke view riwayat.blade.php
        return view('donasi.riwayatdonasi', [
            'riwayatDonasi' => $riwayatDonasi,
            'statusAktif' => $statusFilter // Untuk menandai tombol filter yang sedang aktif
        ]);
    }
}