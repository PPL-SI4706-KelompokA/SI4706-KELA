<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\permintaan;

class RiwayatPenerimaanMakananController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap parameter filter dari URL
        $statusFilter = $request->query('status');

        // Ambil permintaan milik penerima yang sedang login, beserta info donasinya
        $query = permintaan::with(['donasi', 'rating'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc');

        // Jika tombol filter diklik
        if ($statusFilter) {
            if (in_array(strtolower($statusFilter), ['pending', 'menunggu'])) {
                $query->where('status', 'Pending');
            } else {
                $query->where('status', $statusFilter);
            }
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