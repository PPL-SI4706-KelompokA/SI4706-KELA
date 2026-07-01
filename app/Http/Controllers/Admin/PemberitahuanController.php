<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemberitahuan;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PemberitahuanController extends Controller
{
    public function index()
    {
        $announcements = Pemberitahuan::latest()->get();
        return view('admin.pemberitahuan', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:100',
            'pesan'           => 'required|string',
            'tipe'            => 'required|string|in:Maintenance,Informasi',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $announcement = Pemberitahuan::create([
            'judul'           => $request->judul,
            'pesan'           => $request->pesan,
            'tipe'            => $request->tipe,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        // Send notifications to all users (Donaturs, Penerima, etc.)
        $users = User::all();
        $tipeNotif = $request->tipe; // 'Maintenance' or 'Informasi'
        $emoji = $tipeNotif === 'Maintenance' ? '⚠️' : '📢';
        $pesanFull = "{$emoji} {$request->judul}: {$request->pesan}";
        $pesanTruncated = mb_strimwidth($pesanFull, 0, 255);
        
        foreach ($users as $user) {
            Notifikasi::create([
                'id_user'            => $user->id_user,
                'id_permintaan'      => null,
                'pesan'              => $pesanTruncated,
                'tanggal_notifikasi' => now()->toDateString(),
                'status_baca'        => 0,
                'tipe_notifikasi'    => $tipeNotif,
            ]);
        }

        return redirect()->route('admin.pemberitahuan')->with('success', 'Pemberitahuan berhasil dibuat dan dikirim ke seluruh user.');
    }

    public function destroy($id)
    {
        $announcement = Pemberitahuan::findOrFail($id);

        // Find and delete all corresponding user notifications
        $emoji = $announcement->tipe === 'Maintenance' ? '⚠️' : '📢';
        $pesanFull = "{$emoji} {$announcement->judul}: {$announcement->pesan}";
        $pesanTruncated = mb_strimwidth($pesanFull, 0, 255);

        Notifikasi::where('pesan', $pesanTruncated)->delete();

        // Delete the announcement itself
        $announcement->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Pemberitahuan dan notifikasi terkait berhasil dihapus.'
            ]);
        }

        return redirect()->route('admin.pemberitahuan')->with('success', 'Pemberitahuan dan notifikasi terkait berhasil dihapus.');
    }
}
