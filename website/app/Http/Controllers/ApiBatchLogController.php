<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AktuatorLog;
use App\Models\Komponen;
use App\Models\SensorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiBatchLogController extends Controller
{
    /**
     * Skema key nilai yang WAJIB ada per tipe_komponen sensor,
     * mengikuti standar yang dipakai SensorLogSeeder.
     *
     * - Sensor dengan 1 key: boleh dikirim sebagai angka tunggal,
     *   akan otomatis dibungkus ke key yang sesuai.
     * - Sensor dengan >1 key: WAJIB dikirim sebagai object lengkap
     *   berisi seluruh key yang terdaftar, persis namanya.
     */
    private const SKEMA_NILAI_SENSOR = [
        'DS18B20'   => ['suhu_air'],
        'DHT22'     => ['suhu', 'kelembapan'],
        'HC-SR04'   => ['level', 'jarak_cm'],
        'pH Probe'  => ['ph'],
        'TDS Meter' => ['tds', 'ec'],
    ];

    /**
     * Simpan banyak log sensor & aktuator sekaligus dalam 1x request.
     *
     * Nilai sensor mengikuti skema standar SensorLogSeeder (lihat SKEMA_NILAI_SENSOR):
     * - DS18B20   (1 nilai)  -> boleh angka tunggal, otomatis jadi {"suhu_air": ...}
     * - pH Probe  (1 nilai)  -> boleh angka tunggal, otomatis jadi {"ph": ...}
     * - DHT22     (2 nilai)  -> WAJIB object {"suhu": ..., "kelembapan": ...}
     * - HC-SR04   (2 nilai)  -> WAJIB object {"level": ..., "jarak_cm": ...}
     * - TDS Meter (2 nilai)  -> WAJIB object {"tds": ..., "ec": ...}
     *
     * Contoh body request:
     * {
     *   "sensor": {
     *     "data": {
     *       "DS18B20 Suhu Air": {
     *         "nilai": 28.5,
     *         "kualitas_data": "normal",
     *         "sudah_diproses": false
     *       },
     *       "DHT22 Suhu Udara": {
     *         "nilai": {"suhu": 30.2, "kelembapan": 65},
     *         "kualitas_data": "warning"
     *       },
     *       "HC-SR04 Level Air": {
     *         "nilai": {"level": 75, "jarak_cm": 10.5},
     *         "kualitas_data": "normal"
     *       },
     *       "Probe pH Larutan": {
     *         "nilai": 6.5,
     *         "kualitas_data": "normal"
     *       },
     *       "EC/TDS Probe": {
     *         "nilai": {"tds": 1180, "ec": 2.36},
     *         "kualitas_data": "normal"
     *       }
     *     }
     *   },
     *   "aktuator": {
     *     "data": {
     *       "SSR Pompa Utama": "on",
     *       "Motor pH Down": {
     *         "status": "on",
     *         "durasi_detik": 5,
     *         "nilai_pwm": 200,
     *         "sumber_perintah": "threshold",
     *         "catatan": "pH terlalu tinggi, pompa pH down dinyalakan"
     *       }
     *     }
     *   }
     * }
     *
     * Catatan:
     * - "sensor" dan "aktuator" sama-sama opsional, tapi minimal salah satu harus ada.
     * - Setiap item sensor WAJIB punya "nilai" dan "kualitas_data" sendiri-sendiri
     *   (tidak ada lagi kualitas_data global untuk semua sensor).
     * - Key nilai sensor harus PERSIS sesuai SKEMA_NILAI_SENSOR; key asing ditolak,
     *   key wajib yang hilang (untuk sensor 2-nilai) juga ditolak — tidak ditebak/dikosongkan.
     * - Untuk aktuator, nilai boleh berupa string sederhana "on"/"off" (disetarakan
     *   dengan field `status`), atau object lengkap mengikuti kolom tabel aktuator_logs.
     * - triggered_by_log_id TIDAK didukung lewat endpoint ini (selalu null).
     */
    public function store(Request $request)
    {
        // ── Validasi struktur umum ─────────────────────────────────────
        $validated = $request->validate([
            'sensor'         => 'required_without:aktuator|array',
            'sensor.data'    => 'required_with:sensor|array|min:1',

            'aktuator'       => 'required_without:sensor|array',
            'aktuator.data'  => 'required_with:aktuator|array|min:1',
        ]);

        $hasilSukses = [];
        $hasilGagal  = [];

        // ── Kumpulkan semua nama komponen yang dirujuk (sensor + aktuator) ──
        $namaSensor   = array_keys($validated['sensor']['data']   ?? []);
        $namaAktuator = array_keys($validated['aktuator']['data'] ?? []);
        $semuaNama    = array_unique(array_merge($namaSensor, $namaAktuator));

        $komponenList = Komponen::where(function ($query) use ($semuaNama) {
                foreach ($semuaNama as $nama) {
                    $query->orWhere('nama_komponen', 'like', '%' . $nama . '%');
                }
            })
            ->whereIn('jenis_komponen', ['sensor', 'aktuator'])
            ->get();

        $cariKomponen = function (string $nama, string $jenis) use ($komponenList) {
            return $komponenList->first(fn($k) => $k->jenis_komponen === $jenis && $k->nama_komponen === $nama)
                ?? $komponenList->first(fn($k) => $k->jenis_komponen === $jenis && stripos($k->nama_komponen, $nama) !== false);
        };

        /**
         * Normalisasi & validasi nilai sensor sesuai SKEMA_NILAI_SENSOR
         * berdasarkan tipe_komponen. Mengembalikan ['nilai' => array] jika valid,
         * atau ['error' => string] jika tidak sesuai skema.
         */
        $normalisasiNilaiSensor = function ($nilaiMentah, string $tipeKomponen) {
            $skema = self::SKEMA_NILAI_SENSOR[$tipeKomponen] ?? null;

            // Tipe komponen tidak dikenal skemanya -> tolak, jangan terka-terka
            if ($skema === null) {
                return ['error' => "Tipe komponen '{$tipeKomponen}' belum punya skema nilai standar."];
            }

            // ── Sensor dengan 1 key: boleh angka tunggal ─────────────
            if (count($skema) === 1) {
                $key = $skema[0];

                if (is_array($nilaiMentah)) {
                    if (!array_key_exists($key, $nilaiMentah)) {
                        return ['error' => "Object nilai harus berisi key '{$key}'."];
                    }
                    $ekstra = array_diff(array_keys($nilaiMentah), $skema);
                    if (!empty($ekstra)) {
                        return ['error' => 'Key tidak dikenal: ' . implode(', ', $ekstra) . ". Hanya '{$key}' yang diizinkan."];
                    }
                    return ['nilai' => [$key => $nilaiMentah[$key]]];
                }

                // Angka/string tunggal -> bungkus otomatis ke key yang sesuai
                return ['nilai' => [$key => $nilaiMentah]];
            }

            // ── Sensor dengan >1 key: wajib object lengkap, key persis ──
            if (!is_array($nilaiMentah)) {
                return ['error' => 'Sensor ini butuh ' . count($skema) . ' nilai (' . implode(', ', $skema) . '), wajib kirim object lengkap, bukan nilai tunggal.'];
            }

            $hilang = array_diff($skema, array_keys($nilaiMentah));
            if (!empty($hilang)) {
                return ['error' => 'Key wajib belum lengkap: ' . implode(', ', $hilang)];
            }

            $ekstra = array_diff(array_keys($nilaiMentah), $skema);
            if (!empty($ekstra)) {
                return ['error' => 'Key tidak dikenal: ' . implode(', ', $ekstra) . '. Hanya (' . implode(', ', $skema) . ') yang diizinkan.'];
            }

            // Susun ulang sesuai urutan skema, drop key liar (sudah divalidasi di atas)
            $nilaiBersih = [];
            foreach ($skema as $key) {
                $nilaiBersih[$key] = $nilaiMentah[$key];
            }

            return ['nilai' => $nilaiBersih];
        };

        DB::beginTransaction();

        try {
            // ── Proses SENSOR ────────────────────────────────────────
            if (!empty($validated['sensor']['data'])) {
                foreach ($validated['sensor']['data'] as $namaKomponen => $itemMentah) {
                    $komponen = $cariKomponen($namaKomponen, 'sensor');

                    if (!$komponen) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'sensor',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => "Komponen sensor '{$namaKomponen}' tidak ditemukan.",
                        ];
                        continue;
                    }

                    // ── Setiap item sensor wajib object berisi nilai & kualitas_data sendiri ──
                    if (!is_array($itemMentah)) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'sensor',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => "Item sensor '{$namaKomponen}' harus berupa object berisi 'nilai' dan 'kualitas_data'.",
                        ];
                        continue;
                    }

                    $itemValidator = Validator::make($itemMentah, [
                        'nilai'          => 'required',
                        'kualitas_data'  => 'required|in:normal,warning,critical,error',
                        'sudah_diproses' => 'nullable|in:0,1,true,false',
                    ]);

                    if ($itemValidator->fails()) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'sensor',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => 'Payload tidak valid: ' . $itemValidator->errors()->first(),
                        ];
                        continue;
                    }

                    $dataValid     = $itemValidator->validated();
                    $sudahDiproses = in_array($dataValid['sudah_diproses'] ?? null, [1, '1', 'true', true], true);

                    // ── Normalisasi nilai sesuai skema tipe_komponen (standar seeder) ──
                    $hasilNilai = $normalisasiNilaiSensor($dataValid['nilai'], $komponen->tipe_komponen);

                    if (isset($hasilNilai['error'])) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'sensor',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => "Nilai tidak sesuai skema '{$komponen->tipe_komponen}': " . $hasilNilai['error'],
                        ];
                        continue;
                    }

                    $nilai = $hasilNilai['nilai'];

                    try {
                        $log = SensorLog::create([
                            'id_komponen'    => $komponen->id,
                            'nilai'          => $nilai,
                            'kualitas_data'  => $dataValid['kualitas_data'],
                            'sudah_diproses' => $sudahDiproses,
                        ]);

                        $hasilSukses[] = [
                            'jenis_komponen' => 'sensor',
                            'id'             => $log->id,
                            'id_komponen'    => $komponen->id,
                            'nama_komponen'  => $komponen->nama_komponen,
                            'tipe_komponen'  => $komponen->tipe_komponen,
                            'nilai'          => $log->nilai,
                            'kualitas_data'  => $log->kualitas_data,
                            'created_at'     => $log->created_at->format('Y-m-d H:i:s'),
                        ];
                    } catch (\Throwable $e) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'sensor',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => 'Gagal menyimpan sensor log: ' . $e->getMessage(),
                        ];
                    }
                }
            }

            // ── Proses AKTUATOR ──────────────────────────────────────
            if (!empty($validated['aktuator']['data'])) {
                foreach ($validated['aktuator']['data'] as $namaKomponen => $nilaiMentah) {
                    $komponen = $cariKomponen($namaKomponen, 'aktuator');

                    if (!$komponen) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'aktuator',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => "Komponen aktuator '{$namaKomponen}' tidak ditemukan.",
                        ];
                        continue;
                    }

                    // ── Normalisasi: string "on"/"off" -> object {status: ...} ──
                    $payloadAktuator = is_array($nilaiMentah)
                        ? $nilaiMentah
                        : ['status' => $nilaiMentah];

                    // Samakan status jadi lowercase ('ON' -> 'on')
                    if (isset($payloadAktuator['status'])) {
                        $payloadAktuator['status'] = strtolower($payloadAktuator['status']);
                    }

                    // ── Validasi field per item aktuator ─────────────
                    $itemValidator = Validator::make($payloadAktuator, [
                        'status'          => 'required|in:on,off',
                        'durasi_detik'    => 'nullable|integer|min:0',
                        'nilai_pwm'       => 'nullable|integer|min:0|max:255',
                        'sumber_perintah' => 'nullable|in:manual,otomatis,ai,threshold',
                        'catatan'         => 'nullable|string',
                    ]);

                    if ($itemValidator->fails()) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'aktuator',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => 'Payload tidak valid: ' . $itemValidator->errors()->first(),
                        ];
                        continue;
                    }

                    $dataValid = $itemValidator->validated();

                    try {
                        $log = AktuatorLog::create([
                            'id_komponen'         => $komponen->id,
                            'status'              => $dataValid['status'],
                            'durasi_detik'        => $dataValid['durasi_detik']    ?? null,
                            'nilai_pwm'           => $dataValid['nilai_pwm']       ?? null,
                            'sumber_perintah'     => $dataValid['sumber_perintah'] ?? AktuatorLog::SUMBER_MANUAL,
                            'catatan'             => $dataValid['catatan']         ?? null,
                            'triggered_by_log_id' => null, // tidak didukung lewat batch endpoint ini
                        ]);

                        $hasilSukses[] = [
                            'jenis_komponen'  => 'aktuator',
                            'id'              => $log->id,
                            'id_komponen'     => $komponen->id,
                            'nama_komponen'   => $komponen->nama_komponen,
                            'tipe_komponen'   => $komponen->tipe_komponen,
                            'status'          => $log->status,
                            'durasi_detik'    => $log->durasi_detik,
                            'nilai_pwm'       => $log->nilai_pwm,
                            'sumber_perintah' => $log->sumber_perintah,
                            'created_at'      => $log->created_at->format('Y-m-d H:i:s'),
                        ];
                    } catch (\Throwable $e) {
                        $hasilGagal[] = [
                            'jenis_komponen' => 'aktuator',
                            'nama_komponen'  => $namaKomponen,
                            'message'        => 'Gagal menyimpan aktuator log: ' . $e->getMessage(),
                        ];
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses batch log.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => count($hasilGagal) === 0,
            'message' => sprintf(
                'Selesai diproses: %d berhasil, %d gagal.',
                count($hasilSukses),
                count($hasilGagal)
            ),
            'data' => [
                'berhasil' => $hasilSukses,
                'gagal'    => $hasilGagal,
            ],
        ], count($hasilGagal) === 0 ? 201 : 207); // 207 Multi-Status kalau ada yang gagal sebagian
    }
}