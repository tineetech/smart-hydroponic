<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
                
        // ── Komponen ───────────────────────────────────────────────────
        $komponen = [

            // ── SENSOR ──────────────────────────────────────
            [
                'id'             => 4,
                'nama_komponen'  => 'DS18B20 Suhu Air',
                'jenis_komponen' => 'sensor',
                'tipe_komponen'  => 'DS18B20',
                'pin_data'       => json_encode(['pin' => 4]),
                'satuan'         => '°C',
                'batas_nilai'    => json_encode([
                    'min'          => 15,
                    'max'          => 35,
                    'optimal_min'  => 20,
                    'optimal_max'  => 28,
                ]),
                'lokasi'         => 'Tangki Nutrisi A',
                'protokol'       => 'OneWire',
                'deskripsi'      => 'Sensor suhu air larutan nutrisi menggunakan protokol OneWire.',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 5,
                'nama_komponen'  => 'DHT22 Suhu Udara',
                'jenis_komponen' => 'sensor',
                'tipe_komponen'  => 'DHT22',
                'pin_data'       => json_encode(['pin' => 5]),
                'satuan'         => '°C / %',
                'batas_nilai'    => json_encode([
                    'min'          => 18,
                    'max'          => 38,
                    'optimal_min'  => 22,
                    'optimal_max'  => 32,
                ]),
                'lokasi'         => 'Greenhouse A',
                'protokol'       => 'Digital',
                'deskripsi'      => 'Sensor suhu & kelembapan udara di dalam greenhouse.',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 6,
                'nama_komponen'  => 'HC-SR04 Level Air',
                'jenis_komponen' => 'sensor',
                'tipe_komponen'  => 'HC-SR04',
                'pin_data'       => json_encode(['trig' => 12, 'echo' => 13]),
                'satuan'         => '%',
                'batas_nilai'    => json_encode([
                    'min'          => 20,
                    'max'          => 100,
                    'optimal_min'  => 40,
                    'optimal_max'  => 90,
                ]),
                'lokasi'         => 'Tangki Nutrisi A',
                'protokol'       => 'Digital',
                'deskripsi'      => 'Sensor ultrasonik untuk mengukur level ketinggian air dalam tangki.',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 7,
                'nama_komponen'  => 'Probe pH Larutan',
                'jenis_komponen' => 'sensor',
                'tipe_komponen'  => 'pH Probe',
                'pin_data'       => json_encode(['pin' => 34]),
                'satuan'         => 'pH',
                'batas_nilai'    => json_encode([
                    'min'          => 4.0,
                    'max'          => 9.0,
                    'optimal_min'  => 5.5,
                    'optimal_max'  => 7.0,
                ]),
                'lokasi'         => 'Tangki Nutrisi A',
                'protokol'       => 'Analog',
                'deskripsi'      => 'Probe pengukur kadar pH larutan nutrisi hidroponik.',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 8,
                'nama_komponen'  => 'EC/TDS Probe',
                'jenis_komponen' => 'sensor',
                'tipe_komponen'  => 'TDS Meter',
                'pin_data'       => json_encode(['pin' => 35]),
                'satuan'         => 'ppm',
                'batas_nilai'    => json_encode([
                    'min'          => 400,
                    'max'          => 2500,
                    'optimal_min'  => 800,
                    'optimal_max'  => 1600,
                ]),
                'lokasi'         => 'Tangki Nutrisi A',
                'protokol'       => 'Analog',
                'deskripsi'      => 'Probe untuk mengukur konsentrasi nutrisi dalam larutan (TDS/EC).',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // ── AKTUATOR ─────────────────────────────────────
            [
                'id'             => 9,
                'nama_komponen'  => 'SSR Pompa Utama',
                'jenis_komponen' => 'aktuator',
                'tipe_komponen'  => 'SSR 25DA',
                'pin_data'       => json_encode(['pin' => 26, 'active_low' => false]),
                'satuan'         => null,
                'batas_nilai'    => null,
                'lokasi'         => 'Panel Kontrol',
                'protokol'       => 'Digital',
                'deskripsi'      => 'Solid State Relay untuk mengontrol pompa sirkulasi nutrisi utama.',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 10,
                'nama_komponen'  => 'Motor pH Down',
                'jenis_komponen' => 'aktuator',
                'tipe_komponen'  => 'L298N',
                'pin_data'       => json_encode(['in1' => 27, 'in2' => 28, 'ena' => 29]),
                'satuan'         => null,
                'batas_nilai'    => null,
                'lokasi'         => 'Panel Kontrol',
                'protokol'       => 'PWM',
                'deskripsi'      => 'Motor dosing pump untuk menurunkan kadar pH larutan nutrisi.',
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 11,
                'nama_komponen'  => 'Motor pH Up',
                'jenis_komponen' => 'aktuator',
                'tipe_komponen'  => 'L298N',
                'pin_data'       => json_encode(['in1' => 30, 'in2' => 31, 'ena' => 32]),
                'satuan'         => null,
                'batas_nilai'    => null,
                'lokasi'         => 'Panel Kontrol',
                'protokol'       => 'PWM',
                'deskripsi'      => 'Motor dosing pump untuk menaikkan kadar pH larutan nutrisi.',
                'status'         => 'non-active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('komponen')->insert($komponen);
    }
}
