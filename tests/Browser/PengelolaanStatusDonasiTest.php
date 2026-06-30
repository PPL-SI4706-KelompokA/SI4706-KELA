<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PengelolaanStatusDonasiTest extends DuskTestCase
{
    // DatabaseMigrations DIHAPUS agar data test masuk ke database utama
    // dan tidak direset/dihapus setelah setiap test selesai.

    private $donatur;
    private $donaturLain;
    private $penerima;
    private $admin;
    private $lokasi;
    private $donasi;

    protected function setUp(): void
    {
        parent::setUp();

        // Gunakan firstOrCreate untuk lokasi agar tidak duplikat
        $this->lokasi = Lokasi::firstOrCreate(
            ['alamat' => 'Jl. Kebon Sirih No. 10', 'kota' => 'Jakarta Pusat'],
            ['latitude' => -6.1824, 'longitude' => 106.8291]
        );

        // Gunakan firstOrCreate untuk semua user agar tidak duplikat
        $this->donatur = User::firstOrCreate(
            ['email' => 'donatur_test@email.com'],
            [
                'nama'              => 'Donatur Test',
                'password'          => bcrypt('12345678'),
                'role'              => 'Donatur',
                'status_verifikasi' => 'Sudah Verifikasi',
                'no_telp'           => '08123456789',
                'alamat'            => 'Alamat Test Donatur',
            ]
        );
        $this->donatur->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->donaturLain = User::firstOrCreate(
            ['email' => 'donatur_lain_kelola@email.com'],
            [
                'nama'              => 'Donatur Lain',
                'password'          => bcrypt('12345678'),
                'role'              => 'Donatur',
                'status_verifikasi' => 'Sudah Verifikasi',
                'no_telp'           => '08123456789',
                'alamat'            => 'Alamat Test Donatur Lain',
            ]
        );
        $this->donaturLain->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->penerima = User::firstOrCreate(
            ['email' => 'penerima_test@email.com'],
            [
                'nama'              => 'Penerima Test',
                'password'          => bcrypt('12345678'),
                'role'              => 'Penerima',
                'status_verifikasi' => 'Sudah Verifikasi',
                'no_telp'           => '08123456789',
                'alamat'            => 'Alamat Test Penerima',
            ]
        );
        $this->penerima->update(['password' => bcrypt('12345678'), 'role' => 'Penerima', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@email.com'],
            [
                'nama'              => 'Admin Test',
                'password'          => bcrypt('12345678'),
                'role'              => 'Admin',
                'status_verifikasi' => 'Sudah Verifikasi',
                'no_telp'           => '08123456789',
                'alamat'            => 'Alamat Test Admin',
            ]
        );
        $this->admin->update(['password' => bcrypt('12345678'), 'role' => 'Admin', 'status_verifikasi' => 'Sudah Verifikasi']);

        // Selalu buat donasi baru setiap test dijalankan
        // (data lama tetap ada di DB, tidak dihapus)
        $this->donasi = Donasi::create([
            'id_user'            => $this->donatur->id_user,
            'id_lokasi'          => $this->lokasi->id_lokasi,
            'nama_makanan'       => 'Nasi Goreng Spesial',
            'kategori'           => 'Makanan Berat',
            'jumlah'             => 10,
            'tanggal_kadaluarsa' => now()->addDays(3)->format('Y-m-d'),
            'deskripsi'          => 'Nasi goreng buatan sendiri.',
            'status_donasi'      => 'Available',
            'status_verifikasi'  => 'Sudah Verifikasi',
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test case 1 (Positive): Donatur dapat melihat daftar donasi miliknya di halaman kelola
     */
    public function test_donatur_dapat_melihat_daftar_donasinya(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->donatur->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->visit('/donasi/kelola')
                    ->pause(1000)
                    ->assertPathIs('/donasi/kelola')
                    ->pause(1000)
                    ->assertSee('Nasi Goreng Spesial')
                    ->pause(1000)
                    ->assertSee('10 Porsi')
                    ->pause(1000)
                    ->assertSee('Ubah Status');
        });
    }

    /**
     * Test case 2 (Positive): Donatur berhasil mengubah status donasi miliknya menjadi 'Dipesan'
     */
    public function test_donatur_berhasil_mengubah_status_donasi_menjadi_dipesan(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->donatur->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->visit('/donasi/kelola')
                    ->pause(1000)
                    ->assertSee('Nasi Goreng Spesial')
                    ->pause(1000);
            $browser->script([
                "document.getElementById('statusForm').action = '" . route('donasi.update-status', $this->donasi->id_donasi) . "';",
                "document.getElementById('selectedStatus').value = 'Dipesan';",
                "document.getElementById('statusForm').submit();"
            ]);
            $browser->pause(2000)
                    // Tunggu halaman reload setelah PATCH berhasil
                    ->waitForLocation('/donasi/kelola')
                    ->pause(1000)
                    ->assertPathIs('/donasi/kelola')
                    ->pause(1000)
                    // Badge status berisi 'Dipesan' di DB, tapi CSS 'uppercase' membuat getText() = 'DIPESAN'
                    // Gunakan ignoreCase=true agar tidak terpengaruh CSS text-transform
                    ->assertSee('Dipesan', true);
        });
    }

    /**
     * Test case 3 (Positive): Admin dapat melihat seluruh donasi dari semua donatur
     */
    public function test_admin_dapat_melihat_semua_donasi(): void
    {
        // Buat donasi tambahan milik donaturLain
        Donasi::create([
            'id_user'            => $this->donaturLain->id_user,
            'id_lokasi'          => $this->lokasi->id_lokasi,
            'nama_makanan'       => 'Bakso Malang',
            'kategori'           => 'Makanan Berat',
            'jumlah'             => 20,
            'tanggal_kadaluarsa' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi'          => 'Bakso segar dari supplier.',
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
                    ->visit('/donasi/kelola')
                    ->pause(1000)
                    ->assertPathIs('/donasi/kelola')
                    ->pause(1000)
                    // Admin melihat semua donasi dari semua donatur
                    ->assertSee('Nasi Goreng Spesial')
                    ->pause(1000)
                    ->assertSee('Bakso Malang');
        });
    }

    /**
     * Test case 4 (Negative): Donatur lain (donatur_lain_kelola) tidak memiliki donasi
     * sehingga melihat pesan kosong dan tidak melihat donasi milik donatur lain
     */
    public function test_donatur_lain_tidak_melihat_donasi_orang_lain(): void
    {
        $donaturTanpaDonasi = User::firstOrCreate(
            ['email' => 'donatur_tanpa_donasi_kelola@email.com'],
            [
                'nama'              => 'Donatur Tanpa Donasi',
                'password'          => bcrypt('12345678'),
                'role'              => 'Donatur',
                'status_verifikasi' => 'Sudah Verifikasi',
                'no_telp'           => '08123456780',
                'alamat'            => 'Alamat Test Tanpa Donasi',
            ]
        );
        $donaturTanpaDonasi->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->browse(function (Browser $browser) use ($donaturTanpaDonasi) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $donaturTanpaDonasi->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->visit('/donasi/kelola')
                    ->pause(1000)
                    ->assertPathIs('/donasi/kelola')
                    ->pause(1000)
                    // donaturTanpaDonasi tidak punya donasi — tampil pesan kosong
                    ->assertSee('Belum ada donasi yang Anda kelola.')
                    ->pause(1000)
                    // Donasi milik donatur utama tidak muncul
                    ->assertDontSee('Nasi Goreng Spesial');
        });
    }

    /**
     * Test case 5 (Negative): Guest melihat halaman kelola dalam kondisi kosong
     *
     * Route /donasi/kelola tidak punya middleware 'auth', sehingga guest tidak diredirect.
     * Controller query dengan auth()->id() = null → hasil kosong.
     */
    public function test_guest_melihat_halaman_kelola_dengan_kondisi_kosong(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/donasi/kelola')
                    ->pause(1000)
                    ->assertPathIs('/donasi/kelola')
                    ->pause(1000)
                    ->assertSee('Belum ada donasi yang Anda kelola.')
                    ->pause(1000)
                    ->assertDontSee('Nasi Goreng Spesial');
        });
    }

    /**
     * Test case 6 (Positive): Donatur berhasil mengubah status donasi menjadi 'Selesai'
     */
    public function test_donatur_berhasil_mengubah_status_donasi_menjadi_selesai(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/login?t=' . uniqid())
                    ->pause(1000);
            $browser->script([
                "document.querySelector('input[name=\"email\"]').value = '" . $this->donatur->email . "';",
                "document.querySelector('input[name=\"password\"]').value = '12345678';",
                "document.querySelector('form').submit();"
            ]);
            $browser->pause(2000)
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->visit('/donasi/kelola')
                    ->pause(1000)
                    ->assertSee('Nasi Goreng Spesial')
                    ->pause(1000);
            $browser->script([
                "document.getElementById('statusForm').action = '" . route('donasi.update-status', $this->donasi->id_donasi) . "';",
                "document.getElementById('selectedStatus').value = 'Selesai';",
                "document.getElementById('statusForm').submit();"
            ]);
            $browser->pause(2000)
                    // Tunggu halaman reload setelah PATCH berhasil
                    ->waitForLocation('/donasi/kelola')
                    ->pause(1000)
                    ->assertPathIs('/donasi/kelola')
                    ->pause(1000)
                    // Badge status berisi 'Selesai' di DB, tapi CSS 'uppercase' membuat getText() = 'SELESAI'
                    // Gunakan ignoreCase=true agar tidak terpengaruh CSS text-transform
                    ->assertSee('Selesai', true);
        });
    }
}
