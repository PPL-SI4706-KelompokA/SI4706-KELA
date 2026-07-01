<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_redirects_to_login_if_not_authenticated(): void
    {
        $response = $this->get('/detailuser');

        $response->assertRedirect('/login');
    }

    public function test_profile_page_is_displayed_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => 'Donatur',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/detailuser');

        $response->assertOk();
        $response->assertSee($user->nama);
        $response->assertSee($user->email);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create([
            'nama' => 'User Lama',
            'email' => 'lama@example.com',
            'no_telp' => '08123456789',
            'alamat' => 'Alamat Lama',
            'role' => 'Donatur',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/profile/update', [
                'nama' => 'User Baru',
                'email' => 'baru@example.com',
                'no_telp' => '08987654321',
                'alamat' => 'Alamat Baru',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(); // should redirect back

        $user->refresh();

        $this->assertSame('User Baru', $user->nama);
        $this->assertSame('baru@example.com', $user->email);
        $this->assertSame('08987654321', $user->no_telp);
        $this->assertSame('Alamat Baru', $user->alamat);
    }

    public function test_email_must_be_unique_on_profile_update(): void
    {
        $user1 = User::factory()->create([
            'email' => 'user1@example.com',
        ]);
        $user2 = User::factory()->create([
            'email' => 'user2@example.com',
        ]);

        $response = $this
            ->actingAs($user1)
            ->post('/profile/update', [
                'nama' => 'User Satu',
                'email' => 'user2@example.com', // tries to take user2's email
                'no_telp' => '08123456789',
                'alamat' => 'Alamat',
            ]);

        $response->assertSessionHasErrors('email');
        
        $user1->refresh();
        $this->assertSame('user1@example.com', $user1->email); // unchanged
    }

    public function test_profile_picture_can_be_uploaded(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'Donatur',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('profile.jpg');

        $response = $this
            ->actingAs($user)
            ->post('/profile/update', [
                'nama' => 'Donatur Test',
                'email' => $user->email,
                'no_telp' => '08123456789',
                'alamat' => 'Alamat Donatur',
                'foto_profil' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->foto_profil);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->foto_profil);
    }

    public function test_profile_picture_upload_validation(): void
    {
        $user = User::factory()->create([
            'role' => 'Donatur',
        ]);

        // Test file too large (more than 2048 KB)
        $largeFile = \Illuminate\Http\UploadedFile::fake()->create('profile.jpg', 3000);
        $response = $this
            ->actingAs($user)
            ->post('/profile/update', [
                'nama' => 'Donatur Test',
                'email' => $user->email,
                'foto_profil' => $largeFile,
            ]);
        $response->assertSessionHasErrors('foto_profil');

        // Test invalid file format
        $invalidFile = \Illuminate\Http\UploadedFile::fake()->create('profile.pdf', 500);
        $response = $this
            ->actingAs($user)
            ->post('/profile/update', [
                'nama' => 'Donatur Test',
                'email' => $user->email,
                'foto_profil' => $invalidFile,
            ]);
        $response->assertSessionHasErrors('foto_profil');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $response->assertRedirect();
        $this->assertGuest();
    }
}
