<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\permintaan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanDistribusiMakananController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $permintaanQuery = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved']);

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $permintaanQuery->whereBetween('created_at', [$start, $end]);

            $totalPenyaluran = (clone $permintaanQuery)->sum('jumlah_permintaan') ?: 0;

            // Growth calculation (this period vs previous period of same duration)
            $diffInDays = $start->diffInDays($end) + 1;
            $prevStart = (clone $start)->subDays($diffInDays);
            $prevEnd = (clone $end)->subDays($diffInDays);

            $lastPeriodQty = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved'])
                ->whereBetween('created_at', [$prevStart, $prevEnd])
                ->sum('jumlah_permintaan') ?: 0;

            if ($lastPeriodQty > 0) {
                $growthPct = round((($totalPenyaluran - $lastPeriodQty) / $lastPeriodQty) * 100);
                $growthSign = $growthPct >= 0 ? '+' : '';
                $growthText = $growthSign . $growthPct . '% dari periode lalu';
            } else {
                $growthText = $totalPenyaluran > 0 ? '+100% dari periode lalu' : '0% dari periode lalu';
            }
        } else {
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
        }

        // 2. Recent successful distributions within the selected range (if set)
        $distribusiTerbaru = (clone $permintaanQuery)
            ->with(['user', 'donasi'])
            ->latest()
            ->take(4)
            ->get();

        return view('admin.laporan', compact('totalPenyaluran', 'growthText', 'distribusiTerbaru'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $permintaanQuery = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved']);

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $permintaanQuery->whereBetween('created_at', [$start, $end]);
            $periodeText = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
        } else {
            $periodeText = 'Semua Periode';
        }

        $distributions = $permintaanQuery
            ->with(['user', 'donasi'])
            ->latest()
            ->get();

        $totalPenyaluran = $distributions->sum('jumlah_permintaan');

        return view('admin.print', compact('distributions', 'totalPenyaluran', 'periodeText'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $permintaanQuery = permintaan::whereIn('status', ['Selesai', 'Disetujui', 'Approved']);

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $permintaanQuery->whereBetween('created_at', [$start, $end]);
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=laporan_distribusi_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $distributions = $permintaanQuery
            ->with(['user', 'donasi'])
            ->latest()
            ->get();

        $columns = ['ID Permintaan', 'Nama Penerima', 'Makanan', 'Jumlah Porsi', 'Tanggal Distribusi', 'Status'];

        $callback = function() use($distributions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($distributions as $item) {
                fputcsv($file, [
                    $item->id_permintaan,
                    $item->user->nama ?? 'N/A',
                    $item->donasi->nama_makanan ?? 'N/A',
                    $item->jumlah_permintaan,
                    $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-',
                    $item->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
