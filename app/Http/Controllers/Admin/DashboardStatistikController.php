<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStatistikController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $donasiQuery = Donasi::query();
        $userQuery = User::whereIn('role', ['Donatur', 'donatur']);
        
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            
            $donasiQuery->whereBetween('created_at', [$start, $end]);
            
            $totalDonasiTerkumpul = (clone $donasiQuery)->sum('jumlah') ?: 0;
            
            // Growth calculation (this period vs previous period of same duration)
            $diffInDays = $start->diffInDays($end) + 1;
            $prevStart = (clone $start)->subDays($diffInDays);
            $prevEnd = (clone $end)->subDays($diffInDays);
            
            $donationsPrevRange = Donasi::whereBetween('created_at', [$prevStart, $prevEnd])->sum('jumlah') ?: 0;
            if ($donationsPrevRange > 0) {
                $growthPct = round((($totalDonasiTerkumpul - $donationsPrevRange) / $donationsPrevRange) * 100, 1);
                $growthSign = $growthPct >= 0 ? '+' : '';
                $growthText = $growthSign . $growthPct . '%';
            } else {
                $growthText = $totalDonasiTerkumpul > 0 ? '+100%' : '0%';
            }
            
            // Total donatur who donated during this period
            $totalDonatur = (clone $donasiQuery)->distinct('id_user')->count('id_user') ?: 0;
            
            // Donatur baru registered during this period
            $donaturBaru = (clone $userQuery)->whereBetween('created_at', [$start, $end])->count() ?: 0;
            
        } else {
            $totalDonasiTerkumpul = Donasi::sum('jumlah') ?: 0;
            
            // Default growth calculation (this month vs last month)
            $thisMonthStart = Carbon::now()->startOfMonth();
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
            
            $donationsThisMonth = Donasi::where('created_at', '>=', $thisMonthStart)->sum('jumlah') ?: 0;
            $donationsLastMonth = Donasi::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('jumlah') ?: 0;
            
            if ($donationsLastMonth > 0) {
                $growthPct = round((($donationsThisMonth - $donationsLastMonth) / $donationsLastMonth) * 100, 1);
                $growthSign = $growthPct >= 0 ? '+' : '';
                $growthText = $growthSign . $growthPct . '%';
            } else {
                $growthText = $totalDonasiTerkumpul > 0 ? '+100%' : '0%';
            }
            
            $totalDonatur = User::whereIn('role', ['Donatur', 'donatur'])->count() ?: 0;
            $donaturBaru = User::whereIn('role', ['Donatur', 'donatur'])
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count() ?: 0;
        }
        
        $targetBulanan = 1500;
        $statusKeberhasilan = $totalDonasiTerkumpul > 0 ? round(($totalDonasiTerkumpul / $targetBulanan) * 100) : 0;

        // 2. Weight (KG) and CO2 Emissions from database values
        // Estimate 0.4 kg per donation unit
        $totalBerat = $totalDonasiTerkumpul * 0.4;
        // Estimate 2.5 kg CO2 reduced per 1 kg food saved
        $co2Reduction = round(($totalBerat * 2.5) / 1000, 2);

        // 4. Peak Donation Time (Weekdays vs Weekends) from database timestamps
        $donasis = (clone $donasiQuery)->get();
        $weekdaysQty = 0;
        $weekendsQty = 0;
        foreach ($donasis as $donasi) {
            $dayOfWeek = Carbon::parse($donasi->created_at)->dayOfWeek;
            // 0 = Sunday, 6 = Saturday
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $weekendsQty += $donasi->jumlah;
            } else {
                $weekdaysQty += $donasi->jumlah;
            }
        }
        
        $totalQty = $weekdaysQty + $weekendsQty;
        if ($totalQty > 0) {
            $weekdayPct = round(($weekdaysQty / $totalQty) * 100);
            $weekendPct = 100 - $weekdayPct;
        } else {
            $weekdayPct = 0;
            $weekendPct = 0;
        }

        // 5. Top Donators from database
        $topDonatorsRaw = (clone $donasiQuery)
            ->select('id_user', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('id_user')
            ->orderByDesc('total_jumlah')
            ->with('user')
            ->take(4)
            ->get();
            
        $topDonators = [];
        $rank = 1;
        foreach ($topDonatorsRaw as $raw) {
            if ($raw->user) {
                $topDonators[] = [
                    'rank'        => sprintf('%02d', $rank++),
                    'nama'        => $raw->user->nama,
                    'total_donasi'=> $raw->total_jumlah,
                    'status'      => $raw->total_jumlah > 100 ? 'Sangat Aktif' : ($raw->total_jumlah > 10 ? 'Aktif Berkala' : 'Baru Bergabung'),
                    'foto_profil' => $raw->user->foto_profil, // actual uploaded photo path
                ];
            }
        }

        return view('admin.statistik', compact(
            'totalDonasiTerkumpul',
            'growthText',
            'targetBulanan',
            'statusKeberhasilan',
            'totalBerat',
            'co2Reduction',
            'totalDonatur',
            'donaturBaru',
            'weekdayPct',
            'weekendPct',
            'topDonators'
        ));
    }
}
