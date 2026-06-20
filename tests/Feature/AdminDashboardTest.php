<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Donasi;
use App\Models\permintaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Lokasi $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin user
        $this->admin = User::factory()->create(['role' => 'admin']);

        // Create Location
        $this->lokasi = Lokasi::create([
            'alamat' => 'Jl. Test No. 123',
            'kota' => 'Jakarta',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);
    }

    public function test_admin_can_access_statistics_with_date_filters(): void
    {
        $donatur = User::factory()->create(['role' => 'Donatur', 'nama' => 'Budi Santoso']);

        // Create a donation in the past (10 days ago)
        $pastDonasi = Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Box',
            'kategori' => 'Makanan Berat',
            'jumlah' => 246,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi box lezat',
            'status_donasi' => 'Available',
        ]);
        $pastDonasi->created_at = Carbon::now()->subDays(10);
        $pastDonasi->save();

        // Create a donation today
        $todayDonasi = Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Roti Bakar',
            'kategori' => 'Camilan',
            'jumlah' => 135,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Roti bakar lezat',
            'status_donasi' => 'Available',
        ]);
        $todayDonasi->created_at = Carbon::now();
        $todayDonasi->save();

        // 1. Access statistics without filters - should include both (381 total)
        $response = $this->actingAs($this->admin)->get(route('admin.statistik'));
        $response->assertStatus(200);
        $response->assertSee('381'); // total donasi terkumpul

        // 2. Access statistics with filter for today only - should show 135 total
        $responseFiltered = $this->actingAs($this->admin)->get(route('admin.statistik', [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d'),
        ]));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee('135');
        $responseFiltered->assertDontSee('381');
    }

    public function test_admin_can_access_laporan_distribusi_with_date_filters(): void
    {
        $donatur = User::factory()->create(['role' => 'Donatur']);
        $receiver = User::factory()->create(['role' => 'Penerima', 'nama' => 'Budi Santoso']);

        $donasi = Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Sop Ayam',
            'kategori' => 'Makanan Berat',
            'jumlah' => 600,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Sop ayam hangat',
            'status_donasi' => 'Available',
        ]);

        // Distribution 1: 5 days ago (321 portions)
        $permintaanPast = permintaan::create([
            'id_user' => $receiver->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 321,
            'catatan' => 'Past',
            'status' => 'Selesai',
        ]);
        $permintaanPast->created_at = Carbon::now()->subDays(5);
        $permintaanPast->save();

        // Distribution 2: Today (179 portions)
        $permintaanToday = permintaan::create([
            'id_user' => $receiver->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 179,
            'catatan' => 'Today',
            'status' => 'Selesai',
        ]);
        $permintaanToday->created_at = Carbon::now();
        $permintaanToday->save();

        // 1. Access laporan without filters - should sum both (500 portions)
        $response = $this->actingAs($this->admin)->get(route('admin.laporan'));
        $response->assertStatus(200);
        $response->assertSee('500');

        // 2. Access laporan filtered to past only - should show 321 portions
        $responseFiltered = $this->actingAs($this->admin)->get(route('admin.laporan', [
            'start_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'end_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
        ]));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee('321');
        $responseFiltered->assertDontSee('179');

        // 3. Access print route filtered - should return print view with correct total
        $responsePrint = $this->actingAs($this->admin)->get(route('admin.laporan.print', [
            'start_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'end_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
        ]));
        $responsePrint->assertStatus(200);
        $responsePrint->assertSee('321');
        $responsePrint->assertSee('LAPORAN DISTRIBUSI MAKANAN');
    }
}
