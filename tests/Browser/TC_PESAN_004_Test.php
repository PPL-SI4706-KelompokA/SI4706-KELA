<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PESAN_004_Test extends DuskTestCase
{
    public function test_tc_pesan_004(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                    ->pause(2000)

                    ->type('email', 'Lisa@gmail.com')
                    ->pause(1000)

                    ->type('password', '12345678')
                    ->pause(1000)

                    ->press('Masuk')
                    ->pause(4000)

                    ->visit('/donasi/3/pesan')
                    ->pause(3000)

                    // Semua field sengaja dikosongkan

                    ->press('Kirim Permintaan')
                    ->pause(5000)

                    ->screenshot('tc-pesan-004');
        });
    }
}