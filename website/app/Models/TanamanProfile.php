<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TanamanProfile extends Model
{
    protected $table = 'tanaman_profiles';

    protected $fillable = [
        'nama_tanaman',
        'varietas',
        'lokasi',
        'jumlah',
        'tanggal_tanam',
        'estimasi_panen_hari',
        'fase_pertumbuhan',
        'parameter_ideal',
        'is_aktif',
        'catatan',
    ];

    protected $casts = [
        'parameter_ideal'     => 'array',
        'tanggal_tanam'       => 'date',
        'is_aktif'            => 'boolean',
        'jumlah'              => 'integer',
        'estimasi_panen_hari' => 'integer',
    ];

    // ── Konstanta Enum ────────────────────────────────────────────────
    const FASE_SEMAI       = 'semai';
    const FASE_VEGETATIF   = 'vegetatif';
    const FASE_PEMBUNGAAN  = 'pembungaan';
    const FASE_PEMBUAHAN   = 'pembuahan';
    const FASE_PANEN       = 'panen';

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Hitung umur tanaman dalam hari
     */
    public function getUmurHariAttribute(): ?int
    {
        if (!$this->tanggal_tanam) return null;

        return (int) $this->tanggal_tanam->diffInDays(now());
    }

    /**
     * Hitung sisa hari menuju panen
     */
    public function getSisaHariPanenAttribute(): ?int
    {
        if (!$this->estimasi_panen_hari || !$this->tanggal_tanam) {
            return null;
        }

        $sisa = $this->estimasi_panen_hari - $this->umur_hari;
        return max(0, $sisa);
    }

    /**
     * Hitung persentase pertumbuhan
     */
    public function getPresentaseTumbuhAttribute(): ?float
    {
        if (!$this->estimasi_panen_hari || !$this->tanggal_tanam) {
            return null;
        }

        $persen = ($this->umur_hari / $this->estimasi_panen_hari) * 100;
        return min(100, round($persen, 1));
    }

    /**
     * Cek apakah sudah siap panen
     */
    public function getSiapPanenAttribute(): bool
    {
        return $this->sisa_hari_panen === 0;
    }

    /**
     * Ambil parameter ideal untuk nilai tertentu
     * Contoh: $tanaman->getParameter('ph') => ['min' => 5.5, 'max' => 6.5]
     */
    public function getParameter(string $key): ?array
    {
        return $this->parameter_ideal[$key] ?? null;
    }

    /**
     * Cek apakah nilai sensor sesuai parameter ideal
     * Contoh: $tanaman->isNilaiIdeal('ph', 6.0) => true
     */
    public function isNilaiIdeal(string $key, float $nilai): bool
    {
        $param = $this->getParameter($key);

        if (!$param) return true;

        return $nilai >= ($param['min'] ?? -INF)
            && $nilai <= ($param['max'] ?? INF);
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter tanaman yang aktif
     * Penggunaan: TanamanProfile::aktif()->get()
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Filter berdasarkan fase pertumbuhan
     * Penggunaan: TanamanProfile::dalamFase('vegetatif')->get()
     */
    public function scopeDalamFase(Builder $query, string $fase): Builder
    {
        return $query->where('fase_pertumbuhan', $fase);
    }

    /**
     * Filter berdasarkan lokasi
     */
    public function scopeDiLokasi(Builder $query, string $lokasi): Builder
    {
        return $query->where('lokasi', $lokasi);
    }

    /**
     * Filter yang hampir panen (sisa hari <= n hari)
     * Penggunaan: TanamanProfile::hampirPanen(7)->get()
     */
    public function scopeHampirPanen(Builder $query, int $hari = 7): Builder
    {
        return $query->whereNotNull('tanggal_tanam')
            ->whereNotNull('estimasi_panen_hari')
            ->whereRaw(
                'estimasi_panen_hari - DATEDIFF(NOW(), tanggal_tanam) <= ?',
                [$hari]
            );
    }
}