<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    public function test_tc_pesan_001(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                    ->type('email', 'Lisa@gmail.com')
                    ->type('password', '12345678')
                    ->press('Masuk')
                    ->pause(2000)

                    ->visit('/donasi/3/pesan')
                    ->type('jumlah_permintaan', '1')
                    ->type('nama_penerima', 'Revaldo')
                    ->type('nomor_telepon', '08123456789')
                    ->press('Kirim Permintaan')
                    ->pause(3000)
                    ->screenshot('tc-pesan-001');
        });
    }
}