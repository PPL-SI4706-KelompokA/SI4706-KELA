<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_PROFIL_006_Test extends DuskTestCase
{
    public function test_tc_profil_006(): void
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

                    // Klik OK pada popup confirm()
                    ->script("
                        window.confirm = function() {
                            return true;
                        };
                    ");

            $browser->press('🗑 Hapus Foto')
                    ->pause(2000)

                    ->press('Simpan Perubahan')
                    ->pause(5000)

                    ->assertSee('Profil berhasil diperbarui')

                    ->screenshot('tc-profil-006-hapus-foto');
        });
    }
}