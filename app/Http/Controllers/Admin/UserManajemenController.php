<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManajemenController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 1. Filter by role (default: Donatur)
        $role = $request->input('role', 'Donatur');
        $query->where('role', $role);

        // 2. Filter by search query (name or email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Filter by status
        $status = $request->input('status', 'all');
        if ($status === 'aktif') {
            $query->where('status_verifikasi', 'Sudah Verifikasi');
        } elseif ($status === 'nonaktif') {
            $query->where('status_verifikasi', '!=', 'Sudah Verifikasi');
        }

        // 4. Paginate
        $users = $query->paginate(6);

        return view('admin.manajemen', compact('users'));
    }

    public function ban($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat memblokir akun Anda sendiri.');
        }
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }
        $user->status_verifikasi = 'Diblokir';
        $user->save();
        return redirect()->back()->with('success', "Pengguna {$user->nama} telah diblokir.");
    }

    public function unban($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }
        $user->status_verifikasi = 'Sudah Verifikasi';
        $user->save();
        return redirect()->back()->with('success', "Pengguna {$user->nama} telah dibuka blokirnya.");
    }

    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($id, $user) {
                // Get all donasi IDs created by the user
                $donasiIds = \Illuminate\Support\Facades\DB::table('donasis')->where('id_user', $id)->pluck('id_donasi');
                
                // Get all permintaan IDs directly requested by the user
                $permintaanIds = \Illuminate\Support\Facades\DB::table('permintaans')->where('id_user', $id)->pluck('id_permintaan');

                // Get all permintaan IDs requested for donations created by the user
                $relatedPermintaanIds = \Illuminate\Support\Facades\DB::table('permintaans')->whereIn('id_donasi', $donasiIds)->pluck('id_permintaan');

                // Merge all affected permintaans
                $allPermintaanIds = $permintaanIds->concat($relatedPermintaanIds)->unique();

                // Delete riwayat_donasis referencing this user, their donations, or any of these permintaans
                \Illuminate\Support\Facades\DB::table('riwayat_donasis')
                    ->where('id_user', $id)
                    ->orWhereIn('id_donasi', $donasiIds)
                    ->orWhereIn('id_permintaan', $allPermintaanIds)
                    ->delete();

                // Delete ratings referencing this user or any of these permintaans
                \Illuminate\Support\Facades\DB::table('ratings')
                    ->where('id_user', $id)
                    ->orWhereIn('id_permintaan', $allPermintaanIds)
                    ->delete();

                // Delete the user (this cascades to donasis, permintaans, and notifikasis via database onDelete('cascade'))
                $user->delete();
            });

            return redirect()->route('admin.manajemen')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
