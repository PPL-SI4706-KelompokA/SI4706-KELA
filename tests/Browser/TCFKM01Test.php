<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCFKM01Test extends DuskTestCase
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

    public function test_berhasil_memfilter_donasi_berdasarkan_kategori()
    {
        $penerima = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');
        
        // Buat donatur
        $donatur = User::where('email', 'donatur_fkm01@email.com')->first();
        if (!$donatur) {
            $donatur = User::create([
                'nama' => 'Donatur FKM01',
                'email' => 'donatur_fkm01@email.com',
                'password' => bcrypt('password'),
                'role' => 'Donatur',
                'no_telp' => '081234567891',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Bandung',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]);
        }

        // Hapus data donasi lama
        Donasi::where('nama_makanan', 'Nasi Goreng FKM01')->delete();
        Donasi::where('nama_makanan', 'Es Teh FKM01')->delete();

        // Buat lokasi
        $lokasi = Lokasi::first() ?: Lokasi::create([
            'alamat' => 'Jl. Dago No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // Buat makanan
        Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Goreng FKM01',
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-12-31',
            'deskripsi' => 'Nasi goreng lezat porsi jumbo',
            'status_donasi' => 'Available',
        ]);

        // Buat minuman
        Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Es Teh FKM01',
            'kategori' => 'Minuman',
            'jumlah' => 10,
            'tanggal_kadaluarsa' => '2026-12-31',
            'deskripsi' => 'Es teh manis segar',
            'status_donasi' => 'Available',
        ]);

        $this->browse(function (Browser $browser) use ($penerima) {
            $this->login($browser, $penerima->email, 'password');

            // 1. Masuk ke halaman Daftar Donasi
            $browser->visit('/donasi')
                    ->pause(1000)
                    ->assertSee('Nasi Goreng FKM01')
                    ->assertSee('Es Teh FKM01');

            // 2. Pilih kategori Makanan
            $browser->clickLink('Makanan')
                    ->pause(1000)
                    // 3. Pastikan memfilter data dengan benar
                    ->assertSee('Nasi Goreng FKM01')
                    ->assertDontSee('Es Teh FKM01');
        });
    }
}
