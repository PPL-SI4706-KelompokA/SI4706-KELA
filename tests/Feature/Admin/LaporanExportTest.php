<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Donasi;
use App\Models\permintaan;
use App\Models\lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class LaporanExportTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $donatur;
    private $penerima;
    private $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin
        $this->admin = User::factory()->create(['role' => 'Admin']);

        // Create Users for Donasi & Permintaan
        $this->donatur = User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);
        $this->penerima = User::factory()->create(['role' => 'Penerima', 'status_verifikasi' => 'Sudah Verifikasi']);

        // Create Lokasi
        $this->lokasi = lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10',
            'kota' => 'Bandung',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
        ]);
    }

    public function test_admin_can_access_laporan_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/laporan');
        $response->assertStatus(200);
        $response->assertSee('Laporan Distribusi');
        $response->assertSee('Tanggal Mulai');
        $response->assertSee('Tanggal Selesai');
    }

    public function test_export_pdf_without_filters(): void
    {
        // Create a Donasi
        $donasi = Donasi::create([
            'id_user' => $this->donatur->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Goreng Berkah',
            'kategori' => 'Makanan Berat',
            'jumlah' => 50,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi' => 'Nasi goreng halal dan nikmat',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Approved',
        ]);

        // Create a completed Permintaan
        $permintaan = permintaan::create([
            'id_user' => $this->penerima->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 10,
            'catatan' => 'Butuh untuk makan malam',
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/laporan/export-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('laporan-distribusi-makanan-', $response->headers->get('content-disposition'));
    }

    public function test_export_pdf_with_date_range_filters(): void
    {
        // Set dates
        $dateOld = Carbon::now()->subDays(10);
        $dateMid = Carbon::now()->subDays(5);
        $dateNew = Carbon::now()->subDays(1);

        // Create Donasis
        $donasi1 = Donasi::create([
            'id_user' => $this->donatur->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Box A',
            'kategori' => 'Makanan Berat',
            'jumlah' => 20,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi' => 'Nasi Box Enak',
            'status_donasi' => 'Available',
        ]);

        $donasi2 = Donasi::create([
            'id_user' => $this->donatur->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Box B',
            'kategori' => 'Makanan Berat',
            'jumlah' => 30,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi' => 'Nasi Box Lezat',
            'status_donasi' => 'Available',
        ]);

        // Create Permintaans with specific timestamps
        // Record 1: 10 days ago (outside range)
        Carbon::setTestNow($dateOld);
        $permintaanOld = permintaan::create([
            'id_user' => $this->penerima->id_user,
            'id_donasi' => $donasi1->id_donasi,
            'jumlah_permintaan' => 5,
            'status' => 'Selesai',
        ]);
        $permintaanOld->created_at = $dateOld;
        $permintaanOld->save();

        // Record 2: 5 days ago (inside range)
        Carbon::setTestNow($dateMid);
        $permintaanMid = permintaan::create([
            'id_user' => $this->penerima->id_user,
            'id_donasi' => $donasi2->id_donasi,
            'jumlah_permintaan' => 15,
            'status' => 'Selesai',
        ]);
        $permintaanMid->created_at = $dateMid;
        $permintaanMid->save();

        // Record 3: 1 day ago (outside range if we filter only up to 3 days ago)
        Carbon::setTestNow($dateNew);
        $permintaanNew = permintaan::create([
            'id_user' => $this->penerima->id_user,
            'id_donasi' => $donasi2->id_donasi,
            'jumlah_permintaan' => 25,
            'status' => 'Selesai',
        ]);
        $permintaanNew->created_at = $dateNew;
        $permintaanNew->save();

        // Reset Carbon test time
        Carbon::setTestNow();

        // Query between 7 days ago and 3 days ago
        $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = Carbon::now()->subDays(3)->format('Y-m-d');

        $response = $this->actingAs($this->admin)->get("/admin/laporan/export-pdf?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        
        // Assert download filename contains the date range
        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString("laporan-distribusi-makanan-{$startDate}-to-{$endDate}.pdf", $contentDisposition);

        // Verify the view structure when rendered directly
        $view = $this->view('admin.laporan_pdf', [
            'totalPenyaluran' => 15, // Only the mid one should be counted
            'distribusiData' => collect([$permintaanMid]),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        $view->assertSee('Periode:');
        $view->assertSee(Carbon::parse($startDate)->translatedFormat('d F Y'));
        $view->assertSee(Carbon::parse($endDate)->translatedFormat('d F Y'));
        $view->assertSee('Nasi Box B');
        $view->assertDontSee('Nasi Box A');
    }

    public function test_admin_can_access_statistics_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/statistik');
        
        $response->assertStatus(200);
        $response->assertSee('Statistik Donasi');
        $response->assertSee('Total Donasi Terkumpul');
        $response->assertSee('DONATUR');
        $response->assertSee('Donatur Teratas');
        $response->assertDontSee('BERAT (KG)');
        $response->assertDontSee('Busy Donation Time');
    }

    public function test_admin_can_update_target_bulanan(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/statistik/target', [
            'target_bulanan' => 2500,
        ]);

        $response->assertRedirect();
        $this->assertEquals(2500, \App\Models\Setting::get('target_bulanan'));

        // Visit statistics page to verify new value is displayed
        $responsePage = $this->actingAs($this->admin)->get('/admin/statistik');
        $responsePage->assertSee('2.500');
    }

    public function test_top_donators_can_be_filtered_by_date(): void
    {
        $donor1 = User::factory()->create(['role' => 'Donatur', 'nama' => 'Donatur Lama']);
        $donor2 = User::factory()->create(['role' => 'Donatur', 'nama' => 'Donatur Baru']);

        $dateOld = Carbon::now()->subDays(10);
        $dateNew = Carbon::now()->subDays(1);

        // Donasi 1 (Old)
        Carbon::setTestNow($dateOld);
        $donasi1 = Donasi::create([
            'id_user' => $donor1->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Makanan Lama',
            'kategori' => 'Makanan Berat',
            'jumlah' => 100,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi' => 'Deskripsi',
            'status_donasi' => 'Available',
        ]);
        $donasi1->created_at = $dateOld;
        $donasi1->save();

        // Donasi 2 (New)
        Carbon::setTestNow($dateNew);
        $donasi2 = Donasi::create([
            'id_user' => $donor2->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Makanan Baru',
            'kategori' => 'Makanan Berat',
            'jumlah' => 50,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi' => 'Deskripsi',
            'status_donasi' => 'Available',
        ]);
        $donasi2->created_at = $dateNew;
        $donasi2->save();

        Carbon::setTestNow();

        // Filter: last 3 days
        $startDate = Carbon::now()->subDays(3)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $response = $this->actingAs($this->admin)->get("/admin/statistik?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);
        $response->assertSee('Donatur Baru');
        $response->assertDontSee('Donatur Lama');
    }
}
