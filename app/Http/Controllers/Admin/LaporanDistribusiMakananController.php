<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanDistribusiMakananController extends Controller
{
    public function index()
    {
        // 1. Total Penyaluran (Sum of quantity in completed/approved permintaans)
        $totalPenyaluran = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved'])
            ->sum('jumlah_permintaan') ?: 0;

        // Growth calculation (this month vs last month)
        $thisMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $thisMonthQty = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved'])
            ->where('created_at', '>=', $thisMonthStart)
            ->sum('jumlah_permintaan') ?: 0;

        $lastMonthQty = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved'])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('jumlah_permintaan') ?: 0;

        if ($lastMonthQty > 0) {
            $growthPct = round((($thisMonthQty - $lastMonthQty) / $lastMonthQty) * 100);
            $growthSign = $growthPct >= 0 ? '+' : '';
            $growthText = $growthSign . $growthPct . '% dari bulan lalu';
        } else {
            $growthText = $totalPenyaluran > 0 ? '+100% dari bulan lalu' : '0% dari bulan lalu';
        }

        // 2. Recent successful distributions
        $distribusiTerbaru = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved'])
            ->with(['user', 'donasi'])
            ->latest()
            ->take(4)
            ->get();

        return view('admin.laporan', compact('totalPenyaluran', 'growthText', 'distribusiTerbaru'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved'])
            ->with(['user', 'donasi.user']);

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $distribusiData = $query->latest()->get();

        $totalPenyaluran = $distribusiData->sum('jumlah_permintaan') ?: 0;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_pdf', compact('totalPenyaluran', 'distribusiData', 'startDate', 'endDate'));
        
        return $pdf->download('laporan-distribusi-makanan-' . ($startDate ? $startDate . '-to-' . $endDate : date('Y-m-d')) . '.pdf');
    }
}
