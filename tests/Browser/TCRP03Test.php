<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\permintaan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCRP03Test extends DuskTestCase
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

    public function test_belum_ada_riwayat_penerimaan()
    {
        $penerimaB = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');
        permintaan::where('id_user', $penerimaB->id_user)->delete();

        $this->browse(function (Browser $browser) use ($penerimaB) {
            $this->login($browser, $penerimaB->email, 'password');

            $browser->visit('/penerima/riwayat')
                    ->pause(1000)
                    ->assertSee('Belum ada riwayat penerimaan.');
        });
    }
}
