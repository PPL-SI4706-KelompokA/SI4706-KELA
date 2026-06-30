<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Donasi;
use App\Models\lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemesananMakananTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $donasi;
    private $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'Penerima', 'status_verifikasi' => 'Sudah Verifikasi']);
        
        $this->lokasi = lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10',
            'kota' => 'Bandung',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
        ]);

        $this->donasi = Donasi::create([
            'id_user' => User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi'])->id_user,
            'id_lokasi' => $this->lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Bakar Ayam',
            'kategori' => 'Makanan Berat',
            'jumlah' => 10,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi' => 'Nasi bakar lezat',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Approved',
        ]);
    }

    public function test_booking_form_renders_with_user_data(): void
    {
        $this->user->nama = 'Andi Budiman';
        $this->user->no_telp = '081234567890';
        $this->user->save();

        $response = $this->actingAs($this->user)->get("/donasi/{$this->donasi->id_donasi}/pesan");

        $response->assertStatus(200);
        $response->assertSee('Andi Budiman');
        $response->assertSee('081234567890');
    }

    public function test_valid_booking_submission_succeeds(): void
    {
        $response = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => 5,
            'nama_penerima' => 'Andi Budiman',
            'nomor_telepon' => '081234567890',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        // Check if user profile is updated
        $this->user->refresh();
        $this->assertEquals('Andi Budiman', $this->user->nama);
        $this->assertEquals('081234567890', $this->user->no_telp);

        // Check if reservation is created
        $this->assertDatabaseHas('permintaans', [
            'id_user' => $this->user->id_user,
            'jumlah_permintaan' => 5,
            'status' => 'Pending',
        ]);
    }

    public function test_booking_fails_if_inputs_are_missing(): void
    {
        $response = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => '',
            'nama_penerima' => '',
            'nomor_telepon' => '',
        ]);

        $response->assertSessionHasErrors(['jumlah_permintaan', 'nama_penerima', 'nomor_telepon']);
    }

    public function test_booking_fails_if_portions_less_than_one(): void
    {
        $response = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => 0,
            'nama_penerima' => 'Andi',
            'nomor_telepon' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['jumlah_permintaan']);
    }

    public function test_booking_fails_if_portions_exceed_available(): void
    {
        $response = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => 11, // max is 10
            'nama_penerima' => 'Andi',
            'nomor_telepon' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['jumlah_permintaan']);
    }

    public function test_booking_fails_if_phone_does_not_start_with_08(): void
    {
        $response = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => 2,
            'nama_penerima' => 'Andi',
            'nomor_telepon' => '628123456789', // invalid prefix
        ]);

        $response->assertSessionHasErrors(['nomor_telepon']);
    }

    public function test_booking_fails_if_phone_number_length_is_invalid(): void
    {
        // 10 digits (too short)
        $responseShort = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => 2,
            'nama_penerima' => 'Andi',
            'nomor_telepon' => '0812345678', 
        ]);
        $responseShort->assertSessionHasErrors(['nomor_telepon']);

        // 14 digits (too long)
        $responseLong = $this->actingAs($this->user)->post("/donasi/{$this->donasi->id_donasi}/pesan", [
            'jumlah_permintaan' => 2,
            'nama_penerima' => 'Andi',
            'nomor_telepon' => '08123456789012', 
        ]);
        $responseLong->assertSessionHasErrors(['nomor_telepon']);
    }
}
