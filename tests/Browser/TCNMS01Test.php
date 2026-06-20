<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Pemberitahuan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCNMS01Test extends DuskTestCase
{
    private function getOrCreateAdmin()
    {
        $email = 'admin@email.com';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => 'Andi Pratama',
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'no_telp' => '081234567890',
                'alamat' => 'Kantor Pusat FoodShare',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]);
        }
        return $user;
    }

    private function login(Browser $browser, $email, $password)
    {
        $browser->blank();
        $browser->driver->manage()->deleteAllCookies();
        
        $browser->visit('/login')
                ->pause(1000)
                ->type('input[type="email"]', $email)
                ->type('input[type="password"]', $password)
                ->press('button[type="submit"]')
                ->pause(1000);
    }

    public function test_admin_membuat_notifikasi_maintenance()
    {
        $admin = $this->getOrCreateAdmin();

        // Bersihkan data lama dengan judul ini jika ada
        Pemberitahuan::where('judul', 'Maintenance NMS01')->delete();

        $this->browse(function (Browser $browser) use ($admin) {
            $this->login($browser, $admin->email, 'password');

            // 1. Masuk ke menu pengelolaan notifikasi maintenance
            $browser->visit('/admin/pemberitahuan')
                    ->pause(1000)
                    // 2. Pilih opsi untuk membuat notifikasi baru (mengisi form)
                    // 3. Masukkan data maintenance yang diperlukan
                    ->type('input[name="judul"]', 'Maintenance NMS01')
                    ->select('select[name="tipe"]', 'Maintenance')
                    ->type('textarea[name="pesan"]', 'Server akan dimigrasi pada jam 24:00.')
                    // 4. Klik tombol Submit atau Kirim
                    ->press('button[type="submit"]')
                    ->pause(1000)
                    // 5. Sistem menampilkan pemberitahuan bahwa notifikasi berhasil dibuat
                    ->assertSee('Pemberitahuan berhasil dibuat dan dikirim ke seluruh user.');
            
            // 6. Sistem menyimpan notifikasi maintenance
            $this->assertDatabaseHas('pemberitahuans', [
                'judul' => 'Maintenance NMS01',
                'tipe' => 'Maintenance',
                'pesan' => 'Server akan dimigrasi pada jam 24:00.',
            ]);
        });
    }
}
