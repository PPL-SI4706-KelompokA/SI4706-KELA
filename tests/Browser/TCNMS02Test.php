<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Pemberitahuan;
use App\Models\Notifikasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCNMS02Test extends DuskTestCase
{
    private function getOrCreatePenerima($email, $name)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Penerima',
                'no_telp' => '081234567892',
                'alamat' => 'Jl. Cihampelas No. 50, Bandung',
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

    public function test_pengguna_menerima_notifikasi_maintenance()
    {
        $penerima = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');

        // Bersihkan data lama jika ada
        Pemberitahuan::where('judul', 'Maintenance NMS02')->delete();
        Notifikasi::where('pesan', 'like', '%Maintenance NMS02%')->delete();

        // 1. Admin telah membuat dan mengirim notifikasi (Simulasikan via DB seeder di test)
        $judul = 'Maintenance NMS02';
        $pesan = 'Server dimatikan sementara.';
        
        Pemberitahuan::create([
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => 'Maintenance',
        ]);

        // 2. Sistem memproses & mengirim notifikasi ke seluruh user
        $users = User::all();
        $pesanFull = "⚠️ {$judul}: {$pesan}";
        $pesanTruncated = mb_strimwidth($pesanFull, 0, 255);
        
        foreach ($users as $user) {
            Notifikasi::create([
                'id_user'            => $user->id_user,
                'id_permintaan'      => null,
                'pesan'              => $pesanTruncated,
                'tanggal_notifikasi' => now()->toDateString(),
                'status_baca'        => 0,
                'tipe_notifikasi'    => 'Maintenance',
            ]);
        }

        $this->browse(function (Browser $browser) use ($penerima, $pesanTruncated) {
            // 3. Login ke sistem sebagai pengguna
            $this->login($browser, $penerima->email, 'password');

            // 4. Buka menu Notifikasi
            $browser->visit('/donasi')
                    ->pause(1000)
                    ->script("document.getElementById('notif-dropdown').classList.remove('hidden');");
            
            $browser->pause(1000)
                    // 5. Pengguna menerima pemberitahuan notifikasi dari sistem
                    ->assertSee($pesanTruncated);
        });
    }
}
