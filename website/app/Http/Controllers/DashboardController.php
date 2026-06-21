<?php

namespace App\Http\Controllers;

use App\Models\Komponen;
use App\Models\SensorLog;
use App\Models\AktuatorLog;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Ambil semua sensor aktif ───────────────────────────────────
        $sensors = Komponen::where('jenis_komponen', 'sensor')
            ->where('status', 'active')
            ->get();

        // ── Log terbaru per sensor ─────────────────────────────────────
        $latestLogs = [];
        foreach ($sensors as $sensor) {
            $latestLogs[$sensor->id] = SensorLog::where('id_komponen', $sensor->id)
                ->latest()
                ->first();
        }

        // ── Data grafik (20 data terakhir per sensor) ──────────────────
        $chartData = [];
        foreach ($sensors as $sensor) {
            $chartData[$sensor->id] = SensorLog::where('id_komponen', $sensor->id)
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

        // ── Sensor stats ────────────────────────────────────────────────
        $sensorStats = [
            'total'   => $sensors->count(),
            'normal'  => collect($latestLogs)->filter(fn($l) => $l && $l->kualitas_data === 'normal')->count(),
            'warning' => collect($latestLogs)->filter(fn($l) => $l && $l->kualitas_data === 'warning')->count(),
            'critical'=> collect($latestLogs)->filter(fn($l) => $l && $l->kualitas_data === 'critical')->count(),
        ];

        // ── Ambil semua aktuator aktif ──────────────────────────────────
        $aktuators = Komponen::where('jenis_komponen', 'aktuator')
            ->where('status', 'active')
            ->get();

        // ── Log terbaru per aktuator ────────────────────────────────────
        $latestAktuatorLogs = [];
        foreach ($aktuators as $aktuator) {
            $latestAktuatorLogs[$aktuator->id] = AktuatorLog::where('id_komponen', $aktuator->id)
                ->latest()
                ->first();
        }

        $aktuatorAktif = collect($latestAktuatorLogs)
            ->filter(fn($l) => $l && $l->status === AktuatorLog::STATUS_ON)
            ->count();

        // ── Notifikasi ──────────────────────────────────────────────────
        $notifications = Notifikasi::latest()->limit(5)->get();
        $notifUnreadCount = Notifikasi::belumDibaca()->count();

        // ── Log hari ini ────────────────────────────────────────────────
        $logHariIni = SensorLog::hariIni()->count();

        // ── Siapkan data untuk JS (sensor dengan tipe, batas, warna) ───
        $sensorsJs = $sensors->map(fn($s) => [
            'id'    => $s->id,
            'tipe'  => strtolower($s->tipe_komponen ?? ''),
            'nama'  => $s->nama_komponen,
            'batas' => json_decode($s->batas_nilai, true) ?? [],
            'satuan'=> $s->satuan,
        ])->values();

        $warnaSensor = $sensors->mapWithKeys(fn($s) => [
            $s->id => match(true) {
                str_contains(strtolower($s->tipe_komponen ?? ''), 'ds18b20') => '#16C47F',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'dht22')   => '#4A8AF0',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'hc')      => '#4AF0C8',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'ph')      => '#F0A84A',
                str_contains(strtolower($s->tipe_komponen ?? ''), 'tds')     => '#A84AF0',
                default                                                        => '#16C47F',
            }
        ]);

        return view('pages.dashboard', compact(
            'sensors',
            'latestLogs',
            'chartData',
            'sensorStats',
            'aktuators',
            'latestAktuatorLogs',
            'aktuatorAktif',
            'notifications',
            'notifUnreadCount',
            'logHariIni',
            'sensorsJs',
            'warnaSensor',
        ));
    }

    public function markAllRead()
    {
        Notifikasi::belumDibaca()->update([
            'sudah_dibaca' => true,
            'dibaca_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function live()
    {
        $sensors = Komponen::where('jenis_komponen', 'sensor')
            ->where('status', 'active')
            ->get();

        $latestLogs = [];
        foreach ($sensors as $sensor) {
            $log = SensorLog::where('id_komponen', $sensor->id)->latest()->first();
            $latestLogs[$sensor->id] = $log ? [
                'nilai'      => $log->nilai,
                'kualitas'   => $log->kualitas_data,
                'updated_at' => $log->created_at->diffForHumans(),
            ] : null;
        }

        $aktuators = Komponen::where('jenis_komponen', 'aktuator')
            ->where('status', 'active')
            ->get();

        $latestAktuatorLogs = [];
        foreach ($aktuators as $aktuator) {
            $log = AktuatorLog::where('id_komponen', $aktuator->id)->latest()->first();
            $latestAktuatorLogs[$aktuator->id] = $log ? [
                'status'   => $log->status,
                'is_on'    => $log->status === AktuatorLog::STATUS_ON,
                'sumber'   => $log->sumber_perintah,
                'durasi'   => $log->durasi_detik,
            ] : null;
        }

        $sensorStats = [
            'normal'  => collect($latestLogs)->filter(fn($l) => $l && $l['kualitas'] === 'normal')->count(),
            'warning' => collect($latestLogs)->filter(fn($l) => $l && $l['kualitas'] === 'warning')->count(),
            'critical'=> collect($latestLogs)->filter(fn($l) => $l && $l['kualitas'] === 'critical')->count(),
        ];

        $aktuatorAktif = collect($latestAktuatorLogs)->filter(fn($l) => $l && $l['is_on'])->count();
        $notifUnreadCount = Notifikasi::belumDibaca()->count();
        $logHariIni = SensorLog::hariIni()->count();

        return response()->json([
            'sensors'          => $latestLogs,
            'aktuators'        => $latestAktuatorLogs,
            'sensor_stats'     => $sensorStats,
            'aktuator_aktif'   => $aktuatorAktif,
            'total_aktuator'   => $aktuators->count(),
            'notif_unread'     => $notifUnreadCount,
            'log_hari_ini'     => $logHariIni,
        ]);
    }
}
