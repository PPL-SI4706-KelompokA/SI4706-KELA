<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all unverified Donators and Receivers
        $unverifiedUsers = User::whereIn('role', ['Donatur', 'donatur', 'Penerima', 'penerima'])
            ->where('status_verifikasi', 'Belum Verifikasi')
            ->get();

        // Get selected user
        $selectedUserId = $request->input('user_id');
        $selectedUser = null;
        
        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
        }
        
        if (!$selectedUser && $unverifiedUsers->count() > 0) {
            $selectedUser = $unverifiedUsers->first();
        }

        return view('admin.verifikasi', compact('unverifiedUsers', 'selectedUser'));
    }

    public function setuju($id)
    {
        $user = User::findOrFail($id);
        $user->status_verifikasi = 'Sudah Verifikasi';
        $user->save();

        // Create notification for the user
        $roleLabel = strtolower($user->role) === 'donatur' ? 'donatur' : 'penerima';
        $pesan = strtolower($user->role) === 'donatur'
            ? 'Akun donatur Anda telah disetujui dan diverifikasi oleh admin! Anda sekarang dapat mulai melakukan donasi.'
            : 'Akun penerima Anda telah disetujui dan diverifikasi oleh admin! Anda sekarang dapat mulai menerima donasi.';

        Notifikasi::create([
            'id_user' => $user->id_user,
            'id_permintaan' => null,
            'pesan' => $pesan,
            'tanggal_notifikasi' => now()->toDateString(),
            'status_baca' => 0,
            'tipe_notifikasi' => 'Permintaan Disetujui',
        ]);

        $roleTitle = ucfirst(strtolower($user->role));
        return redirect()->route('admin.verifikasi')->with('success', 'Akun ' . $roleTitle . ' ' . $user->nama . ' berhasil diverifikasi.');
    }

    public function tolak($id)
    {
        $user = User::findOrFail($id);
        $user->status_verifikasi = 'Ditolak';
        $user->save();

        // Create notification for the user
        $roleLabel = strtolower($user->role) === 'donatur' ? 'donatur' : 'penerima';
        Notifikasi::create([
            'id_user' => $user->id_user,
            'id_permintaan' => null,
            'pesan' => 'Verifikasi akun ' . $roleLabel . ' Anda ditolak oleh admin. Harap periksa kembali dokumen pendukung Anda.',
            'tanggal_notifikasi' => now()->toDateString(),
            'status_baca' => 0,
            'tipe_notifikasi' => 'Permintaan Ditolak',
        ]);

        return redirect()->route('admin.verifikasi')->with('success', 'Verifikasi ' . $roleLabel . ' ' . $user->nama . ' ditolak.');
    }
}
