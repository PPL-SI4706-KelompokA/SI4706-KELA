<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin User
        User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'nama' => 'Admin FoodShare',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'no_telp' => '08123456789',
                'alamat' => 'Kantor FoodShare Jakarta',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]
        );
        
        // Seed a verified donator
        User::firstOrCreate(
            ['email' => 'budi@email.com'],
            [
                'nama' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'Donatur',
                'no_telp' => '081221344',
                'alamat' => 'Jl. Dago, Bandung',
                'status_verifikasi' => 'Sudah Verifikasi',
            ]
        );

        // Call the custom mock data seeder
        $this->call([
            MockDataSeeder::class,
        ]);
    }
}
