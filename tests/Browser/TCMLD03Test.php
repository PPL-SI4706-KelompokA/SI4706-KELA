<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCMLD03Test extends DuskTestCase
{
    private function getOrCreatePenerima($email, $name)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Penerima',
                'no_telp' => '081234567892',
                'alamat' => 'Jl. Cihampelas No. 50, Bandung',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]);
        }
        return $user;
    }

    private function login(Browser $browser, $email, $password)
    {
        $browser->blank();
        $browser->driver->manage()->deleteAllCookies();
        
        $browser->visit('/login')
                ->pause(1000)
                ->type('input[type="email"]', $email)
                ->type('input[type="password"]', $password)
                ->press('button[type="submit"]')
                ->pause(1000);
    }

    public function test_data_donasi_berhasil_diperbarui()
    {
        $penerima = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');
        
        // Buat donatur
        $donatur = User::where('email', 'donatur_mld03@email.com')->first();
        if (!$donatur) {
            $donatur = User::create([
                'nama' => 'Donatur MLD03',
                'email' => 'donatur_mld03@email.com',
                'password' => bcrypt('password'),
                'role' => 'Donatur',
                'no_telp' => '081234567891',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Bandung',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]);
        }

        // Hapus donasi sate ayam lama jika ada
        Donasi::where('nama_makanan', 'Sate Ayam MLD03')->delete();

        // Buat lokasi
        $lokasi = Lokasi::first() ?: Lokasi::create([
            'alamat' => 'Jl. Dago No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        $this->browse(function (Browser $browser) use ($penerima, $donatur, $lokasi) {
            $this->login($browser, $penerima->email, 'password');

            // 1. Masuk ke halaman Daftar Donasi
            $browser->visit('/donasi')
                    ->pause(1000)
                    ->assertDontSee('Sate Ayam MLD03');

            // 2. Tambahkan donasi baru di sistem
            Donasi::create([
                'id_user' => $donatur->id_user,
                'id_lokasi' => $lokasi->id_lokasi,
                'nama_makanan' => 'Sate Ayam MLD03',
                'kategori' => 'Makanan',
                'jumlah' => 10,
                'tanggal_kadaluarsa' => '2026-12-31',
                'deskripsi' => 'Sate ayam bumbu kacang gurih',
                'status_donasi' => 'Available',
            ]);

            // 3. Refresh halaman
            $browser->refresh()
                    ->pause(1000)
                    // 4. Sistem menampilkan data donasi terbaru
                    ->assertSee('Sate Ayam MLD03');
        });
    }
}
