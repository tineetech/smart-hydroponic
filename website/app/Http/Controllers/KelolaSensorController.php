<?php

namespace App\Http\Controllers;

use App\Models\Komponen;
use Illuminate\Http\Request;

class KelolaSensorController extends Controller
{
    public function index()
    {
        $komponen = Komponen::orderBy('id')->get();

        // Siapkan data JS di controller — bukan di blade
        $komponenJson = $komponen->map(function ($k) {
            return [
                'id'             => $k->id,
                'nama_komponen'  => $k->nama_komponen,
                'jenis_komponen' => $k->jenis_komponen,
                'tipe_komponen'  => $k->tipe_komponen,
                'pin_data'       => is_array($k->pin_data)
                                        ? json_encode($k->pin_data)
                                        : $k->pin_data,
                'satuan'         => $k->satuan,
                'batas_nilai'    => is_array($k->batas_nilai)
                                        ? json_encode($k->batas_nilai)
                                        : $k->batas_nilai,
                'lokasi'         => $k->lokasi,
                'protokol'       => $k->protokol,
                'deskripsi'      => $k->deskripsi,
                'status'         => $k->status,
            ];
        })->toJson(); // langsung toJson() di sini

        $stats = [
            'total'     => $komponen->count(),
            'sensor'    => $komponen->where('jenis_komponen', 'sensor')->count(),
            'aktuator'  => $komponen->where('jenis_komponen', 'aktuator')->count(),
            'aktif'     => $komponen->where('status', 'active')->count(),
            'non_aktif' => $komponen->where('status', 'non-active')->count(),
        ];

        return view('pages.kelola-sensor.index', compact(
            'komponen',
            'komponenJson',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_komponen'  => 'required|string|max:255',
            'jenis_komponen' => 'required|in:sensor,aktuator',
            'tipe_komponen'  => 'nullable|string|max:255',
            'pin_data'       => 'nullable|string',
            'satuan'         => 'nullable|string|max:50',
            'batas_nilai'    => 'nullable|string',
            'lokasi'         => 'nullable|string|max:255',
            'protokol'       => 'nullable|in:I2C,OneWire,Analog,Digital,PWM,UART',
            'deskripsi'      => 'nullable|string',
            'status'         => 'required|in:active,non-active',
        ]);

        // Validasi JSON pin_data
        if (!empty($validated['pin_data'])) {
            json_decode($validated['pin_data']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['pin_data' => 'Format pin_data harus berupa JSON yang valid.']);
            }
        }

        // Validasi JSON batas_nilai
        if (!empty($validated['batas_nilai'])) {
            json_decode($validated['batas_nilai']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['batas_nilai' => 'Format batas_nilai harus berupa JSON yang valid.']);
            }
        }

        Komponen::create($validated);

        return redirect()
            ->route('kelola-sensor.index')
            ->with('toast_success', 'Komponen berhasil ditambahkan.');
    }

    public function update(Request $request, $kelola_sensor)
    {
        $komponen = Komponen::findOrFail($kelola_sensor);
    
        $validated = $request->validate([
            'nama_komponen'  => 'required|string|max:255',
            'jenis_komponen' => 'required|in:sensor,aktuator',
            'tipe_komponen'  => 'nullable|string|max:255',
            'pin_data'       => 'nullable|string',
            'satuan'         => 'nullable|string|max:50',
            'batas_nilai'    => 'nullable|string',
            'lokasi'         => 'nullable|string|max:255',
            'protokol'       => 'nullable|in:I2C,OneWire,Analog,Digital,PWM,UART',
            'deskripsi'      => 'nullable|string',
            'status'         => 'required|in:active,non-active',
        ]);

        if (!empty($validated['pin_data'])) {
            json_decode($validated['pin_data']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['pin_data' => 'Format pin_data harus berupa JSON yang valid.']);
            }
        }

        if (!empty($validated['batas_nilai'])) {
            json_decode($validated['batas_nilai']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['batas_nilai' => 'Format batas_nilai harus berupa JSON yang valid.']);
            }
        }

        $komponen->update($validated);

        return redirect()
            ->route('kelola-sensor.index')
            ->with('toast_success', 'Komponen berhasil diperbarui.');
    }

    public function toggleStatus(Komponen $komponen)
    {
        $komponen->update([
            'status' => $komponen->status === 'active' ? 'non-active' : 'active',
        ]);

        return redirect()
            ->route('kelola-sensor.index')
            ->with('toast_info', "Status {$komponen->nama_komponen} berhasil diperbarui.");
    }

    public function destroy($kelola_sensor)
    {
        $komponen = Komponen::findOrFail($kelola_sensor);
    
        $nama = $komponen->nama_komponen;
        $komponen->delete();

        return redirect()
            ->route('kelola-sensor.index')
            ->with('toast_danger', "{$nama} berhasil dihapus dari sistem.");
    }
}