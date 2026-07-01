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
        User::create([
            'nama' => 'Admin FoodShare',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'no_telp' => '08123456789',
            'alamat' => 'Kantor FoodShare Jakarta',
            'status_verifikasi' => 'Sudah Verifikasi',
        ]);
        
        // Seed a verified donator
        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'role' => 'Donatur',
            'no_telp' => '081221344',
            'alamat' => 'Jl. Dago, Bandung',
            'status_verifikasi' => 'Sudah Verifikasi',
        ]);
    }
}
