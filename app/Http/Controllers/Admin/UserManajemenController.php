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

    public function ban(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        $request->validate([
            'banned_status' => 'required|in:temporary,permanent',
            'banned_reason' => 'required|string|max:500',
            'banned_until'  => 'required_if:banned_status,temporary|nullable|date',
        ]);

        $user->update([
            'banned_status' => $request->banned_status,
            'banned_reason' => $request->banned_reason,
            'banned_until'  => $request->banned_status === 'temporary' ? $request->banned_until : null,
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil diblokir.');
    }

    public function unban($id_user)
    {
        $user = User::findOrFail($id_user);

        $user->update([
            'banned_status' => 'not_banned',
            'banned_reason' => null,
            'banned_until'  => null,
        ]);

        return redirect()->back()->with('success', 'Pemblokiran akun pengguna berhasil dilepas.');
    }
}
