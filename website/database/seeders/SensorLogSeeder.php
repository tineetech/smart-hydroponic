<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorLogSeeder extends Seeder
{
    /**
     * Konfigurasi sensor — sesuai dengan AllSeeder
     */
    private array $sensors = [
        4 => [
            'tipe'  => 'ds18b20',
            'batas' => ['min' => 15, 'max' => 35, 'optimal_min' => 20, 'optimal_max' => 28],
        ],
        5 => [
            'tipe'  => 'dht22',
            'batas' => ['min' => 18, 'max' => 38, 'optimal_min' => 22, 'optimal_max' => 32],
        ],
        6 => [
            'tipe'  => 'hcsr04',
            'batas' => ['min' => 20, 'max' => 100, 'optimal_min' => 40, 'optimal_max' => 90],
        ],
        7 => [
            'tipe'  => 'ph',
            'batas' => ['min' => 4.0, 'max' => 9.0, 'optimal_min' => 5.5, 'optimal_max' => 7.0],
        ],
        8 => [
            'tipe'  => 'tds',
            'batas' => ['min' => 400, 'max' => 2500, 'optimal_min' => 800, 'optimal_max' => 1600],
        ],
    ];

    public function run(): void
    {
        $rows   = [];
        $now    = Carbon::now();
        // 40 log per sensor: 30 data historis (tiap 30 menit) + 10 hari ini (tiap 5 menit)
        $totalHistoris = 30;
        $totalHariIni  = 10;

        foreach ($this->sensors as $idKomponen => $config) {
            // ── Data historis (30 titik, mundur dari 15 jam lalu) ──────
            for ($i = $totalHistoris; $i >= 1; $i--) {
                $waktu  = $now->copy()->subMinutes($i * 30);
                $nilai  = $this->generateNilai($config['tipe'], $config['batas'], $i);
                $kualitas = $this->hitungKualitas($nilai, $config['tipe'], $config['batas']);

                $rows[] = [
                    'id_komponen'    => $idKomponen,
                    'nilai'          => json_encode($nilai),
                    'kualitas_data'  => $kualitas,
                    'sudah_diproses' => true,
                    'created_at'     => $waktu,
                    'updated_at'     => $waktu,
                ];
            }

            // ── Data hari ini (10 titik, tiap 5 menit terakhir) ───────
            for ($j = $totalHariIni; $j >= 1; $j--) {
                $waktu  = $now->copy()->subMinutes($j * 5);
                $nilai  = $this->generateNilai($config['tipe'], $config['batas'], $j);
                $kualitas = $this->hitungKualitas($nilai, $config['tipe'], $config['batas']);

                $rows[] = [
                    'id_komponen'    => $idKomponen,
                    'nilai'          => json_encode($nilai),
                    'kualitas_data'  => $kualitas,
                    'sudah_diproses' => $j > 2, // 2 data terbaru belum diproses
                    'created_at'     => $waktu,
                    'updated_at'     => $waktu,
                ];
            }
        }

        // Insert dalam batch agar tidak timeout
        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('sensor_logs')->insert($chunk);
        }

    }

    // ── Generator nilai per tipe sensor ───────────────────────────────

    private function generateNilai(string $tipe, array $batas, int $seed): array
    {
        // Variasi kecil berbasis seed agar data terlihat natural (bukan acak murni)
        $jitter = sin($seed * 1.3) * 0.5; // -0.5 s.d. +0.5, deterministik

        return match ($tipe) {
            'ds18b20' => [
                'suhu_air' => round($this->rentang($batas, $jitter, pct: 0.6), 1),
            ],
            'dht22' => [
                'suhu'       => round($this->rentang($batas, $jitter, pct: 0.55), 1),
                'kelembapan' => round(65 + $jitter * 12, 1),
            ],
            'hcsr04' => [
                'level'    => round($this->rentang($batas, $jitter, pct: 0.65), 1),
                'jarak_cm' => round(20 - ($this->rentang($batas, $jitter, pct: 0.65) / 100 * 15), 1),
            ],
            'ph' => [
                'ph' => round($this->rentang($batas, $jitter, pct: 0.6), 2),
            ],
            'tds' => [
                'tds' => round($this->rentang($batas, $jitter, pct: 0.6)),
                'ec'  => round($this->rentang($batas, $jitter, pct: 0.6) / 500, 2),
            ],
            default => [],
        };
    }

    /**
     * Hasilkan nilai dalam rentang optimal ± jitter.
     * pct: seberapa jauh nilai bergerak relatif terhadap rentang optimal.
     */
    private function rentang(array $batas, float $jitter, float $pct): float
    {
        $optMin = $batas['optimal_min'];
        $optMax = $batas['optimal_max'];
        $tengah = ($optMin + $optMax) / 2;
        $delta  = ($optMax - $optMin) * $pct / 2;

        return $tengah + ($jitter * $delta);
    }

    // ── Penentuan kualitas berdasarkan batas_nilai ─────────────────────

    private function hitungKualitas(array $nilai, string $tipe, array $batas): string
    {
        $nilaiUtama = match ($tipe) {
            'ds18b20' => $nilai['suhu_air']  ?? null,
            'dht22'   => $nilai['suhu']      ?? null,
            'hcsr04'  => $nilai['level']     ?? null,
            'ph'      => $nilai['ph']        ?? null,
            'tds'     => $nilai['tds']       ?? null,
            default   => null,
        };

        if ($nilaiUtama === null) {
            return 'error';
        }

        $min    = $batas['min'];
        $max    = $batas['max'];
        $optMin = $batas['optimal_min'];
        $optMax = $batas['optimal_max'];

        if ($nilaiUtama < $min || $nilaiUtama > $max) {
            return 'critical';
        }

        if ($nilaiUtama < $optMin || $nilaiUtama > $optMax) {
            return 'warning';
        }

        return 'normal';
    }
}