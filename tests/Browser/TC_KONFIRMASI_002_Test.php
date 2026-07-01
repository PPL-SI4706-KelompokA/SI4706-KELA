<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_KONFIRMASI_002_Test extends DuskTestCase
{
    public function test_tc_konfirmasi_002(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                    ->type('email', 'Jennie@gmail.com')
                    ->type('password', '12345678')
                    ->press('Masuk')
                    ->pause(3000)

                    ->visit('/permintaan/11')
                    ->pause(3000)

                    ->press('Tolak')
                    ->pause(3000)

                    ->assertSee('Ditolak')

                    ->screenshot('tc-konfirmasi-002-passed');
        });
    }
}