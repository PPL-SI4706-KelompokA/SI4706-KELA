<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_KONFIRMASI_001_Test extends DuskTestCase
{
    public function test_tc_konfirmasi_001(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                    ->type('email', 'Jennie@gmail.com')
                    ->type('password', '12345678')
                    ->press('Masuk')
                    ->pause(3000);

        });
    }
}