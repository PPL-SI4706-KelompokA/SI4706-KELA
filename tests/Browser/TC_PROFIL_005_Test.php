<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PROFIL_005_Test extends DuskTestCase
{
    public function test_tc_profil_005(): void
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

                    ->attach(
                        'foto_profil',
                        'C:\\Users\\Revaldo A Nainggolan\\Downloads\\LISA 2.png'
                    )

                    ->pause(2000)

                    ->press('Simpan Perubahan')
                    ->pause(5000)

                    ->assertSee('Profil berhasil diperbarui')

                    ->screenshot('tc-profil-005-upload-foto');
        });
    }
}