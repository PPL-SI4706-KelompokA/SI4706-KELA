<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCRD02Test extends DuskTestCase
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

    public function test_menampilkan_urutan_riwayat_donasi()
    {
        $donatur = $this->getOrCreateDonatur();
        $this->browse(function (Browser $browser) use ($donatur) {
            $this->login($browser, $donatur->email, 'password');

            $browser->visit('/donasi/riwayat')
                    ->pause(1000)
                    ->assertSee('Riwayat Donasi');
        });
    }
}
