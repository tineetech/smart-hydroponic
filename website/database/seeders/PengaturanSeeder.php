<?php
// database/seeders/PengaturanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── AI Configuration ──────────────────────────────
            [
                'kategori'     => 'ai_config',
                'kunci'        => 'ai_provider',
                'nilai'        => 'gemini',
                'tipe_data'    => 'string',
                'is_encrypted' => false,
                'is_public'    => false,
                'deskripsi'    => 'Provider AI aktif: gemini | openai | claude',
            ],
            [
                'kategori'     => 'ai_config',
                'kunci'        => 'gemini_api_key',
                'nilai'        => null,
                'tipe_data'    => 'string',
                'is_encrypted' => true,
                'is_public'    => false,
                'deskripsi'    => 'API Key Google Gemini',
            ],
            [
                'kategori'     => 'ai_config',
                'kunci'        => 'openai_api_key',
                'nilai'        => null,
                'tipe_data'    => 'string',
                'is_encrypted' => true,
                'is_public'    => false,
                'deskripsi'    => 'API Key OpenAI (ChatGPT)',
            ],
            [
                'kategori'     => 'ai_config',
                'kunci'        => 'claude_api_key',
                'nilai'        => null,
                'tipe_data'    => 'string',
                'is_encrypted' => true,
                'is_public'    => false,
                'deskripsi'    => 'API Key Anthropic Claude',
            ],
            [
                'kategori'     => 'ai_config',
                'kunci'        => 'ai_model',
                'nilai'        => 'gemini-1.5-flash',
                'tipe_data'    => 'string',
                'is_encrypted' => false,
                'is_public'    => false,
                'deskripsi'    => 'Model AI yang digunakan',
            ],

            // ── Lokasi ────────────────────────────────────────
            [
                'kategori'     => 'lokasi',
                'kunci'        => 'lokasi_kota',
                'nilai'        => 'Jakarta',
                'tipe_data'    => 'string',
                'is_encrypted' => false,
                'is_public'    => true,
                'deskripsi'    => 'Kota lokasi instalasi hidroponik',
            ],
            [
                'kategori'     => 'lokasi',
                'kunci'        => 'lokasi_latitude',
                'nilai'        => '-6.2088',
                'tipe_data'    => 'float',
                'is_encrypted' => false,
                'is_public'    => true,
                'deskripsi'    => 'Koordinat latitude',
            ],
            [
                'kategori'     => 'lokasi',
                'kunci'        => 'lokasi_longitude',
                'nilai'        => '106.8456',
                'tipe_data'    => 'float',
                'is_encrypted' => false,
                'is_public'    => true,
                'deskripsi'    => 'Koordinat longitude',
            ],

            // ── Sistem ────────────────────────────────────────
            [
                'kategori'     => 'sistem',
                'kunci'        => 'interval_baca_sensor_detik',
                'nilai'        => '30',
                'tipe_data'    => 'integer',
                'is_encrypted' => false,
                'is_public'    => true,
                'deskripsi'    => 'Interval pembacaan sensor dalam detik',
            ],
            [
                'kategori'     => 'sistem',
                'kunci'        => 'retensi_log_hari',
                'nilai'        => '90',
                'tipe_data'    => 'integer',
                'is_encrypted' => false,
                'is_public'    => false,
                'deskripsi'    => 'Berapa hari log sensor/aktuator disimpan',
            ],
            [
                'kategori'     => 'sistem',
                'kunci'        => 'nama_sistem',
                'nilai'        => 'Smart Hydroponic IoT',
                'tipe_data'    => 'string',
                'is_encrypted' => false,
                'is_public'    => true,
                'deskripsi'    => 'Nama sistem hidroponik',
            ],
            [
                'kategori'     => 'sistem',
                'kunci'        => 'mode_otomatis',
                'nilai'        => 'true',
                'tipe_data'    => 'boolean',
                'is_encrypted' => false,
                'is_public'    => true,
                'deskripsi'    => 'Aktifkan kontrol otomatis aktuator',
            ],

            // ── Notifikasi ────────────────────────────────────
            [
                'kategori'     => 'notifikasi',
                'kunci'        => 'telegram_bot_token',
                'nilai'        => null,
                'tipe_data'    => 'string',
                'is_encrypted' => true,
                'is_public'    => false,
                'deskripsi'    => 'Token bot Telegram untuk notifikasi',
            ],
            [
                'kategori'     => 'notifikasi',
                'kunci'        => 'telegram_chat_id',
                'nilai'        => null,
                'tipe_data'    => 'string',
                'is_encrypted' => false,
                'is_public'    => false,
                'deskripsi'    => 'Chat ID Telegram tujuan notifikasi',
            ],
            [
                'kategori'     => 'notifikasi',
                'kunci'        => 'notifikasi_email',
                'nilai'        => null,
                'tipe_data'    => 'string',
                'is_encrypted' => false,
                'is_public'    => false,
                'deskripsi'    => 'Email tujuan notifikasi alert',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('pengaturan')->updateOrInsert(
                ['kunci' => $setting['kunci']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}