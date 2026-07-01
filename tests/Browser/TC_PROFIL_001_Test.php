<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PROFIL_001_Test extends DuskTestCase
{
    public function test_tc_profil_001(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                    ->pause(2000)

                    ->type('email', 'Lisa@gmail.com')
                    ->pause(1000)

                    ->type('password', '12345678')
                    ->pause(1000)

                    ->press('Masuk')
                    ->pause(3000)

                    ->visit('/detailuser')
                    ->pause(3000)

                    ->assertSee('Informasi Akun')
                    ->assertSee('Nama Lengkap')
                    ->assertSee('Alamat Email')
                    ->pause(5000)

                    ->screenshot('tc-profil-001-passed');
        });
    }
}