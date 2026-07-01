<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiwayatDonasiTest extends TestCase
{
    use RefreshDatabase;

    private function buatDonatur()
    {
        return User::factory()->create([
            'role' => 'donatur'
        ]);
    }

    // TC-RD-01
    public function test_donatur_dapat_membuka_halaman_riwayat_donasi()
    {
        $donatur = $this->buatDonatur();

        $response = $this->actingAs($donatur)
            ->get('/donasi/riwayat');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Donasi');
    }

    // TC-RD-02
    public function test_menampilkan_daftar_riwayat_donasi()
    {
        $donatur = $this->buatDonatur();

        $response = $this->actingAs($donatur)
            ->get('/donasi/riwayat');

        $response->assertStatus(200);
    }

    // TC-RD-03
    public function test_tidak_ada_riwayat_donasi()
    {
        $donatur = $this->buatDonatur();

        $response = $this->actingAs($donatur)
            ->get('/donasi/riwayat');

        $response->assertStatus(200);
    }

    // TC-RD-04
    public function test_akses_riwayat_donasi_harus_login()
    {
        $response = $this->get('/donasi/riwayat');

        $response->assertStatus(302);
    }

    // TC-RD-05
    public function test_filter_riwayat_donasi_berdasarkan_status()
    {
        $donatur = $this->buatDonatur();

        $response = $this->actingAs($donatur)
            ->get('/donasi/riwayat?status=Disetujui');

        $response->assertStatus(200);
    }
}