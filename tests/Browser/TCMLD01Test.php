<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCMLD01Test extends DuskTestCase
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

    public function test_berhasil_melihat_daftar_donasi()
    {
        $penerima = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');
        
        // Buat donatur
        $donatur = User::where('email', 'donatur_mld01@email.com')->first();
        if (!$donatur) {
            $donatur = User::create([
                'nama' => 'Donatur MLD01',
                'email' => 'donatur_mld01@email.com',
                'password' => bcrypt('password'),
                'role' => 'Donatur',
                'no_telp' => '081234567891',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Bandung',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]);
        }

        // Hapus donasi lama untuk test ini
        Donasi::where('nama_makanan', 'Nasi Goreng MLD01')->delete();

        // Buat lokasi
        $lokasi = Lokasi::first() ?: Lokasi::create([
            'alamat' => 'Jl. Dago No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // Buat donasi tersedia
        Donasi::create([
            'id_user' => $donatur->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Goreng MLD01',
            'kategori' => 'Makanan',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-12-31',
            'deskripsi' => 'Nasi goreng lezat porsi jumbo',
            'status_donasi' => 'Available',
        ]);

        $this->browse(function (Browser $browser) use ($penerima) {
            $this->login($browser, $penerima->email, 'password');

            $browser->visit('/donasi')
                    ->pause(1000)
                    ->assertPathIs('/donasi')
                    ->assertSee('Nasi Goreng MLD01');
        });
    }
}
