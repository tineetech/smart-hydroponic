<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'kategori',
        'kunci',
        'nilai',
        'tipe_data',
        'is_encrypted',
        'is_public',
        'deskripsi',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_public'    => 'boolean',
    ];

    // ── Konstanta Kategori ────────────────────────────────────────────
    const KATEGORI_AI           = 'ai_config';
    const KATEGORI_LOKASI       = 'lokasi';
    const KATEGORI_SISTEM       = 'sistem';
    const KATEGORI_NOTIFIKASI   = 'notifikasi';

    // ── Static Helper Methods ─────────────────────────────────────────

    /**
     * Ambil nilai setting berdasarkan kunci
     * Penggunaan: Pengaturan::get('gemini_api_key')
     */
    public static function get(string $kunci, mixed $default = null): mixed
    {
        $setting = static::where('kunci', $kunci)->first();

        if (!$setting) return $default;

        return $setting->nilai_decoded;
    }

    /**
     * Set/update nilai setting
     * Penggunaan: Pengaturan::set('mode_otomatis', true)
     */
    public static function set(string $kunci, mixed $nilai): bool
    {
        $setting = static::where('kunci', $kunci)->first();

        if (!$setting) return false;

        // Enkripsi jika diperlukan
        $nilaiSimpan = $setting->is_encrypted
            ? Crypt::encryptString((string) $nilai)
            : (string) $nilai;

        return $setting->update(['nilai' => $nilaiSimpan]);
    }

    /**
     * Ambil semua setting dalam satu kategori sebagai array
     * Penggunaan: Pengaturan::kategori('ai_config')
     */
    public static function kategori(string $kategori): array
    {
        return static::where('kategori', $kategori)
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->kunci => $item->nilai_decoded
            ])
            ->toArray();
    }

    // ── Accessor ──────────────────────────────────────────────────────

    /**
     * Ambil nilai dengan casting tipe data yang sesuai
     * dan dekripsi jika perlu
     */
    public function getNilaiDecodedAttribute(): mixed
    {
        $nilai = $this->nilai;

        if (is_null($nilai)) return null;

        // Dekripsi jika terenkripsi
        if ($this->is_encrypted) {
            try {
                $nilai = Crypt::decryptString($nilai);
            } catch (\Exception $e) {
                return null;
            }
        }

        // Cast berdasarkan tipe_data
        return match ($this->tipe_data) {
            'integer' => (int) $nilai,
            'float'   => (float) $nilai,
            'boolean' => filter_var($nilai, FILTER_VALIDATE_BOOLEAN),
            'json'    => json_decode($nilai, true),
            default   => $nilai,
        };
    }

    // ── Local Scope ───────────────────────────────────────────────────

    /**
     * Filter berdasarkan kategori
     * Penggunaan: Pengaturan::dariKategori('ai_config')->get()
     */
    public function scopeDariKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Filter hanya setting yang publik
     * Penggunaan: Pengaturan::publik()->get()
     */
    public function scopePublik(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }
}