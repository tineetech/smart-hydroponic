<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Pengaturan::orderBy('kategori')->orderBy('id')->get()->groupBy('kategori');

        $kategoriLabels = [
            'ai_config'   => 'Konfigurasi AI',
            'lokasi'      => 'Lokasi',
            'sistem'      => 'Sistem',
            'notifikasi'  => 'Notifikasi',
        ];

        return view('pages.pengaturan.index', compact('settings', 'kategoriLabels'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $updated = 0;
        foreach ($request->settings as $kunci => $nilai) {
            $setting = Pengaturan::where('kunci', $kunci)->first();
            if (!$setting) continue;

            $nilaiSimpan = match ($setting->tipe_data) {
                'boolean' => filter_var($nilai, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
                'integer' => (int) $nilai,
                'float'   => (float) $nilai,
                'json'    => is_string($nilai) ? $nilai : json_encode($nilai),
                default   => (string) $nilai,
            };

            if ($setting->is_encrypted && !empty($nilaiSimpan)) {
                $nilaiSimpan = Crypt::encryptString((string) $nilaiSimpan);
            }

            $setting->update(['nilai' => (string) $nilaiSimpan]);
            $updated++;
        }

        return redirect()->route('admin.pengaturan.index')
            ->with('toast_success', "{$updated} pengaturan berhasil disimpan.");
    }
}
