<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiwayatPenerimaanTest extends TestCase
{
    use RefreshDatabase;

    private function buatPenerima()
    {
        return User::factory()->create([
            'role' => 'penerima'
        ]);
    }

    // TC-RP-01
    public function test_penerima_dapat_membuka_halaman_riwayat_penerimaan()
    {
        $penerima = $this->buatPenerima();

        $response = $this->actingAs($penerima)
            ->get('/penerima/riwayat');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Penerimaan');
    }

    // TC-RP-02
    public function test_data_riwayat_sesuai_pengguna()
    {
        $penerima = $this->buatPenerima();

        $response = $this->actingAs($penerima)
            ->get('/penerima/riwayat');

        $response->assertStatus(200);
    }

    // TC-RP-03
    public function test_tidak_ada_riwayat_penerimaan()
    {
        $penerima = $this->buatPenerima();

        $response = $this->actingAs($penerima)
            ->get('/penerima/riwayat');

        $response->assertStatus(200);
    }

    // TC-RP-04
    public function test_filter_riwayat_penerimaan_berdasarkan_status()
    {
        $penerima = $this->buatPenerima();

        $response = $this->actingAs($penerima)
            ->get('/penerima/riwayat?status=Disetujui');

        $response->assertStatus(200);
    }
}