<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'tipe',
        'level',
        'judul',
        'pesan',
        'id_komponen',
        'data_tambahan',
        'sudah_dibaca',
        'dibaca_at',
        'channel',
        'terkirim',
        'terkirim_at',
    ];

    protected $casts = [
        'data_tambahan' => 'array',
        'sudah_dibaca'  => 'boolean',
        'terkirim'      => 'boolean',
        'dibaca_at'     => 'datetime',
        'terkirim_at'   => 'datetime',
    ];

    // ── Konstanta Enum ────────────────────────────────────────────────
    const TIPE_ALERT    = 'alert';
    const TIPE_INFO     = 'info';
    const TIPE_AI       = 'ai_insight';
    const TIPE_JADWAL   = 'jadwal';
    const TIPE_ERROR    = 'error';

    const LEVEL_LOW      = 'low';
    const LEVEL_MEDIUM   = 'medium';
    const LEVEL_HIGH     = 'high';
    const LEVEL_CRITICAL = 'critical';

    const CHANNEL_DATABASE = 'database';
    const CHANNEL_TELEGRAM = 'telegram';
    const CHANNEL_EMAIL    = 'email';
    const CHANNEL_WHATSAPP = 'whatsapp';

    // ── Relasi ────────────────────────────────────────────────────────

    /**
     * Notifikasi ini terkait satu komponen (opsional)
     */
    public function komponen(): BelongsTo
    {
        return $this->belongsTo(Komponen::class, 'id_komponen');
    }

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Cek apakah notifikasi ini kritis
     */
    public function getIsKritisAttribute(): bool
    {
        return $this->level === self::LEVEL_CRITICAL;
    }

    /**
     * Cek apakah belum dibaca
     */
    public function getBelumDibacaAttribute(): bool
    {
        return !$this->sudah_dibaca;
    }

    // ── Methods ───────────────────────────────────────────────────────

    /**
     * Tandai sebagai sudah dibaca
     * Penggunaan: $notifikasi->tandaiDibaca()
     */
    public function tandaiDibaca(): bool
    {
        return $this->update([
            'sudah_dibaca' => true,
            'dibaca_at'    => now(),
        ]);
    }

    /**
     * Tandai sudah terkirim
     */
    public function tandaiTerkirim(): bool
    {
        return $this->update([
            'terkirim'    => true,
            'terkirim_at' => now(),
        ]);
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter yang belum dibaca
     * Penggunaan: Notifikasi::belumDibaca()->get()
     */
    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('sudah_dibaca', false);
    }

    /**
     * Filter berdasarkan level
     * Penggunaan: Notifikasi::denganLevel('critical')->get()
     */
    public function scopeDenganLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    /**
     * Filter berdasarkan tipe
     * Penggunaan: Notifikasi::denganTipe('alert')->get()
     */
    public function scopeDenganTipe(Builder $query, string $tipe): Builder
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Filter yang belum terkirim
     * Penggunaan: Notifikasi::belumTerkirim()->get()
     */
    public function scopeBelumTerkirim(Builder $query): Builder
    {
        return $query->where('terkirim', false);
    }

    /**
     * Filter hari ini
     */
    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }
}