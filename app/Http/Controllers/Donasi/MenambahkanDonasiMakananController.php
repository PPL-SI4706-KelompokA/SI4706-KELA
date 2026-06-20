<?php

namespace App\Http\Controllers\Donasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;

class MenambahkanDonasiMakananController extends Controller {

    /**
     * Menangani permintaan penambahan donasi (FR03)
     */
    public function __invoke(Request $request) {
        // Blokir akses jika user adalah Penerima
        if (Auth::check() && Auth::user()->role === 'Penerima') {
            return redirect()->route('donasi.daftar')->with('error', 'Anda terdaftar sebagai Penerima dan tidak dapat menambahkan donasi.');
        }

        // Jika request GET, tampilkan halaman form tambah donasi
        if ($request->isMethod('get')) {
            return view('donasi.create'); 
        }

        // 1. Validasi Input 
        $request->validate([
            'nama_makanan'       => 'required|max:100',
            'kategori'           => 'required|string',
            'jumlah'             => 'required|integer',
            'tanggal_kadaluarsa' => 'required|date',
            'deskripsi'          => 'nullable|max:255', 
            'lokasi'             => 'required|string',  
            'foto_makanan'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // 2. Simpan Data Lokasi (karena form hanya kirim string 'lokasi')
        $latitude = -6.917464; // Default Bandung
        $longitude = 107.619123; // Default Bandung
        
        $queries = [];
        $queries[] = $request->lokasi; // Full address
        
        // Clean zip codes out of the address
        $cleanZip = preg_replace('/\b\d{5}\b/', '', $request->lokasi);
        $queries[] = $cleanZip;
        
        // Try first part of the address (e.g. street name before comma)
        $parts = explode(',', $request->lokasi);
        if (count($parts) > 1) {
            $queries[] = trim($parts[0]) . ', Bandung';
            if (isset($parts[2])) {
                $queries[] = trim($parts[0]) . ', ' . trim($parts[2]);
            }
        }

        $success = false;
        foreach ($queries as $q) {
            if ($success) break;
            
            try {
                $address = urlencode(trim($q));
                $opts = [
                    'http' => [
                        'header' => "User-Agent: FoodShareApp/1.0\r\n"
                    ]
                ];
                $context = stream_context_create($opts);
                $response = @file_get_contents("https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1", false, $context);
                if ($response) {
                    $data = json_decode($response, true);
                    if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                        $latitude = floatval($data[0]['lat']);
                        $longitude = floatval($data[0]['lon']);
                        $success = true;
                    }
                }
            } catch (\Exception $e) {
                // Ignore and try next query
            }
            
            if (!$success) {
                usleep(500000); // 500ms delay between retries
            }
        }
        
        if (!$success) {
            // Fallback to random offset around Bandung
            $latitude += (mt_rand(-50, 50) / 10000);
            $longitude += (mt_rand(-50, 50) / 10000);
        }

        $lokasi = \App\Models\Lokasi::create([
            'alamat'    => $request->lokasi,
            'kota'      => 'Belum ditentukan',
            'latitude'  => $latitude,
            'longitude' => $longitude,
        ]);

        // 3. Simpan Data ke Tabel donasis
        $userId = Auth::id();
        if (!$userId) {
            $user = \App\Models\User::first();
            if (!$user) {
                // Buat dummy user jika database kosong
                $user = \App\Models\User::create([
                    'nama' => 'Dummy User',
                    'email' => 'dummy@test.com',
                    'password' => bcrypt('password'),
                    'role' => 'Donatur',
                    'no_telp' => '08123456789',
                    'alamat' => 'Alamat Dummy',
                    'status_verifikasi' => 'Sudah Verifikasi'
                ]);
            }
            $userId = $user->id_user;
        }

        // Handle File Upload
        $fotoUrl = null;
        if ($request->hasFile('foto_makanan')) {
            $path = $request->file('foto_makanan')->store('donasi', 'public');
            $fotoUrl = '/storage/' . $path;
        }

        Donasi::create([
            'id_user'            => $userId,
            'id_lokasi'          => $lokasi->id_lokasi,
            'nama_makanan'       => $request->nama_makanan,
            'kategori'           => $request->kategori,
            'jumlah'             => $request->jumlah,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'deskripsi'          => $request->deskripsi ?? 'Tidak ada deskripsi',
            'status_donasi'      => 'Available', 
            'foto_url'           => $fotoUrl,
        ]);

        // 3. Redirect
        // Pastikan route 'donasi.daftar' ini sudah ada di web.php kamu ya!
        return redirect()->route('donasi.daftar')->with('success', 'Donasi berhasil ditambahkan!');
    }
}