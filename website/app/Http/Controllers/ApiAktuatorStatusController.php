<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AktuatorLog;
use Illuminate\Http\Request;

class ApiAktuatorStatusController extends Controller
{
    /**
     * Ambil status TERBARU aktuator (untuk dipanggil berkala / polling dari frontend).
     *
     * Format response flat, simetris dengan payload ApiBatchLogController::store():
     * {
     *   "aktuator": {
     *     "data": {
     *       "SSR Pompa Utama": "on",
     *       "Motor pH Down": "off",
     *       "Motor pH Up": "off"
     *     }
     *   }
     * }
     *
     * Tanpa parameter -> status terbaru SEMUA aktuator.
     * Dengan ?id_komponen=9 -> status terbaru 1 aktuator spesifik saja (tetap format flat).
     *
     * Contoh:
     *   GET /api/aktuator/status
     *   GET /api/aktuator/status?id_komponen=9
     */
    public function status(Request $request)
    {
        $validated = $request->validate([
            'id_komponen' => 'nullable|integer|exists:komponen,id',
        ]);

        $query = AktuatorLog::query()
            ->with(['komponen:id,nama_komponen'])
            ->terbaruPerKomponen();

        // ── Filter ke 1 komponen spesifik jika diminta ──────────────
        if (!empty($validated['id_komponen'])) {
            $query->dariKomponen((int) $validated['id_komponen']);
        }

        $logs = $query->get();

        // ── Susun jadi flat: nama_komponen => status ────────────────
        $data = [];
        foreach ($logs as $log) {
            $namaKomponen = $log->komponen->nama_komponen ?? "komponen#{$log->id_komponen}";
            $data[$namaKomponen] = $log->status;
        }

        return response()->json([
            'aktuator' => [
                'data' => $data,
            ],
        ], 200);
    }
}