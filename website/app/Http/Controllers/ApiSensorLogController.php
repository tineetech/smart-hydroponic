<?php

namespace App\Http\Controllers;

use App\Models\Komponen;
use App\Models\SensorLog;
use Illuminate\Http\Request;

class ApiSensorLogController extends Controller
{
    public function store(Request $request)
    {
        // ── Validasi parameter ────────────────────────────────────────────
        $validated = $request->validate([
            'nama_komponen'  => 'required|string',
            'nilai'          => 'required|string', // JSON string, contoh: {"suhu_air":28.5}
            'kualitas_data'  => 'required|in:normal,warning,critical,error',
            'sudah_diproses' => 'nullable|in:0,1,true,false',
        ]);

        // ── Cari komponen berdasarkan nama ────────────────────────────────
        $komponen = Komponen::where('nama_komponen', 'like', "%" . $validated['nama_komponen'] . '%')
            ->where('jenis_komponen', 'sensor')
            ->first();

        if (!$komponen) {
            return response()->json([
                'success' => false,
                'message' => "Komponen '{$validated['nama_komponen']}' tidak ditemukan.",
            ], 404);
        }

        // ── Parse nilai JSON ──────────────────────────────────────────────
        $nilai = json_decode($validated['nilai'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter nilai harus berupa JSON yang valid. Contoh: {"suhu_air":28.5}',
            ], 422);
        }

        // ── Normalize sudah_diproses ──────────────────────────────────────
        $sudahDiproses = in_array($request->input('sudah_diproses'), [1, '1', 'true', true], true);

        // ── Simpan log ────────────────────────────────────────────────────
        $log = SensorLog::create([
            'id_komponen'    => $komponen->id,
            'nilai'          => $nilai,
            'kualitas_data'  => $validated['kualitas_data'],
            'sudah_diproses' => $sudahDiproses,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sensor log berhasil disimpan.',
            'data'    => [
                'id'             => $log->id,
                'id_komponen'    => $log->id_komponen,
                'nama_komponen'  => $komponen->nama_komponen,
                'tipe_komponen'  => $komponen->tipe_komponen,
                'nilai'          => $log->nilai,
                'kualitas_data'  => $log->kualitas_data,
                'sudah_diproses' => $log->sudah_diproses,
                'created_at'     => $log->created_at->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }
}