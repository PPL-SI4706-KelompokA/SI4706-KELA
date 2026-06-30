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

            // Check if user is banned
            if ($user->banned_status && $user->banned_status !== 'not_banned') {
                $isStillBanned = true;
                if ($user->banned_status === 'temporary' && $user->banned_until && \Carbon\Carbon::parse($user->banned_until)->isPast()) {
                    // Temporary ban expired, unban them automatically
                    $user->banned_status = 'not_banned';
                    $user->banned_reason = null;
                    $user->banned_until = null;
                    $user->save();
                    $isStillBanned = false;
                }

                if ($isStillBanned) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $banType = $user->banned_status === 'permanent' ? 'Permanen' : 'Sementara';
                    $msg = "Akun Anda telah dinonaktifkan ({$banType}). Alasan: " . ($user->banned_reason ?? 'Tidak ada alasan khusus.');
                    if ($user->banned_status === 'temporary' && $user->banned_until) {
                        $msg .= " Ban akan berakhir pada: " . \Carbon\Carbon::parse($user->banned_until)->format('d M Y H:i') . " WIB.";
                    }

                    return back()->withErrors([
                        'email' => $msg,
                    ])->onlyInput('email');
                }
            }

            // Check if user is a Donatur/Penerima and their verification status
            if (in_array(strtolower($user->role), ['donatur', 'penerima']) && $user->status_verifikasi !== 'Sudah Verifikasi') {
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