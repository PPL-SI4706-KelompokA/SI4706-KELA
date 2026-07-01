<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCRD04Test extends DuskTestCase
{
    private function getOrCreateDonaturNoHistory()
    {
        $email = 'bambang@email.com';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => 'Bambang Hermawan',
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Donatur',
                'no_telp' => '081234567891',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
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

    public function test_tidak_ada_riwayat_donasi()
    {
        $donaturNoHistory = $this->getOrCreateDonaturNoHistory();
        Donasi::where('id_user', $donaturNoHistory->id_user)->delete();

        $this->browse(function (Browser $browser) use ($donaturNoHistory) {
            $this->login($browser, $donaturNoHistory->email, 'password');

            $browser->visit('/donasi/riwayat')
                    ->pause(1000)
                    ->assertSee('Belum ada riwayat donasi dari Anda.');
        });
    }
}
