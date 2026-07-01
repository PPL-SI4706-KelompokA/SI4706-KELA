<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Donasi;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonasiDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_detail_displays_correct_donor_name_and_location(): void
    {
        // 1. Create a donor user
        $donor = User::factory()->create([
            'nama' => 'Joko Susilo',
            'role' => 'Donatur',
        ]);

        // 2. Create a location
        $lokasi = Lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // 3. Create a donation
        $donasi = Donasi::create([
            'id_user' => $donor->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Ayam Geprek',
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi ayam geprek pedas sedang',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        // 4. Create a receiver user to view the detail page
        $receiver = User::factory()->create([
            'nama' => 'Budi Receiver',
            'role' => 'Penerima',
        ]);

        // 5. Access the donation detail page (route: donasi.pesan.form)
        $response = $this
            ->actingAs($receiver)
            ->get(route('donasi.pesan.form', $donasi->id_donasi));

        // 6. Assertions
        $response->assertOk();
        // The donor name "Joko Susilo" should be visible
        $response->assertSee('Joko Susilo');
        // The location address should be visible
        $response->assertSee('Jl. Merdeka No. 10, Bandung');
        // It shouldn't hardcode Budi Santoso anymore
        $response->assertDontSee('Budi Santoso');
    }

    public function test_notification_redirects_to_request_confirmation(): void
    {
        // 1. Create a donor user
        $donor = User::factory()->create([
            'role' => 'Donatur',
        ]);

        // 2. Create a location
        $lokasi = Lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // 3. Create a donation
        $donasi = Donasi::create([
            'id_user' => $donor->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Ayam Geprek',
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi ayam geprek',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        // 4. Create a receiver user
        $receiver = User::factory()->create([
            'role' => 'Penerima',
        ]);

        // 5. Create a request (permintaan)
        $permintaan = \App\Models\permintaan::create([
            'id_user' => $receiver->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 2,
            'catatan' => 'Minta porsi extra sambal',
            'status' => 'Pending',
        ]);

        // 6. Create a notification for the donor
        $notification = Notifikasi::create([
            'id_user' => $donor->id_user,
            'id_permintaan' => $permintaan->id_permintaan,
            'pesan' => 'Ada permintaan baru untuk donasi Anda.',
            'tanggal_notifikasi' => now()->toDateString(),
            'status_baca' => 0,
            'tipe_notifikasi' => 'Permintaan Baru',
        ]);

        // 7. Call redirect route as donor
        $response = $this
            ->actingAs($donor)
            ->get("/notifikasi/{$notification->id_notifikasi}/redirect");

        // 8. Assertions
        $response->assertRedirect(route('permintaan.show', $permintaan->id_permintaan));
        $notification->refresh();
        $this->assertEquals(1, $notification->status_baca);
    }

    public function test_request_confirmation_displays_dynamic_recipient_details(): void
    {
        // 1. Create a donor user
        $donor = User::factory()->create([
            'role' => 'Donatur',
        ]);

        // 2. Create a location
        $lokasi = Lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // 3. Create a donation
        $donasi = Donasi::create([
            'id_user' => $donor->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Ayam Geprek',
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi ayam geprek',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        // 4. Create a receiver user with specific phone
        $receiver = User::factory()->create([
            'nama' => 'Joko Penerima',
            'role' => 'Penerima',
            'no_telp' => '089999999999',
        ]);

        // 5. Create a request (permintaan)
        $permintaan = \App\Models\permintaan::create([
            'id_user' => $receiver->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 2,
            'catatan' => 'Minta sendok',
            'status' => 'Pending',
        ]);

        // 6. Access request confirmation as donor
        $response = $this
            ->actingAs($donor)
            ->get(route('permintaan.show', $permintaan->id_permintaan));

        // 7. Assertions
        $response->assertOk();
        $response->assertSee('089999999999');
        $response->assertSee('Joko Penerima');
        $response->assertDontSee('0829213883'); // Should not see hardcoded phone number
    }

    public function test_rating_form_defaults_to_zero_stars(): void
    {
        // 1. Create a donor user
        $donor = User::factory()->create([
            'role' => 'Donatur',
        ]);

        // 2. Create a location
        $lokasi = Lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // 3. Create a donation
        $donasi = Donasi::create([
            'id_user' => $donor->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Ayam Geprek',
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi ayam geprek',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        // 4. Create a receiver user
        $receiver = User::factory()->create([
            'role' => 'Penerima',
        ]);

        // 5. Access rating form page
        $response = $this
            ->actingAs($receiver)
            ->get(route('rating.create', $donasi->id_donasi));

        // 6. Assert that it has input name="rating" with value "0" and text "Pilih Rating"
        $response->assertOk();
        $response->assertSee('value="0"', false);
        $response->assertSee('Pilih Rating');
    }

    public function test_rating_validation_prevents_zero_stars(): void
    {
        $donor = User::factory()->create(['role' => 'Donatur']);
        $lokasi = Lokasi::create([
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);
        $donasi = Donasi::create([
            'id_user' => $donor->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Ayam Geprek',
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-06-15',
            'deskripsi' => 'Nasi ayam geprek',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        $receiver = User::factory()->create(['role' => 'Penerima']);

        $permintaan = \App\Models\permintaan::create([
            'id_user' => $receiver->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 1,
            'catatan' => 'Test',
            'status' => 'Pending',
        ]);

        // Submit form with rating = 0
        $response = $this
            ->actingAs($receiver)
            ->post(route('rating.store', $donasi->id_donasi), [
                'rating' => 0,
                'review' => 'Jelek',
                'id_permintaan' => $permintaan->id_permintaan,
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_history_icon_active_inactive_states(): void
    {
        $user = User::factory()->create([
            'role' => 'Penerima',
        ]);

        // 1. Visit another page (like profile /detailuser)
        $response = $this
            ->actingAs($user)
            ->get('/detailuser');

        $response->assertOk();
        // The history icon should be gray, not yellow
        $response->assertSee('class="transition-colors text-gray-500 hover:text-[#5B5C35]"', false);
        $response->assertDontSee('class="transition-colors text-[#FCD34D]"', false);

        // 2. Visit the history page (riwayat penerimaan)
        $response = $this
            ->actingAs($user)
            ->get(route('penerima.riwayatpenerimaan'));

        $response->assertOk();
        // The history icon should be active yellow
        $response->assertSee('class="transition-colors text-[#FCD34D]"', false);
        $response->assertDontSee('class="transition-colors text-gray-500 hover:text-[#5B5C35]"', false);
    }

    public function test_admin_layout_changes_and_export(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // 1. Visit admin reports page
        $response = $this
            ->actingAs($admin)
            ->get(route('admin.laporan'));

        $response->assertOk();

        // Check sidebar cleanups: shouldn't see Export Data button, Support, Sign Out
        $response->assertDontSee('Export Data');
        $response->assertDontSee('Support');
        $response->assertDontSee('Sign Out');

        // Check topbar cleanups: shouldn't see Dashboard, Alerts, Settings links
        $response->assertDontSee('Dashboard');
        $response->assertDontSee('Alerts');
        $response->assertDontSee('Settings');

        // Check that the download button is linked to export route
        $response->assertSee(route('admin.laporan.export'));

        // 2. Trigger CSV Export
        $exportResponse = $this
            ->actingAs($admin)
            ->get(route('admin.laporan.export'));

        $exportResponse->assertOk();
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
