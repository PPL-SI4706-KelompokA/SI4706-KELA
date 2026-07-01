<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PROFIL_004_Test extends DuskTestCase
{
    public function test_tc_profil_004(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                    ->type('email', 'Lisa@gmail.com')
                    ->type('password', '12345678')
                    ->press('Masuk')
                    ->pause(3000)

                    ->visit('/detailuser')
                    ->pause(3000)

                    ->press('✏️ Edit Profil')
                    ->pause(2000)

                    ->type('email', 'abc123')
                    ->press('Simpan Perubahan')
                    ->pause(2000)

                    ->screenshot('tc-profil-004-validation');
        });
    }
}