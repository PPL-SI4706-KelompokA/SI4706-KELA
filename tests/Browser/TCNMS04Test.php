<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Pemberitahuan;
use App\Models\Notifikasi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCNMS04Test extends DuskTestCase
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

    private function getOrCreateDonatur()
    {
        $email = 'dewi@email.com';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'nama' => 'Dewi Lestari',
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'Donatur',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Dago No. 12, Bandung',
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

    public function test_admin_menghapus_notifikasi_maintenance()
    {
        $admin = $this->getOrCreateAdmin();
        $donatur = $this->getOrCreateDonatur();

        // Bersihkan data lama jika ada
        Pemberitahuan::where('judul', 'Maintenance Salah NMS04')->delete();
        Notifikasi::where('pesan', 'like', '%Maintenance Salah NMS04%')->delete();

        // Buat notifikasi dengan informasi salah via database
        $judul = 'Maintenance Salah NMS04';
        $pesan = 'Pesan Salah NMS04';
        
        $announcement = Pemberitahuan::create([
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => 'Maintenance',
        ]);

        // Kirim notifikasi ke seluruh user (termasuk donatur)
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

        $this->browse(function (Browser $browser) use ($admin, $donatur, $announcement, $pesanTruncated) {
            // 1. Login ke sistem sebagai admin.
            $this->login($browser, $admin->email, 'password');

            // 2. Masuk ke halaman Kelola Pemberitahuan.
            $browser->visit('/admin/pemberitahuan')
                    ->pause(1000)
                    // 3. Cari notifikasi yang berisi informasi salah & 4. Pilih notifikasi tersebut.
                    // 5. Klik tombol Hapus.
                    ->click('#ann-card-' . $announcement->id_pemberitahuan . ' button[type="submit"]')
                    ->pause(500)
                    // 6. Konfirmasi penghapusan notifikasi.
                    ->acceptDialog()
                    ->pause(1500)
                    // 7. Sistem menghapus notifikasi dari daftar pemberitahuan.
                    ->assertDontSee('Maintenance Salah NMS04');

            // Pastikan database terupdate
            $this->assertDatabaseMissing('pemberitahuans', [
                'id_pemberitahuan' => $announcement->id_pemberitahuan,
            ]);
            $this->assertDatabaseMissing('notifikasis', [
                'pesan' => $pesanTruncated,
            ]);

            // 8. Login ke sistem sebagai pengguna donatur, lalu buka menu Notifikasi untuk memastikan bahwa notifikasi tersebut telah dihapus.
            $this->login($browser, $donatur->email, 'password');

            $browser->visit('/donasi')
                    ->pause(1000)
                    ->script("document.getElementById('notif-dropdown').classList.remove('hidden');");

            $browser->pause(1000)
                    ->assertDontSee($pesanTruncated);
        });
    }
}
