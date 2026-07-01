<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Donasi;
use App\Models\permintaan;
use App\Models\rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user_and_all_associated_data_cascades_safely(): void
    {
        // 1. Create Admin
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Create Donatur and Receiver
        $donatur = User::factory()->create(['role' => 'Donatur']);
        $receiver = User::factory()->create(['role' => 'Penerima']);

        // 3. Create Location
        $lokasi = Lokasi::create([
            'alamat' => 'Jl. Test No. 123',
            'kota' => 'Jakarta',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        // 4. Create Donasi
        $donasi = Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Goreng',
            'kategori' => 'Makanan Berat',
            'jumlah' => 10,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi goreng lezat',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        // 5. Create Permintaan
        $permintaan = permintaan::create([
            'id_user' => $receiver->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 2,
            'catatan' => 'Minta sendok',
            'status' => 'Pending',
        ]);

        // 6. Create Riwayat Donasi (insert directly into DB table to avoid mass-assignment guard constraints)
        $idRiwayat = \Illuminate\Support\Facades\DB::table('riwayat_donasis')->insertGetId([
            'id_donasi' => $donasi->id_donasi,
            'id_permintaan' => $permintaan->id_permintaan,
            'id_user' => $receiver->id_user,
            'status_pengambilan' => 'Pending',
            'tanggal_pembelian' => '2026-06-08',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Create Rating
        $rating = rating::create([
            'id_user' => $receiver->id_user,
            'id_permintaan' => $permintaan->id_permintaan,
            'nilai_rating' => 5,
            'komentar' => 'Sangat baik',
        ]);

        // Verify records exist in database
        $this->assertDatabaseHas('users', ['id_user' => $donatur->id_user]);
        $this->assertDatabaseHas('donasis', ['id_donasi' => $donasi->id_donasi]);
        $this->assertDatabaseHas('permintaans', ['id_permintaan' => $permintaan->id_permintaan]);
        $this->assertDatabaseHas('riwayat_donasis', ['id_riwayat' => $idRiwayat]);
        $this->assertDatabaseHas('ratings', ['id_rating' => $rating->id_rating]);

        // 8. Delete Donatur user as Admin
        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.manajemen.destroy', $donatur->id_user));

        $response->assertRedirect(route('admin.manajemen'));
        $response->assertSessionHas('success', 'Pengguna berhasil dihapus.');

        // Verify data cleanup
        $this->assertDatabaseMissing('users', ['id_user' => $donatur->id_user]);
        $this->assertDatabaseMissing('donasis', ['id_donasi' => $donasi->id_donasi]);
        $this->assertDatabaseMissing('permintaans', ['id_permintaan' => $permintaan->id_permintaan]);
        $this->assertDatabaseMissing('riwayat_donasis', ['id_riwayat' => $idRiwayat]);
        $this->assertDatabaseMissing('ratings', ['id_rating' => $rating->id_rating]);
    }

    public function test_admin_cannot_delete_themselves_lockout_protection(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.manajemen.destroy', $admin->id_user));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

        $this->assertDatabaseHas('users', ['id_user' => $admin->id_user]);
    }
}
