<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PESAN_005_Test extends DuskTestCase
{
    public function test_tc_pesan_005(): void
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

                    ->type('jumlah_permintaan', '1')
                    ->pause(1000)

                    ->type('nama_penerima', '12345')
                    ->pause(1000)

                    ->type('nomor_telepon', '08123456789')
                    ->pause(1000)

                    ->press('Kirim Permintaan')
                    ->pause(5000)

                    ->screenshot('tc-pesan-005');
        });
    }
}