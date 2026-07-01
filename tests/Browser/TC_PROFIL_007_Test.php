<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PROFIL_007_Test extends DuskTestCase
{
    public function test_tc_profil_007(): void
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
                        'C:\\Users\\Revaldo A Nainggolan\\Downloads\\SoftSkillClass_102022330325_Revaldo A.Nainggolan.pdf'
                    )

                    ->pause(2000)

                    ->press('Simpan Perubahan')
                    ->pause(5000)

                    ->screenshot('tc-profil-007-upload-pdf');
        });
    }
}