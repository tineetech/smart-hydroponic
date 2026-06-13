<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class JadwalOtomatis extends Model
{
    protected $table = 'jadwal_otomatis';

    protected $fillable = [
        'nama_jadwal',
        'id_komponen',
        'aksi',
        'nilai_pwm',
        'cron_expression',
        'jam_mulai',
        'jam_selesai',
        'hari_aktif',
        'durasi_menit',
        'is_aktif',
        'catatan',
    ];

    protected $casts = [
        'hari_aktif'   => 'array',
        'is_aktif'     => 'boolean',
        'nilai_pwm'    => 'integer',
        'durasi_menit' => 'integer',
    ];

    // ── Konstanta ─────────────────────────────────────────────────────
    const HARI = [
        'senin', 'selasa', 'rabu',
        'kamis', 'jumat', 'sabtu', 'minggu',
    ];

    const AKSI_ON     = 'on';
    const AKSI_OFF    = 'off';
    const AKSI_TOGGLE = 'toggle';

    // ── Relasi ────────────────────────────────────────────────────────

    /**
     * Jadwal ini untuk satu komponen (aktuator)
     */
    public function komponen(): BelongsTo
    {
        return $this->belongsTo(Komponen::class, 'id_komponen');
    }

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Cek apakah jadwal aktif hari ini
     */
    public function getAktifHariIniAttribute(): bool
    {
        if (!$this->is_aktif) return false;

        $hariIni = strtolower(now()->locale('id')->dayName);

        return in_array('*', $this->hari_aktif ?? [])
            || in_array($hariIni, $this->hari_aktif ?? []);
    }

    /**
     * Cek apakah sekarang dalam rentang waktu jadwal
     */
    public function getSedangAktifAttribute(): bool
    {
        if (!$this->aktif_hari_ini) return false;

        $sekarang = now()->format('H:i:s');

        return $sekarang >= $this->jam_mulai
            && $sekarang <= $this->jam_selesai;
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter jadwal yang aktif
     * Penggunaan: JadwalOtomatis::aktif()->get()
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Filter berdasarkan komponen
     */
    public function scopeUntukKomponen(Builder $query, int $komponenId): Builder
    {
        return $query->where('id_komponen', $komponenId);
    }
}