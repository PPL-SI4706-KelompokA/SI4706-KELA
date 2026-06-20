<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCRD05Test extends DuskTestCase
{
    private function getOrCreateDonatur()
    {
        $email = 'dewi@email.com';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => 'Dewi Lestari',
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Donatur',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Dago No. 12, Bandung',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]);
        }
        return $user;
    }

    private function prepareDataForDonatur($user)
    {
        Donasi::where('id_user', $user->id_user)->delete();

        $lokasi = Lokasi::first() ?: Lokasi::create([
            'alamat' => 'Jl. Dago No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        // Tersedia
        Donasi::create([
            'id_user' => $user->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Kotak Ayam Bakar ' . time(),
            'kategori' => 'Makanan Ringan',
            'jumlah' => 10,
            'tanggal_kadaluarsa' => '2026-12-31',
            'deskripsi' => 'Nasi kotak ayam bakar dengan lauk lengkap dan higienis',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
            'created_at' => now()->subHours(2),
        ]);

        // Habis
        Donasi::create([
            'id_user' => $user->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Susu UHT Ultra Milk ' . time(),
            'kategori' => 'Makanan Berat',
            'jumlah' => 0,
            'tanggal_kadaluarsa' => '2026-12-31',
            'deskripsi' => 'Susu UHT siap minum rasa cokelat',
            'status_donasi' => 'Distributed',
            'status_verifikasi' => 'Disetujui',
            'created_at' => now()->subHours(1),
        ]);
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

    public function test_memfilter_riwayat_donasi_berdasarkan_status()
    {
        $donatur = $this->getOrCreateDonatur();
        $this->prepareDataForDonatur($donatur);

        $this->browse(function (Browser $browser) use ($donatur) {
            $this->login($browser, $donatur->email, 'password');

            $browser->visit('/donasi/riwayat')
                    ->pause(1000)
                    ->clickLink('Tersedia')
                    ->pause(1000)
                    ->assertSeeIn('.space-y-6', 'Tersedia')
                    ->assertDontSeeIn('.space-y-6', 'Habis')
                    ->clickLink('Habis')
                    ->pause(1000)
                    ->assertSeeIn('.space-y-6', 'Habis')
                    ->assertDontSeeIn('.space-y-6', 'Tersedia');
        });
    }
}
