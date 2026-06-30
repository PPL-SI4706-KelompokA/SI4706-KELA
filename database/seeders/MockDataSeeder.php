<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Donasi;
use App\Models\permintaan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        $mockEmails = ['siti@email.com', 'joko@email.com', 'restuibu@email.com', 'rahmat@email.com', 'panti@email.com', 'dian@email.com'];
        $mockUserIds = User::whereIn('email', $mockEmails)->pluck('id_user')->toArray();

        // 1. Clean up old mock donations and requests associated with mock users to prevent duplicate bloat
        DB::table('riwayat_donasis')->whereIn('id_user', $mockUserIds)->delete();
        DB::table('riwayat_donasis')->whereIn('id_donasi', function($q) use ($mockUserIds) {
            $q->select('id_donasi')->from('donasis')->whereIn('id_user', $mockUserIds);
        })->delete();

        permintaan::whereIn('id_user', $mockUserIds)->delete();
        permintaan::whereIn('id_donasi', function($q) use ($mockUserIds) {
            $q->select('id_donasi')->from('donasis')->whereIn('id_user', $mockUserIds);
        })->delete();

        Donasi::whereIn('id_user', $mockUserIds)->delete();

        // 2. Ensure we have Bandung locations for active donations
        $bandungLocations = [
            ['alamat' => 'Jl. Merdeka No. 45', 'kota' => 'Bandung', 'latitude' => -6.917464, 'longitude' => 107.619122],
            ['alamat' => 'Jl. Dago No. 102', 'kota' => 'Bandung', 'latitude' => -6.892015, 'longitude' => 107.615234],
            ['alamat' => 'Jl. Riau No. 56', 'kota' => 'Bandung', 'latitude' => -6.908234, 'longitude' => 107.625123],
            ['alamat' => 'Jl. Pasir Kaliki No. 121', 'kota' => 'Bandung', 'latitude' => -6.903451, 'longitude' => 107.598234],
            ['alamat' => 'Jl. Buah Batu No. 180', 'kota' => 'Bandung', 'latitude' => -6.942123, 'longitude' => 107.627234],
            ['alamat' => 'Jl. Cihampelas No. 90', 'kota' => 'Bandung', 'latitude' => -6.899123, 'longitude' => 107.604234],
            ['alamat' => 'Jl. Asia Afrika No. 10', 'kota' => 'Bandung', 'latitude' => -6.921123, 'longitude' => 107.611234],
            ['alamat' => 'Jl. Gatot Subroto No. 289', 'kota' => 'Bandung', 'latitude' => -6.925451, 'longitude' => 107.636234],
        ];

        $lokasiIds = [];
        foreach ($bandungLocations as $loc) {
            $lokasi = Lokasi::firstOrCreate(
                ['alamat' => $loc['alamat'], 'kota' => $loc['kota']],
                ['latitude' => $loc['latitude'], 'longitude' => $loc['longitude']]
            );
            $lokasiIds[] = $lokasi->id_lokasi;
        }

        // 3. Ensure Donatur accounts exist
        $donaturData = [
            ['nama' => 'Siti Aminah', 'email' => 'siti@email.com', 'no_telp' => '08123456701', 'alamat' => 'Jl. Merdeka No. 45, Bandung'],
            ['nama' => 'Joko Widodo', 'email' => 'joko@email.com', 'no_telp' => '08123456702', 'alamat' => 'Jl. Sudirman No. 12, Jakarta Pusat'],
            ['nama' => 'Restu Ibu Bakery', 'email' => 'restuibu@email.com', 'no_telp' => '08123456703', 'alamat' => 'Jl. Malioboro No. 88, Yogyakarta'],
        ];

        $donaturUsers = [];
        foreach ($donaturData as $index => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nama' => $data['nama'],
                    'password' => Hash::make('12345678'),
                    'role' => 'Donatur',
                    'no_telp' => $data['no_telp'],
                    'alamat' => $data['alamat'],
                    'status_verifikasi' => 'Sudah Verifikasi',
                    'id_lokasi' => $lokasiIds[$index % count($lokasiIds)],
                ]
            );
            $donaturUsers[] = $user;
        }

        // 4. Ensure Penerima accounts exist
        $penerimaData = [
            ['nama' => 'Rahmat Hidayat', 'email' => 'rahmat@email.com', 'no_telp' => '08123456704', 'alamat' => 'Jl. Kebon Waru, Bandung'],
            ['nama' => 'Panti Asuhan Kasih', 'email' => 'panti@email.com', 'no_telp' => '08123456705', 'alamat' => 'Jl. Mangga Dua, Jakarta Pusat'],
            ['nama' => 'Dian Pratama', 'email' => 'dian@email.com', 'no_telp' => '08123456706', 'alamat' => 'Jl. Monjali, Yogyakarta'],
        ];

        $penerimaUsers = [];
        foreach ($penerimaData as $index => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nama' => $data['nama'],
                    'password' => Hash::make('12345678'),
                    'role' => 'Penerima',
                    'no_telp' => $data['no_telp'],
                    'alamat' => $data['alamat'],
                    'status_verifikasi' => 'Sudah Verifikasi',
                    'id_lokasi' => $lokasiIds[($index + 1) % count($lokasiIds)],
                ]
            );
            $penerimaUsers[] = $user;
        }

        // 5. Seed 15 ACTIVE/AVAILABLE donations in Bandung (visible on map)
        $foodItemsActive = [
            ['nama' => 'Nasi Padang Lauk Rendang', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Nasi Padang hangat dengan rendang sapi asli, sayur nangka, dan sambal ijo.'],
            ['nama' => 'Roti Bakar Bandung Cokelat', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Roti bakar isi cokelat meises lumer manis.'],
            ['nama' => 'Es Teh Manis Jumbo', 'kategori' => 'Minuman', 'deskripsi' => 'Es teh manis segar ukuran cup besar.'],
            ['nama' => 'Ayam Goreng Geprek', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Ayam goreng tepung digeprek dengan sambal bawang pedas.'],
            ['nama' => 'Pisang Goreng Keju', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Pisang goreng crispy hangat ditaburi parutan keju susu.'],
            ['nama' => 'Juice Alpukat Kocok', 'kategori' => 'Minuman', 'deskripsi' => 'Alpukat kocok dengan susu kental manis cokelat.'],
            ['nama' => 'Nasi Kuning Komplit', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Nasi kuning gurih dengan mie goreng, orek tempe, dan telur iris.'],
            ['nama' => 'Donat Kentang Gula', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Donat kentang empuk dengan taburan gula halus.'],
            ['nama' => 'Kopi Susu Gula Aren', 'kategori' => 'Minuman', 'deskripsi' => 'Kopi susu dingin dengan gula aren murni.'],
            ['nama' => 'Bubur Ayam Cianjur', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Bubur ayam hangat dengan kuah kuning gurih dan kerupuk.'],
            ['nama' => 'Cireng Rujak Pedas', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Cireng goreng kenyal dengan cocolan bumbu rujak pedas manis.'],
            ['nama' => 'Es Kelapa Muda Segar', 'kategori' => 'Minuman', 'deskripsi' => 'Es kelapa muda dengan air kelapa murni tanpa sirup.'],
            ['nama' => 'Gado-Gado Betawi', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Sayuran rebus segar disiram bumbu kacang gurih khas Betawi.'],
            ['nama' => 'Martabak Telur Spesial', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Martabak telur gurih renyah dengan isian daging sapi cincang.'],
            ['nama' => 'Susu Jahe Hangat', 'kategori' => 'Minuman', 'deskripsi' => 'Minuman susu hangat dicampur jahe bakar geprek.'],
        ];

        $now = Carbon::now();

        foreach ($foodItemsActive as $i => $item) {
            $createdDate = (clone $now)->subDays(rand(0, 5));
            $expiryDate = (clone $now)->addDays(rand(2, 7)); // Future expiry date (active!)

            Donasi::create([
                'id_user' => $donaturUsers[$i % count($donaturUsers)]->id_user,
                'id_lokasi' => $lokasiIds[$i % count($lokasiIds)],
                'nama_makanan' => $item['nama'],
                'kategori' => $item['kategori'],
                'jumlah' => rand(10, 40),
                'tanggal_kadaluarsa' => $expiryDate->toDateString(),
                'deskripsi' => $item['deskripsi'],
                'status_donasi' => 'Available',
                'status_verifikasi' => 'Sudah Verifikasi',
                'verified_by' => 1,
                'tanggal_verifikasi' => $createdDate->toDateString(),
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);
        }

        // 6. Seed 15 HISTORICAL/COMPLETED donations spanning the last 6 months (for statistics)
        $foodItemsHistorical = [
            ['nama' => 'Nasi Kotak Ayam Bakar', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Nasi kotak lengkap dengan ayam bakar, lalapan, dan sambal.'],
            ['nama' => 'Roti Tawar Gandum', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Roti tawar gandum utuh, serat tinggi.'],
            ['nama' => 'Susu UHT 1L', 'kategori' => 'Minuman', 'deskripsi' => 'Susu sapi segar UHT rasa plain.'],
            ['nama' => 'Biskuit Cokelat', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Biskuit cokelat renyah manis.'],
            ['nama' => 'Nasi Goreng Spesial', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Nasi goreng dengan telur mata sapi dan sosis.'],
            ['nama' => 'Jus Jeruk Segar', 'kategori' => 'Minuman', 'deskripsi' => 'Jus jeruk murni tanpa bahan pengawet.'],
            ['nama' => 'Kue Bolu Kukus', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Kue bolu kukus pandan manis lembut.'],
            ['nama' => 'Mie Goreng Ayam', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Mie goreng dengan potongan daging ayam dan sawi.'],
            ['nama' => 'Teh Kotak Melati', 'kategori' => 'Minuman', 'deskripsi' => 'Teh manis kemasan kotak rasa melati.'],
            ['nama' => 'Roti Manis Cokelat Keju', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Roti manis dengan isian cokelat dan taburan keju.'],
            ['nama' => 'Soto Ayam Lamongan', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Soto ayam hangat lengkap dengan kuah koya.'],
            ['nama' => 'Air Mineral Botol 600ml', 'kategori' => 'Minuman', 'deskripsi' => 'Air mineral botol bersih higienis.'],
            ['nama' => 'Sate Ayam Madura', 'kategori' => 'Makanan Berat', 'deskripsi' => 'Sate ayam dengan bumbu kacang kental manis gurih.'],
            ['nama' => 'Kripik Singkong Balado', 'kategori' => 'Cemilan / Snack', 'deskripsi' => 'Kripik singkong renyah bumbu balado pedas manis.'],
            ['nama' => 'Es Blewah Segar', 'kategori' => 'Minuman', 'deskripsi' => 'Es blewah serut manis dengan sirup cocopandan.']
        ];

        foreach ($foodItemsHistorical as $i => $item) {
            // Span 6 months (5 to 1 months ago)
            $monthsAgo = 5 - intval($i / 3);
            $createdDate = (clone $now)->subMonths($monthsAgo)->subDays(rand(1, 25));
            $expiryDate = (clone $createdDate)->addDays(rand(3, 7));

            $donasi = Donasi::create([
                'id_user' => $donaturUsers[$i % count($donaturUsers)]->id_user,
                'id_lokasi' => $lokasiIds[$i % count($lokasiIds)],
                'nama_makanan' => $item['nama'],
                'kategori' => $item['kategori'],
                'jumlah' => rand(5, 20),
                'tanggal_kadaluarsa' => $expiryDate->toDateString(),
                'deskripsi' => $item['deskripsi'],
                'status_donasi' => 'Distributed',
                'status_verifikasi' => 'Sudah Verifikasi',
                'verified_by' => 1,
                'tanggal_verifikasi' => $createdDate->toDateString(),
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            // Create permintaan and riwayat
            $recipient = $penerimaUsers[$i % count($penerimaUsers)];
            
            $permintaan = permintaan::create([
                'id_user' => $recipient->id_user,
                'id_donasi' => $donasi->id_donasi,
                'jumlah_permintaan' => rand(2, 5),
                'catatan' => 'Pemesanan data historis.',
                'status' => 'Selesai',
                'tanggal_acc' => $createdDate->copy()->addHours(rand(1, 4))->toDateString(),
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            DB::table('riwayat_donasis')->insert([
                'id_donasi' => $donasi->id_donasi,
                'id_permintaan' => $permintaan->id_permintaan,
                'id_user' => $recipient->id_user,
                'status_pengambilan' => 'Sudah Diambil',
                'tanggal_pembelian' => $permintaan->tanggal_acc,
                'created_at' => $permintaan->created_at,
                'updated_at' => $permintaan->created_at,
            ]);
        }
    }
}
