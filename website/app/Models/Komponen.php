<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Komponen extends Model
{
    protected $table = 'komponen';

    protected $fillable = [
        'nama_komponen',
        'jenis_komponen',
        'tipe_komponen',
        'pin_data',
        'satuan',
        'batas_nilai',
        'lokasi',
        'protokol',
        'deskripsi',
        'status',
    ];


    // ── Konstanta Enum ────────────────────────────────────────────────
    const JENIS_SENSOR   = 'sensor';
    const JENIS_AKTUATOR = 'aktuator';

    const STATUS_ACTIVE     = 'active';
    const STATUS_NON_ACTIVE = 'non-active';

    // ── Relasi ────────────────────────────────────────────────────────

    /**
     * Komponen memiliki banyak sensor log
     * (hanya relevan jika jenis_komponen = 'sensor')
     */
    public function sensorLogs(): HasMany
    {
        return $this->hasMany(SensorLog::class, 'id_komponen');
    }

    /**
     * Komponen memiliki banyak aktuator log
     * (hanya relevan jika jenis_komponen = 'aktuator')
     */
    public function aktuatorLogs(): HasMany
    {
        return $this->hasMany(AktuatorLog::class, 'id_komponen');
    }

    /**
     * Komponen memiliki banyak jadwal otomatis
     */
    public function jadwalOtomatis(): HasMany
    {
        return $this->hasMany(JadwalOtomatis::class, 'id_komponen');
    }

    /**
     * Komponen memiliki banyak notifikasi
     */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_komponen');
    }

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Ambil log sensor terbaru
     */
    public function getLogTerbaruAttribute(): ?SensorLog
    {
        return $this->sensorLogs()->latest()->first();
    }

    /**
     * Ambil status aktuator saat ini (on/off)
     */
    public function getStatusAktuatorAttribute(): string
    {
        $log = $this->aktuatorLogs()->latest()->first();
        return $log ? $log->status : 'off';
    }

    /**
     * Cek apakah komponen ini adalah sensor
     */
    public function getIsSensorAttribute(): bool
    {
        return $this->jenis_komponen === self::JENIS_SENSOR;
    }

    /**
     * Cek apakah komponen ini adalah aktuator
     */
    public function getIsAktuatorAttribute(): bool
    {
        return $this->jenis_komponen === self::JENIS_AKTUATOR;
    }

    /**
     * Cek apakah komponen aktif
     */
    public function getIsAktifAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter hanya sensor
     * Penggunaan: Komponen::sensor()->get()
     */
    public function scopeSensor(Builder $query): Builder
    {
        return $query->where('jenis_komponen', self::JENIS_SENSOR);
    }

    /**
     * Filter hanya aktuator
     * Penggunaan: Komponen::aktuator()->get()
     */
    public function scopeAktuator(Builder $query): Builder
    {
        return $query->where('jenis_komponen', self::JENIS_AKTUATOR);
    }

    /**
     * Filter hanya yang aktif
     * Penggunaan: Komponen::aktif()->get()
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Filter berdasarkan lokasi
     * Penggunaan: Komponen::diLokasi('Nutrient Tank A')->get()
     */
    public function scopeDiLokasi(Builder $query, string $lokasi): Builder
    {
        return $query->where('lokasi', $lokasi);
    }
}