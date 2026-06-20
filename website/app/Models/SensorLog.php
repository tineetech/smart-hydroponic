<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // ── Relasi ────────────────────────────────────────────────────────
    public function komponen(): BelongsTo
    {
        return $this->belongsTo(Komponen::class, 'id_komponen');
    }

    // ── Scope ─────────────────────────────────────────────────────────
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByKomponen($query, int $idKomponen)
    {
        return $query->where('id_komponen', $idKomponen);
    }

    public function scopeByKualitas($query, string $kualitas)
    {
        return $query->where('kualitas_data', $kualitas);
    }
}