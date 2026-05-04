<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistrasiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input sesuai form di mockup
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan data ke database
        $user = User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Pastikan kolom phone dan address sudah ada di migration users Anda
        ]);

        Auth::login($user);

        return redirect()->route('donasi.daftar');
    }
}