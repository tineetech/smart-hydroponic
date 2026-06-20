<?php

namespace App\Http\Controllers;

use App\Models\Komponen;
use App\Models\SensorLog;
use App\Models\AktuatorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function indexSensor(Request $request)
    {
        // ── Ambil semua sensor aktif ───────────────────────────────────
        $sensors = Komponen::where('jenis_komponen', 'sensor')
            ->where('status', 'active')
            ->get();

        // ── Log terbaru per sensor (untuk cards) ──────────────────────
        $latestLogs = [];
        foreach ($sensors as $sensor) {
            $latestLogs[$sensor->id] = SensorLog::where('id_komponen', $sensor->id)
                ->latest()
                ->first();
        }

        // ── Statistik header ──────────────────────────────────────────
        $stats = [
            'total'        => $sensors->count(),
            'online'       => $sensors->count(), // semua active = online
            'normal'       => collect($latestLogs)
                                ->filter(fn($l) => $l && $l->kualitas_data === 'normal')
                                ->count(),
            'warning'      => collect($latestLogs)
                                ->filter(fn($l) => $l && $l->kualitas_data === 'warning')
                                ->count(),
            'log_hari_ini' => SensorLog::hariIni()->count(),
        ];

        // ── Data grafik historis (20 data terakhir per sensor) ────────
        $grafik = [];
        foreach ($sensors as $sensor) {
            $grafik[$sensor->id] = SensorLog::where('id_komponen', $sensor->id)
                ->latest()
                ->limit(20)
                ->get()
                ->reverse()
                ->values()
                ->map(fn($log) => [
                    'waktu' => $log->created_at->format('H:i:s'),
                    'nilai' => $log->nilai,
                ]);
        }

        // ── Histori log table (dengan filter & pagination) ────────────
        $query = SensorLog::with('komponen')->latest();

        if ($request->filled('sensor') && $request->sensor !== 'all') {
            $query->where('id_komponen', $request->sensor);
        }

        if ($request->filled('kualitas') && $request->kualitas !== 'all') {
            $query->where('kualitas_data', $request->kualitas);
        }

        $logs = $query->paginate(10)->withQueryString();

        $sensorsJs = $sensors->map(fn($s) => [
            'id'   => $s->id,
            'tipe' => strtolower($s->tipe_komponen ?? ''),
            'nama' => $s->nama_komponen,
            'batas' => json_decode($s->batas_nilai, true) ?? [],
        ])->values();

        $warnaJs = $sensors->mapWithKeys(fn($s) => [
            $s->id => match(true) {
                str_contains(strtolower($s->tipe_komponen ?? ''), 'ds18b20') => '#A8F04A',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'dht22')   => '#4A8AF0',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'hc')      => '#4AF0C8',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'ph')      => '#F0A84A',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'tds')     => '#A84AF0',
                default                                                        => '#A8F04A',
            }
        ]);

        return view('pages.monitor-sensor.index', compact(
            'sensors',
            'latestLogs',
            'stats',
            'grafik',
            'logs',
            'sensorsJs',
            'warnaJs',
        ));
    }

    public function liveSensor()
    {
        $sensors = Komponen::where('jenis_komponen', 'sensor')
            ->where('status', 'active')
            ->get();

        $latestLogs = [];
        foreach ($sensors as $sensor) {
            $log = SensorLog::where('id_komponen', $sensor->id)->latest()->first();
            $latestLogs[$sensor->id] = $log ? [
                'nilai'        => $log->nilai,
                'kualitas'     => $log->kualitas_data,
                'updated_at'   => $log->created_at->diffForHumans(),
                'is_online'    => $log->kualitas_data !== 'error',
            ] : null;
        }

        return response()->json([
            'logs'         => $latestLogs,
            'log_hari_ini' => SensorLog::hariIni()->count(),
            'stats' => [
                'normal'  => collect($latestLogs)->filter(fn($l) => $l && $l['kualitas'] === 'normal')->count(),
                'warning' => collect($latestLogs)->filter(fn($l) => $l && $l['kualitas'] === 'warning')->count(),
            ],
        ]);
    }

    // ════════════════════════════════════════════════════════════════
    //  MONITORING AKTUATOR
    // ════════════════════════════════════════════════════════════════

    public function indexAkuator(Request $request)
    {
        // ── Ambil semua aktuator aktif ─────────────────────────────────
        $aktuators = Komponen::where('jenis_komponen', 'aktuator')
            ->where('status', 'active')
            ->get();

        // ── Log terbaru per aktuator (untuk cards) ─────────────────────
        $latestLogs = [];
        foreach ($aktuators as $aktuator) {
            $latestLogs[$aktuator->id] = AktuatorLog::where('id_komponen', $aktuator->id)
                ->latest()
                ->first();
        }

        // ── Statistik header ────────────────────────────────────────────
        $stats = [
            'total'        => $aktuators->count(),
            'aktif'        => collect($latestLogs)
                                ->filter(fn($l) => $l && $l->status === AktuatorLog::STATUS_ON)
                                ->count(),
            'nonaktif'     => collect($latestLogs)
                                ->filter(fn($l) => !$l || $l->status === AktuatorLog::STATUS_OFF)
                                ->count(),
            'otomatis'     => collect($latestLogs)
                                ->filter(fn($l) => $l && in_array($l->sumber_perintah, [
                                    AktuatorLog::SUMBER_OTOMATIS,
                                    AktuatorLog::SUMBER_THRESHOLD,
                                    AktuatorLog::SUMBER_AI,
                                ]))
                                ->count(),
            'log_hari_ini' => AktuatorLog::hariIni()->count(),
        ];

        // ── Data grafik historis (riwayat ON/OFF, 20 terakhir per aktuator) ──
        $grafik = [];
        foreach ($aktuators as $aktuator) {
            $grafik[$aktuator->id] = AktuatorLog::where('id_komponen', $aktuator->id)
                ->latest()
                ->limit(20)
                ->get()
                ->reverse()
                ->values()
                ->map(fn($log) => [
                    'waktu'  => $log->created_at->format('H:i:s'),
                    'status' => $log->status === AktuatorLog::STATUS_ON ? 1 : 0,
                    'pwm'    => $log->nilai_pwm,
                ]);
        }

        // ── Total durasi nyala (menit) hari ini per aktuator ────────────
        $durasiHariIni = [];
        foreach ($aktuators as $aktuator) {
            $durasiHariIni[$aktuator->id] = AktuatorLog::where('id_komponen', $aktuator->id)
                ->hariIni()
                ->where('status', AktuatorLog::STATUS_ON)
                ->sum('durasi_detik');
        }

        // ── Histori log table (dengan filter & pagination) ──────────────
        $query = AktuatorLog::with('komponen')->latest();

        if ($request->filled('aktuator') && $request->aktuator !== 'all') {
            $query->where('id_komponen', $request->aktuator);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sumber') && $request->sumber !== 'all') {
            $query->where('sumber_perintah', $request->sumber);
        }

        $logs = $query->paginate(10)->withQueryString();

        $aktuatorsJs = $aktuators->map(fn($a) => [
            'id'   => $a->id,
            'tipe' => strtolower($a->tipe_komponen ?? ''),
            'nama' => $a->nama_komponen,
        ])->values();

        $warnaJs = $aktuators->mapWithKeys(fn($a) => [
            $a->id => match(true) {
                str_contains(strtolower($a->tipe_komponen ?? ''), 'pompa') => '#A8F04A',
                str_contains(strtolower($a->tipe_komponen ?? ''), 'ssr')   => '#A8F04A',
                str_contains(strtolower($a->tipe_komponen ?? ''), 'ph down') => '#F04A4A',
                str_contains(strtolower($a->tipe_komponen ?? ''), 'ph up')   => '#4A8AF0',
                str_contains(strtolower($a->tipe_komponen ?? ''), 'motor')   => '#F0A84A',
                str_contains(strtolower($a->tipe_komponen ?? ''), 'kipas')   => '#4AF0C8',
                str_contains(strtolower($a->tipe_komponen ?? ''), 'lampu')   => '#F0E04A',
                default                                                       => '#A84AF0',
            }
        ]);

        return view('pages.monitor-akuator.index', compact(
            'aktuators',
            'latestLogs',
            'stats',
            'grafik',
            'durasiHariIni',
            'logs',
            'aktuatorsJs',
            'warnaJs',
        ));
    }

    public function liveAkuator()
    {
        $aktuators = Komponen::where('jenis_komponen', 'aktuator')
            ->where('status', 'active')
            ->get();

        $latestLogs = [];
        foreach ($aktuators as $aktuator) {
            $log = AktuatorLog::where('id_komponen', $aktuator->id)->latest()->first();
            $latestLogs[$aktuator->id] = $log ? [
                'status'          => $log->status,
                'is_on'           => $log->status === AktuatorLog::STATUS_ON,
                'nilai_pwm'       => $log->nilai_pwm,
                'sumber_perintah' => $log->sumber_perintah,
                'durasi_detik'    => $log->durasi_detik,
                'updated_at'      => $log->created_at->diffForHumans(),
            ] : null;
        }

        $durasiHariIni = [];
        foreach ($aktuators as $aktuator) {
            $durasiHariIni[$aktuator->id] = AktuatorLog::where('id_komponen', $aktuator->id)
                ->hariIni()
                ->where('status', AktuatorLog::STATUS_ON)
                ->sum('durasi_detik');
        }

        return response()->json([
            'logs'           => $latestLogs,
            'durasi_hari_ini'=> $durasiHariIni,
            'log_hari_ini'   => AktuatorLog::hariIni()->count(),
            'stats' => [
                'aktif'    => collect($latestLogs)->filter(fn($l) => $l && $l['is_on'])->count(),
                'nonaktif' => collect($latestLogs)->filter(fn($l) => !$l || !$l['is_on'])->count(),
            ],
        ]);
    }

    /**
     * Toggle ON/OFF aktuator secara manual dari panel admin.
     * Membuat record AktuatorLog baru dengan sumber_perintah = manual.
     */
    public function toggleAkuator(Request $request, Komponen $komponen)
    {
        $request->validate([
            'status'    => 'required|in:on,off',
            'nilai_pwm' => 'nullable|integer|min:0|max:255',
            'catatan'   => 'nullable|string|max:255',
        ]);

        if ($komponen->jenis_komponen !== Komponen::JENIS_AKTUATOR) {
            return response()->json([
                'message' => 'Komponen ini bukan aktuator.',
            ], 422);
        }

        $log = DB::transaction(function () use ($request, $komponen) {
            // Jika aktuator sebelumnya ON dan sekarang dimatikan,
            // hitung durasi sejak log ON terakhir.
            $durasi = null;
            if ($request->status === AktuatorLog::STATUS_OFF) {
                $logOnTerakhir = AktuatorLog::where('id_komponen', $komponen->id)
                    ->where('status', AktuatorLog::STATUS_ON)
                    ->latest()
                    ->first();

                if ($logOnTerakhir) {
                    $durasi = $logOnTerakhir->created_at->diffInSeconds(now());
                }
            }

            return AktuatorLog::create([
                'id_komponen'     => $komponen->id,
                'status'          => $request->status,
                'durasi_detik'    => $durasi,
                'nilai_pwm'       => $request->nilai_pwm,
                'sumber_perintah' => AktuatorLog::SUMBER_MANUAL,
                'catatan'         => $request->catatan ?? 'Diubah manual via panel admin',
            ]);
        });

        return response()->json([
            'message' => 'Status aktuator berhasil diperbarui.',
            'log'     => [
                'id'              => $log->id,
                'status'          => $log->status,
                'is_on'           => $log->is_on,
                'nilai_pwm'       => $log->nilai_pwm,
                'sumber_perintah' => $log->sumber_perintah,
                'updated_at'      => $log->created_at->diffForHumans(),
            ],
        ]);
    }
}