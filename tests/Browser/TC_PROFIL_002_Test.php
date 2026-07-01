<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PROFIL_002_Test extends DuskTestCase
{
    public function test_tc_profil_002(): void
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

                    ->press('✏️ Edit Profil')
                    ->pause(2000)

                    ->type('nama', 'Lisa Testing')
                    ->pause(1000)

                    ->press('Simpan Perubahan')
                    ->pause(5000)

                    ->assertSee('Profil berhasil diperbarui')
                    ->pause(3000)

                    ->screenshot('tc-profil-002-passed');
        });
    }
}