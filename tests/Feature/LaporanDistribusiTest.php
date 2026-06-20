<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanDistribusiTest extends TestCase
{
    use RefreshDatabase;

    private function buatAdmin()
    {
        return User::factory()->create([
            'role' => 'admin'
        ]);
    }

    // TC-LDM-01
    public function test_admin_membuka_halaman_laporan_distribusi()
    {
        $admin = $this->buatAdmin();

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
        $response->assertSee('Laporan Distribusi');
    }

    // TC-LDM-02
    public function test_menampilkan_daftar_distribusi_terbaru()
    {
        $admin = $this->buatAdmin();

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
        $response->assertSee('Distribusi Terbaru');
    }

    // TC-LDM-03
    public function test_filter_laporan_berdasarkan_rentang_tanggal()
    {
        $admin = $this->buatAdmin();

        $response = $this->actingAs($admin)
            ->get('/admin/laporan?start_date=2026-06-01&end_date=2026-06-30');

        $response->assertStatus(200);
        $response->assertSee('Laporan Distribusi');
    }

    // TC-LDM-04
    public function test_tombol_unduh_pdf_tampil()
    {
        $admin = $this->buatAdmin();

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
        $response->assertSee('Unduh PDF');
    }

    // TC-LDM-05
    public function test_tidak_ada_transaksi_pada_periode_tertentu()
    {
        $admin = $this->buatAdmin();

        $response = $this->actingAs($admin)
            ->get('/admin/laporan?start_date=2020-01-01&end_date=2020-01-31');

        $response->assertStatus(200);
        $response->assertSee('Laporan Distribusi');
    }
}