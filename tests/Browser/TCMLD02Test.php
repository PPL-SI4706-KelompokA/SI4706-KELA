<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCMLD02Test extends DuskTestCase
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

    public function test_tidak_ada_donasi_tersedia()
    {
        $penerima = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');
        // Ubah status semua donasi agar tidak muncul (memicu kondisi kosong)
        Donasi::query()->update(['status_donasi' => 'Distributed']);

        $this->browse(function (Browser $browser) use ($penerima) {
            $this->login($browser, $penerima->email, 'password');

            $browser->visit('/donasi')
                    ->pause(1000)
                    ->assertSee('Maaf, belum ada donasi.');
        });
    }
}
