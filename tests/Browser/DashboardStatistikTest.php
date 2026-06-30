<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use App\Models\Setting;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Carbon\Carbon;

class DashboardStatistikTest extends DuskTestCase
{
    // DatabaseMigrations DIHAPUS agar data test masuk ke database utama
    // dan tidak direset/dihapus setelah setiap test selesai.

    private $admin;
    private $donatur;
    private $penerima;
    private $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed target bulanan setting default (idempotent, aman dijalankan berulang)
        Setting::set('target_bulanan', 1500);

        // Gunakan firstOrCreate agar tidak duplikat jika test dijalankan berulang kali
        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@email.com'],
            [
                'nama'              => 'Admin Test',
                'password'          => bcrypt('12345678'),
                'no_telp'           => '08000000001',
                'role'              => 'Admin',
                'status_verifikasi' => 'Sudah Verifikasi',
                'alamat'            => 'Alamat Test Admin',
            ]
        );
        $this->admin->update(['password' => bcrypt('12345678'), 'role' => 'Admin', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->donatur = User::firstOrCreate(
            ['email' => 'donatur_test@email.com'],
            [
                'nama'              => 'Donatur Test',
                'password'          => bcrypt('12345678'),
                'no_telp'           => '08000000002',
                'role'              => 'Donatur',
                'status_verifikasi' => 'Sudah Verifikasi',
                'alamat'            => 'Alamat Test Donatur',
            ]
        );
        $this->donatur->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->penerima = User::firstOrCreate(
            ['email' => 'penerima_test@email.com'],
            [
                'nama'              => 'Penerima Test',
                'password'          => bcrypt('12345678'),
                'no_telp'           => '08000000003',
                'role'              => 'Penerima',
                'status_verifikasi' => 'Sudah Verifikasi',
                'alamat'            => 'Alamat Test Penerima',
            ]
        );
        $this->penerima->update(['password' => bcrypt('12345678'), 'role' => 'Penerima', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->lokasi = Lokasi::firstOrCreate(
            ['alamat' => 'Jl. Kebon Sirih No. 10', 'kota' => 'Jakarta Pusat'],
            [
                'latitude'  => -6.1824,
                'longitude' => 106.8291,
            ]
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test case 1 (Positive): Admin dapat mengakses dashboard statistik dan melihat metrik
     */
    public function test_admin_can_access_statistics_dashboard_and_see_metrics(): void
    {
        // Buat donasi baru setiap kali test (data terakumulasi, tidak direset)
        Donasi::create([
            'id_user'            => $this->donatur->id_user,
            'id_lokasi'          => $this->lokasi->id_lokasi,
            'nama_makanan'       => 'Nasi Box Gurih',
            'kategori'           => 'Makanan Berat',
            'jumlah'             => 20,
            'tanggal_kadaluarsa' => now()->addDays(5)->format('Y-m-d'),
            'deskripsi'          => 'Deskripsi Makanan',
            'status_donasi'      => 'Available',
            'status_verifikasi'  => 'Sudah Verifikasi',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->admin->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik')
                    ->pause(1000)
                    ->assertSee('Statistik')
                    ->pause(1000)
                    ->assertSee('Donasi')
                    ->pause(1000)
                    ->assertSee('Total Donasi Terkumpul')
                    ->pause(1000)
                    ->assertSee('TARGET BULANAN', true);
        });
    }

    /**
     * Test case 2 (Positive): Admin dapat mengubah target bulanan via modal
     */
    public function test_admin_can_update_monthly_target_successfully(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->admin->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik')
                    ->pause(1000)
                    // Klik tombol edit target
                    ->script("document.querySelector('button[title=\"Ubah Target Bulanan\"]').click();");
            $browser->pause(1000)
                    ->waitFor('#targetModal')
                    ->pause(1000)
                    // Bersihkan input lalu isi nilai baru
                    ->script("document.getElementById('target_bulanan').value = '2500';");
            $browser->pause(1000)
                    ->script("document.querySelector('#targetModal form').submit();");
            $browser->pause(1000)
                    ->pause(1000)
                    ->waitForText('Target bulanan berhasil diperbarui!')
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik')
                    ->pause(1000)
                    ->assertSee('2.500');
        });
    }

    /**
     * Test case 3 (Positive): Admin dapat memfilter donatur teratas berdasarkan rentang tanggal
     */
    public function test_admin_can_filter_top_donators_by_date_range(): void
    {
        // Gunakan email unik agar setiap run test tidak duplikat
        $donorOld = User::firstOrCreate(
            ['email' => 'donatur_lama_dashboard@email.com'],
            ['nama' => 'Donatur Lama', 'password' => bcrypt('12345678'), 'no_telp' => '08000000004', 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi', 'alamat' => 'Alamat Donatur Lama']
        );
        $donorOld->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $donorNew = User::firstOrCreate(
            ['email' => 'donatur_baru_dashboard@email.com'],
            ['nama' => 'Donatur Baru', 'password' => bcrypt('12345678'), 'no_telp' => '08000000005', 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi', 'alamat' => 'Alamat Donatur Baru']
        );
        $donorNew->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        // Gunakan rentang tanggal di masa depan agar terisolasi dari data seeder
        $dateOld = Carbon::now()->addDays(15);
        $dateNew = Carbon::now()->addDays(20);

        // Buat donasi lama (15 hari ke depan)
        $donasiOld = Donasi::create([
            'id_user'            => $donorOld->id_user,
            'id_lokasi'          => $this->lokasi->id_lokasi,
            'nama_makanan'       => 'Makanan Lama',
            'kategori'           => 'Makanan Berat',
            'jumlah'             => 100,
            'tanggal_kadaluarsa' => now()->addDays(25)->format('Y-m-d'),
            'deskripsi'          => 'Old desc',
            'status_donasi'      => 'Available',
            'status_verifikasi'  => 'Sudah Verifikasi',
        ]);
        $donasiOld->created_at = $dateOld;
        $donasiOld->save();

        // Buat donasi baru (20 hari ke depan)
        $donasiNew = Donasi::create([
            'id_user'            => $donorNew->id_user,
            'id_lokasi'          => $this->lokasi->id_lokasi,
            'nama_makanan'       => 'Makanan Baru',
            'kategori'           => 'Makanan Berat',
            'jumlah'             => 50,
            'tanggal_kadaluarsa' => now()->addDays(25)->format('Y-m-d'),
            'deskripsi'          => 'New desc',
            'status_donasi'      => 'Available',
            'status_verifikasi'  => 'Sudah Verifikasi',
        ]);
        $donasiNew->created_at = $dateNew;
        $donasiNew->save();

        // Filter: 18 s/d 22 hari ke depan (hanya mencakup $donorNew)
        $startDate = Carbon::now()->addDays(18)->format('Y-m-d');
        $endDate   = Carbon::now()->addDays(22)->format('Y-m-d');

        $this->browse(function (Browser $browser) use ($startDate, $endDate) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->admin->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik')
                    ->pause(1000);

            $browser->script([
                "document.getElementById('start_date').value = '$startDate';",
                "document.getElementById('end_date').value = '$endDate';",
                "document.querySelector('form[action*=\"statistik\"]').submit();"
            ]);

            $browser->pause(1000)
                    ->waitForText('Periode:')
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik')
                    ->pause(1000)
                    ->assertSee('Donatur Baru')
                    ->pause(1000)
                    ->assertDontSee('Donatur Lama');
        });
    }

    /**
     * Test case 4 (Negative): Guest tidak dapat mengakses dashboard statistik
     */
    public function test_guest_cannot_access_statistics_dashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/admin/statistik')
                    ->pause(1000)
                    ->assertPathIs('/login');
        });
    }

    /**
     * Test case 5 (Negative): Non-admin tidak dapat mengakses dashboard statistik
     */
    public function test_non_admin_user_cannot_access_statistics_dashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->penerima->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->visit('/admin/statistik')
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->assertSee('Hanya Admin yang dapat mengakses halaman ini.');
        });
    }

    /**
     * Test case 6 (Negative): Admin tidak dapat menyimpan target bulanan dengan nilai tidak valid
     */
    public function test_admin_cannot_update_monthly_target_with_invalid_data(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->admin->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik')
                    ->pause(1000)
                    ->script("document.querySelector('button[title=\"Ubah Target Bulanan\"]').click();");
            $browser->pause(1000)
                    ->waitFor('#targetModal')
                    ->pause(1000)
                    ->script("document.getElementById('target_bulanan').value = '-50';");
            $browser->pause(1000)
                    ->click('#targetModal button[type="submit"]')
                    ->pause(1000)
                    ->assertPathIs('/admin/statistik');
        });
    }
}
