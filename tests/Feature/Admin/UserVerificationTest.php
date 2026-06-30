<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_verification_queue_list(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $donatur = User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Belum Verifikasi']);
        $penerima = User::factory()->create(['role' => 'Penerima', 'status_verifikasi' => 'Belum Verifikasi']);

        $response = $this->actingAs($admin)->get('/admin/verifikasi');

        $response->assertStatus(200);
        $response->assertSee($donatur->nama);
        $response->assertSee($penerima->nama);
    }

    public function test_admin_can_approve_donatur_verification(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $donatur = User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Belum Verifikasi']);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$donatur->id_user}/setuju");

        $response->assertRedirect('/admin/verifikasi');
        $donatur->refresh();
        $this->assertEquals('Sudah Verifikasi', $donatur->status_verifikasi);

        // Check notification created
        $this->assertDatabaseHas('notifikasis', [
            'id_user' => $donatur->id_user,
            'tipe_notifikasi' => 'Permintaan Disetujui',
        ]);
    }

    public function test_admin_can_approve_penerima_verification(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $penerima = User::factory()->create(['role' => 'Penerima', 'status_verifikasi' => 'Belum Verifikasi']);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$penerima->id_user}/setuju");

        $response->assertRedirect('/admin/verifikasi');
        $penerima->refresh();
        $this->assertEquals('Sudah Verifikasi', $penerima->status_verifikasi);

        // Check notification created
        $this->assertDatabaseHas('notifikasis', [
            'id_user' => $penerima->id_user,
            'tipe_notifikasi' => 'Permintaan Disetujui',
        ]);
    }

    public function test_admin_can_reject_verification(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $donatur = User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Belum Verifikasi']);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$donatur->id_user}/tolak");

        $response->assertRedirect('/admin/verifikasi');
        $donatur->refresh();
        $this->assertEquals('Ditolak', $donatur->status_verifikasi);

        // Check notification created
        $this->assertDatabaseHas('notifikasis', [
            'id_user' => $donatur->id_user,
            'tipe_notifikasi' => 'Permintaan Ditolak',
        ]);
    }
}
