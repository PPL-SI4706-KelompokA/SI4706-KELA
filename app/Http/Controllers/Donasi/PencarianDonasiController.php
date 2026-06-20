<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class PencarianDonasiController extends Controller
{
    public function index(Request $request)
    {
        $q        = $request->query('q', '');
        $kategori = $request->query('kategori', '');
        $status   = $request->query('status', []);

        $query = Donasi::query();

        // Filter berdasarkan kata kunci pencarian
        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('nama_makanan', 'LIKE', "%{$q}%")
                   ->orWhere('deskripsi', 'LIKE', "%{$q}%");
            });
        }

        // Filter berdasarkan kategori makanan (sesuaikan dengan nilai di DB)
        if ($kategori) {
            // Map label tampilan ke nilai database
            $kategoriMap = [
                'Makanan' => 'Makanan Berat',
                'Snack'   => 'Cemilan / Snack',
                'Minuman' => 'Minuman',
            ];
            $dbKategori = $kategoriMap[$kategori] ?? $kategori;
            $query->where('kategori', $dbKategori);
        }

        // Filter berdasarkan status donasi
        if (!empty($status)) {
            $statusMap = [
                'Tersedia' => ['Available', 'Tersedia'],
                'Dipesan'  => ['Booked', 'Dipesan'],
                'Selesai'  => ['Distributed', 'Selesai'],
            ];

            $dbStatus = [];
            foreach ($status as $s) {
                if (isset($statusMap[$s])) {
                    $dbStatus = array_merge($dbStatus, $statusMap[$s]);
                }
            }

            if (!empty($dbStatus)) {
                $query->whereIn('status_donasi', $dbStatus);
            }
        }

        // Gunakan paginate agar nomor halaman dinamis
        $hasilPencarian = $query->paginate(9)->withQueryString();

        return view('donasi.pencarian', compact('hasilPencarian', 'q', 'kategori', 'status'));
    }
}