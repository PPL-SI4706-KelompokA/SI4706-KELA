<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_ban_user_permanently(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $user = User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $response = $this->actingAs($admin)->post("/admin/manajemen/{$user->id_user}/ban", [
            'banned_status' => 'permanent',
            'banned_reason' => 'Spamming content',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('permanent', $user->banned_status);
        $this->assertEquals('Spamming content', $user->banned_reason);
        $this->assertNull($user->banned_until);
    }

    public function test_admin_can_ban_user_temporarily(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $user = User::factory()->create(['role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);
        $bannedUntil = now()->addDays(5)->format('Y-m-d H:i:s');

        $response = $this->actingAs($admin)->post("/admin/manajemen/{$user->id_user}/ban", [
            'banned_status' => 'temporary',
            'banned_reason' => 'Abusive language',
            'banned_until'  => $bannedUntil,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('temporary', $user->banned_status);
        $this->assertEquals('Abusive language', $user->banned_reason);
        $this->assertNotNull($user->banned_until);
    }

    public function test_banned_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'role' => 'Donatur',
            'status_verifikasi' => 'Sudah Verifikasi',
            'banned_status' => 'permanent',
            'banned_reason' => 'Violations',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_expired_temporary_ban_allows_login(): void
    {
        $user = User::factory()->create([
            'role' => 'Donatur',
            'status_verifikasi' => 'Sudah Verifikasi',
            'banned_status' => 'temporary',
            'banned_reason' => 'Time out',
            'banned_until' => now()->subMinutes(1), // already expired
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/donasi');
        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $this->assertEquals('not_banned', $user->banned_status);
        $this->assertNull($user->banned_reason);
        $this->assertNull($user->banned_until);
    }

    public function test_admin_can_unban_user(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $user = User::factory()->create([
            'role' => 'Donatur',
            'status_verifikasi' => 'Sudah Verifikasi',
            'banned_status' => 'permanent',
            'banned_reason' => 'Spam',
        ]);

        $response = $this->actingAs($admin)->post("/admin/manajemen/{$user->id_user}/unban");

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('not_banned', $user->banned_status);
        $this->assertNull($user->banned_reason);
        $this->assertNull($user->banned_until);
    }
}
