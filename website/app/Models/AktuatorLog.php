<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AktuatorLog extends Model
{
    protected $table = 'aktuator_logs';

    protected $fillable = [
        'id_komponen',
        'status',
        'durasi_detik',
        'nilai_pwm',
        'sumber_perintah',
        'catatan',
        'triggered_by_log_id',
    ];

    protected $casts = [
        'durasi_detik' => 'integer',
        'nilai_pwm'    => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ── Konstanta Enum ────────────────────────────────────────────────
    const STATUS_ON  = 'on';
    const STATUS_OFF = 'off';

    const SUMBER_MANUAL    = 'manual';
    const SUMBER_OTOMATIS  = 'otomatis';
    const SUMBER_AI        = 'ai';
    const SUMBER_THRESHOLD = 'threshold';

    // ── Relasi ────────────────────────────────────────────────────────

    /**
     * Log ini milik satu komponen (aktuator)
     */
    public function komponen(): BelongsTo
    {
        return $this->belongsTo(Komponen::class, 'id_komponen');
    }

    /**
     * Log ini dipicu oleh sensor log tertentu (jika otomatis)
     */
    public function sensorLogPemicu(): BelongsTo
    {
        return $this->belongsTo(SensorLog::class, 'triggered_by_log_id');
    }

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Cek apakah aktuator sedang ON
     */
    public function getIsOnAttribute(): bool
    {
        return $this->status === self::STATUS_ON;
    }

    /**
     * Format durasi ke menit
     */
    public function getDurasiMenitAttribute(): ?float
    {
        return $this->durasi_detik
            ? round($this->durasi_detik / 60, 2)
            : null;
    }

    /**
     * Cek apakah dijalankan oleh AI
     */
    public function getIsByAiAttribute(): bool
    {
        return $this->sumber_perintah === self::SUMBER_AI;
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter hanya yang ON
     * Penggunaan: AktuatorLog::sedangOn()->get()
     */
    public function scopeSedangOn(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ON);
    }

    /**
     * Filter berdasarkan sumber perintah
     * Penggunaan: AktuatorLog::dariSumber('ai')->get()
     */
    public function scopeDariSumber(Builder $query, string $sumber): Builder
    {
        return $query->where('sumber_perintah', $sumber);
    }

    /**
     * Filter berdasarkan komponen
     */
    public function scopeDariKomponen(Builder $query, int $komponenId): Builder
    {
        return $query->where('id_komponen', $komponenId);
    }

    /**
     * Status terbaru per komponen (untuk dashboard)
     */
    public function scopeTerbaruPerKomponen(Builder $query): Builder
    {
        return $query->whereIn('id', function ($sub) {
            $sub->selectRaw('MAX(id)')
                ->from('aktuator_logs')
                ->groupBy('id_komponen');
        });
    }

    /**
     * Filter hari ini
     */
    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }
}