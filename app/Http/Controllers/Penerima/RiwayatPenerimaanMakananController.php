<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donasi; // Menggunakan model Donasi sebagai contoh data penerimaan

class RiwayatPenerimaanMakananController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap parameter filter dari URL
        $statusFilter = $request->query('status');

        // Mengambil data dari database, urut dari yang paling baru
        $query = Donasi::query()->orderBy('created_at', 'desc');

        // Jika tombol filter diklik
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Membagi data 10 per halaman
        $riwayatPenerimaan = $query->paginate(10);

        // Mengirim data ke file riwayat.blade.php di folder penerimaan
        return view('penerima.riwayatpenerimaan', [
            'riwayatPenerimaan' => $riwayatPenerimaan,
            'statusAktif'       => $statusFilter
        ]);
    }
}