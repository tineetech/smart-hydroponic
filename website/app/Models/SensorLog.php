<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class SensorLog extends Model
{
    protected $table = 'sensor_logs';

    protected $fillable = [
        'id_komponen',
        'nilai',
        'kualitas_data',
        'sudah_diproses',
    ];

    protected $casts = [
        'nilai'          => 'array',
        'sudah_diproses' => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ── Konstanta Enum ────────────────────────────────────────────────
    const KUALITAS_NORMAL   = 'normal';
    const KUALITAS_WARNING  = 'warning';
    const KUALITAS_CRITICAL = 'critical';
    const KUALITAS_ERROR    = 'error';

    // ── Relasi ────────────────────────────────────────────────────────

    /**
     * Log ini milik satu komponen (sensor)
     */
    public function komponen(): BelongsTo
    {
        return $this->belongsTo(Komponen::class, 'id_komponen');
    }

    /**
     * Log sensor ini bisa memicu banyak aktuator log
     */
    public function aktuatorLogs(): HasMany
    {
        return $this->hasMany(AktuatorLog::class, 'triggered_by_log_id');
    }

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Ambil nilai spesifik dari JSON
     * Contoh: $log->getNilai('suhu') => 28.5
     */
    public function getNilai(string $key): mixed
    {
        return $this->nilai[$key] ?? null;
    }

    /**
     * Cek apakah data dalam kondisi bahaya
     */
    public function getIsBahayaAttribute(): bool
    {
        return in_array($this->kualitas_data, [
            self::KUALITAS_CRITICAL,
            self::KUALITAS_ERROR,
        ]);
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter berdasarkan komponen tertentu
     * Penggunaan: SensorLog::dariKomponen(1)->get()
     */
    public function scopeDariKomponen(Builder $query, int $komponenId): Builder
    {
        return $query->where('id_komponen', $komponenId);
    }

    /**
     * Filter berdasarkan kualitas data
     * Penggunaan: SensorLog::denganKualitas('warning')->get()
     */
    public function scopeDenganKualitas(Builder $query, string $kualitas): Builder
    {
        return $query->where('kualitas_data', $kualitas);
    }

    /**
     * Filter yang belum diproses AI
     * Penggunaan: SensorLog::belumDiproses()->get()
     */
    public function scopeBelumDiproses(Builder $query): Builder
    {
        return $query->where('sudah_diproses', false);
    }

    /**
     * Filter berdasarkan rentang waktu
     * Penggunaan: SensorLog::dalamRentang('2024-01-01', '2024-01-31')->get()
     */
    public function scopeDalamRentang(
        Builder $query,
        string $dari,
        string $sampai
    ): Builder {
        return $query->whereBetween('created_at', [$dari, $sampai]);
    }

    /**
     * Filter data hari ini
     * Penggunaan: SensorLog::hariIni()->get()
     */
    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Data terbaru per komponen (untuk dashboard)
     * Penggunaan: SensorLog::terbaruPerKomponen()->get()
     */
    public function scopeTerbaruPerKomponen(Builder $query): Builder
    {
        return $query->whereIn('id', function ($sub) {
            $sub->selectRaw('MAX(id)')
                ->from('sensor_logs')
                ->groupBy('id_komponen');
        });
    }
}