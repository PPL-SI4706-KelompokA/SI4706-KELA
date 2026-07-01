<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Donasi;
use App\Models\Lokasi;
use App\Models\permintaan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCRP02Test extends DuskTestCase
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

    private function prepareDataForPenerima($penerima)
    {
        permintaan::where('id_user', $penerima->id_user)->delete();

        $donor = User::where('role', 'Donatur')->first() ?: User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => bcrypt('password'),
            'role' => 'Donatur',
            'no_telp' => '081221344',
            'alamat' => 'Jl. Dago, Bandung',
            'status_verifikasi' => 'Sudah Verifikasi',
        ]);

        $lokasi = Lokasi::first() ?: Lokasi::create([
            'alamat' => 'Jl. Dago No. 10, Bandung',
            'kota' => 'Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619122,
        ]);

        $donasi = Donasi::create([
            'id_user' => $donor->id_user,
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_makanan' => 'Nasi Bungkus Padang ' . time(),
            'kategori' => 'Makanan Berat',
            'jumlah' => 5,
            'tanggal_kadaluarsa' => '2026-12-31',
            'deskripsi' => 'Nasi bungkus Padang lengkap dengan rendang dan sayur',
            'status_donasi' => 'Available',
            'status_verifikasi' => 'Disetujui',
        ]);

        permintaan::create([
            'id_user' => $penerima->id_user,
            'id_donasi' => $donasi->id_donasi,
            'jumlah_permintaan' => 2,
            'status' => 'Disetujui',
        ]);
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

    public function test_validasi_data_sesuai_pengguna()
    {
        $penerimaA = $this->getOrCreatePenerima('rian@email.com', 'Rian Hidayat');
        $penerimaB = $this->getOrCreatePenerima('siti@email.com', 'Siti Aminah');

        $this->prepareDataForPenerima($penerimaA);
        permintaan::where('id_user', $penerimaB->id_user)->delete();

        $this->browse(function (Browser $browser) use ($penerimaB) {
            $this->login($browser, $penerimaB->email, 'password');

            $browser->visit('/penerima/riwayat')
                    ->pause(1000)
                    ->assertSee('Belum ada riwayat penerimaan.');
        });
    }
}
