<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCLDM04Test extends DuskTestCase
{
    private function getOrCreateAdmin()
    {
        $email = 'admin@email.com';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => 'Andi Pratama',
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'no_telp' => '081234567890',
                'alamat' => 'Kantor Pusat FoodShare',
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

    public function test_tombol_unduh_pdf_tersedia()
    {
        $admin = $this->getOrCreateAdmin();
        $this->browse(function (Browser $browser) use ($admin) {
            $this->login($browser, $admin->email, 'password');

            $browser->visit('/admin/laporan')
                    ->pause(1000)
                    ->assertSee('Unduh PDF');
        });
    }
}
