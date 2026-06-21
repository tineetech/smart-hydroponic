<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KelolaNotifikasiController extends Controller
{
    public function index()
    {
        $notifications = Notifikasi::orderBy('id', 'desc')->get();

        $notifJson = $notifications->map(function ($n) {
            return [
                'id'      => $n->id,
                'judul'   => $n->judul,
                'pesan'   => $n->pesan,
                'tipe'    => $n->tipe,
                'level'   => $n->level,
                'channel' => $n->channel,
            ];
        })->toJson();

        $stats = [
            'total'   => $notifications->count(),
            'alert'   => $notifications->where('tipe', 'alert')->count(),
            'info'    => $notifications->where('tipe', 'info')->count(),
            'unread'  => $notifications->where('sudah_dibaca', false)->count(),
        ];

        return view('pages.kelola-notifikasi.index', compact('notifications', 'notifJson', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'pesan'  => 'required|string',
            'tipe'   => 'required|in:alert,info,ai_insight,jadwal,error',
            'level'  => 'required|in:low,medium,high,critical',
            'channel' => 'required|in:database,telegram,email,whatsapp',
        ]);

        $validated['sudah_dibaca'] = false;

        Notifikasi::create($validated);

        return redirect()
            ->route('kelola-notifikasi.index')
            ->with('toast_success', 'Notifikasi berhasil ditambahkan.');
    }

    public function update(Request $request, $kelola_notifikasi)
    {
        $notif = Notifikasi::findOrFail($kelola_notifikasi);

        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'pesan'  => 'required|string',
            'tipe'   => 'required|in:alert,info,ai_insight,jadwal,error',
            'level'  => 'required|in:low,medium,high,critical',
            'channel' => 'required|in:database,telegram,email,whatsapp',
        ]);

        $notif->update($validated);

        return redirect()
            ->route('kelola-notifikasi.index')
            ->with('toast_success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy($kelola_notifikasi)
    {
        $notif = Notifikasi::findOrFail($kelola_notifikasi);
        $judul = $notif->judul;
        $notif->delete();

        return redirect()
            ->route('kelola-notifikasi.index')
            ->with('toast_danger', "{$judul} berhasil dihapus.");
    }
}
