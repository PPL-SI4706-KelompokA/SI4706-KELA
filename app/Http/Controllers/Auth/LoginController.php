<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user is a Donatur and their verification status
            if (in_array(strtolower($user->role), ['donatur']) && $user->status_verifikasi !== 'Sudah Verifikasi') {
                $status = $user->status_verifikasi;
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = $status === 'Ditolak'
                    ? 'Pendaftaran akun Anda ditolak oleh admin.'
                    : 'Akun Anda belum diverifikasi oleh admin. Harap tunggu verifikasi.';

                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Redirect based on role
            if (in_array(strtolower($user->role), ['admin'])) {
                return redirect()->route('admin.statistik');
            }

            return redirect()->route('donasi.daftar');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}