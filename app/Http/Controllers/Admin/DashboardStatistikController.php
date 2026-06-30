<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStatistikController extends Controller
{
    public function index(Request $request)
    {
        // 1. Total Donasi Terkumpul (Sum of Donasi quantities in database)
        $totalDonasiTerkumpul = Donasi::sum('jumlah') ?: 0;
        
        // Growth calculation (this month vs last month)
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
        
        $targetBulanan = (int) Setting::get('target_bulanan', 1500);
        $statusKeberhasilan = $totalDonasiTerkumpul > 0 ? round(($totalDonasiTerkumpul / $targetBulanan) * 100) : 0;

        // 2. Donators count from database
        $totalDonatur = User::whereIn('role', ['Donatur', 'donatur'])->count() ?: 0;
        $donaturBaru = User::whereIn('role', ['Donatur', 'donatur'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count() ?: 0;

        // 5. Top Donators from database (with date range filtering)
        $topDonatorsQuery = Donasi::select('id_user', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('id_user')
            ->orderByDesc('total_jumlah')
            ->with('user');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $topDonatorsQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $topDonatorsRaw = $topDonatorsQuery->take(4)->get();
            
        $topDonators = [];
        $rank = 1;
        foreach ($topDonatorsRaw as $raw) {
            if ($raw->user) {
                $topDonators[] = [
                    'rank' => sprintf('%02d', $rank++),
                    'nama' => $raw->user->nama,
                    'total_donasi' => $raw->total_jumlah,
                    'status' => $raw->total_jumlah > 100 ? 'Sangat Aktif' : ($raw->total_jumlah > 10 ? 'Aktif Berkala' : 'Baru Bergabung'),
                    'foto' => 'https://ui-avatars.com/api/?name=' . urlencode($raw->user->nama) . '&background=random'
                ];
            }
        }

        return view('admin.statistik', compact(
            'totalDonasiTerkumpul',
            'growthText',
            'targetBulanan',
            'statusKeberhasilan',
            'totalDonatur',
            'donaturBaru',
            'topDonators',
            'startDate',
            'endDate'
        ));
    }

    public function updateTarget(Request $request)
    {
        $request->validate([
            'target_bulanan' => 'required|integer|min:1',
        ]);

        Setting::set('target_bulanan', $request->input('target_bulanan'));

        return redirect()->route('admin.statistik')->with('success', 'Target bulanan berhasil diperbarui!');
    }
}

