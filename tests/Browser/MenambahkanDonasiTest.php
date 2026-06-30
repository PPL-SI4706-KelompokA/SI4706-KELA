<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Carbon\Carbon;

class MenambahkanDonasiTest extends DuskTestCase
{
    // DatabaseMigrations DIHAPUS agar data test masuk ke database utama
    // dan tidak direset/dihapus setelah setiap test selesai.

    private $donatur;
    private $penerima;

    protected function setUp(): void
    {
        parent::setUp();

        // Gunakan firstOrCreate agar tidak error duplikat jika test dijalankan berulang kali
        $this->donatur = User::firstOrCreate(
            ['email' => 'donatur_test@email.com'],
            [
                'nama'               => 'Donatur Test',
                'password'           => bcrypt('12345678'),
                'role'               => 'Donatur',
                'status_verifikasi'  => 'Sudah Verifikasi',
                'foto_url'           => null,
                'no_telp'            => '08123456789',
                'alamat'             => 'Alamat Test Donatur',
            ]
        );
        $this->donatur->update(['password' => bcrypt('12345678'), 'role' => 'Donatur', 'status_verifikasi' => 'Sudah Verifikasi']);

        $this->penerima = User::firstOrCreate(
            ['email' => 'penerima_test@email.com'],
            [
                'nama'               => 'Penerima Test',
                'password'           => bcrypt('12345678'),
                'role'               => 'Penerima',
                'status_verifikasi'  => 'Sudah Verifikasi',
                'foto_url'           => null,
                'no_telp'            => '08123456789',
                'alamat'             => 'Alamat Test Penerima',
            ]
        );
        $this->penerima->update(['password' => bcrypt('12345678'), 'role' => 'Penerima', 'status_verifikasi' => 'Sudah Verifikasi']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test case 1 (Positive): Halaman form tambah donasi dapat diakses oleh Donatur yang sudah login
     */
    public function test_donatur_can_access_tambah_donasi_form(): void
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
                    ->visit('/donasi/tambah')
                    ->pause(1000)
                    ->assertPathIs('/donasi/tambah')
                    ->pause(1000)
                    ->assertSee('Nama Makanan')
                    ->pause(1000)
                    ->assertSee('Kategori Makanan')
                    ->pause(1000)
                    ->assertSee('Jumlah Porsi')
                    ->pause(1000)
                    ->assertSee('Kadaluarsa')
                    ->pause(1000)
                    ->assertSee('Lokasi Pengambilan')
                    ->pause(1000)
                    ->assertSee('Kirim Donasi');
        });
    }

    /**
     * Test case 2 (Positive): Donatur berhasil menambahkan donasi dengan data valid
     *
     * Data donasi yang berhasil ditambahkan akan TERSIMPAN di database utama dan tidak dihapus.
     * CATATAN: script() mengembalikan array — harus dipisah ke statement baru.
     */
    public function test_donatur_can_submit_valid_donasi(): void
    {
        $tanggalKadaluarsa = Carbon::now()->addDays(7)->format('Y-m-d');

        $this->browse(function (Browser $browser) use ($tanggalKadaluarsa) {
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
                    ->visit('/donasi/tambah')
                    ->pause(1000)
                    ->type('nama_makanan', 'Nasi Box Spesial')
                    ->pause(1000)
                    ->select('kategori', 'Makanan Berat')
                    ->pause(1000)
                    ->type('jumlah', '15')
                    ->pause(1000);

            // script() mengembalikan array — pisah ke statement baru
            $browser->script([
                "document.querySelector('input[name=\"tanggal_kadaluarsa\"]').value = '$tanggalKadaluarsa';"
            ]);

            $browser->pause(1000)
                    ->type('lokasi', 'Jl. Merdeka No. 1, Bandung')
                    ->pause(1000)
                    ->type('deskripsi', 'Nasi box ayam bakar dalam kondisi fresh.')
                    ->pause(1000)
                    ->press('Kirim Donasi')
                    ->pause(1000)
                    // Geocoding Nominatim bisa lambat, beri 30 detik untuk redirect
                    ->waitForLocation('/donasi', 30)
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    // Verifikasi donasi berhasil tersimpan dengan melihat nama di daftar
                    ->assertSee('Nasi Box Spesial');
        });
    }

    /**
     * Test case 3 (Negative): Guest mendapatkan error 500 saat akses form tambah donasi
     *
     * Komponen <x-navbar-icons> mencoba akses auth()->user()->foto_url pada null,
     * menyebabkan Internal Server Error saat user tidak login.
     */    public function test_guest_didirect_ke_home_saat_akses_tambah_donasi(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->pause(1000)
                    ->visit('/donasi/tambah')
                    ->pause(1000)
                    ->assertPathIs('/')
                    ->pause(1000)
                    ->assertSee('Anda harus login terlebih dahulu.');
        });
    }

    /**
     * Test case 4 (Negative): Submit form dengan nama makanan kosong ditolak validasi HTML5
     */
    public function test_submit_donasi_gagal_jika_nama_makanan_kosong(): void
    {
        $tanggalKadaluarsa = Carbon::now()->addDays(7)->format('Y-m-d');

        $this->browse(function (Browser $browser) use ($tanggalKadaluarsa) {
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
                    ->visit('/donasi/tambah')
                    ->pause(1000)
                    // Sengaja tidak mengisi nama_makanan
                    ->select('kategori', 'Cemilan / Snack')
                    ->pause(1000)
                    ->type('jumlah', '10')
                    ->pause(1000);

            // script() dipisah ke statement baru
            $browser->script([
                "document.querySelector('input[name=\"tanggal_kadaluarsa\"]').value = '$tanggalKadaluarsa';"
            ]);

            $browser->pause(1000)
                    ->type('lokasi', 'Jl. Sudirman No. 5, Bandung')
                    ->pause(1000)
                    ->press('Kirim Donasi')
                    ->pause(1000)
                    // HTML5 required validation menahan submit, halaman tetap di /donasi/tambah
                    ->assertPathIs('/donasi/tambah');
        });
    }

    /**
     * Test case 5 (Negative): Submit form dengan tanggal kadaluarsa kosong ditolak validasi HTML5
     */
    public function test_submit_donasi_gagal_jika_tanggal_kadaluarsa_kosong(): void
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
                    ->visit('/donasi/tambah')
                    ->pause(1000)
                    ->type('nama_makanan', 'Roti Gandum')
                    ->pause(1000)
                    ->select('kategori', 'Bahan Pokok')
                    ->pause(1000)
                    ->type('jumlah', '5')
                    ->pause(1000)
                    // Sengaja tidak mengisi tanggal_kadaluarsa
                    ->type('lokasi', 'Jl. Braga No. 10, Bandung')
                    ->pause(1000)
                    ->press('Kirim Donasi')
                    ->pause(1000)
                    // HTML5 required validation menahan submit, halaman tetap di /donasi/tambah
                    ->assertPathIs('/donasi/tambah');
        });
    }

    /**
     * Test case 6 (Negative): Penerima langsung diredirect saat mengakses halaman tambah donasi
     *
     * Controller memeriksa role SEBELUM GET/POST check, sehingga Penerima
     * langsung diredirect ke /donasi dengan flash error tanpa sempat melihat form.
     */
    public function test_penerima_tidak_dapat_menambahkan_donasi(): void
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
                    ->visit('/donasi/tambah')
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->pause(1000)
                    ->assertSee('Anda terdaftar sebagai Penerima dan tidak dapat menambahkan donasi.');
        });
    }
}
