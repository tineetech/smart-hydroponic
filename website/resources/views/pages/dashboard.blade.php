@php
    // ── Helper: cari sensor berdasarkan nama ──────────────────────────────
    $cariSensor = function ($nama) use ($sensors) {
        return $sensors->first(fn($s) => str_contains(strtolower($s->nama_komponen), strtolower($nama)));
    };
    $cariAktuator = function ($nama) use ($aktuators) {
        return $aktuators->first(fn($a) => str_contains(strtolower($a->nama_komponen), strtolower($nama)));
    };

    // ── Helper: ambil nilai dari log sensor ──────────────────────────────
    $ambilNilai = function ($log, $key, $default = 0) {
        return $log && isset($log->nilai[$key]) ? $log->nilai[$key] : $default;
    };
    $ambilKualitas = function ($log) {
        return $log ? $log->kualitas_data : 'error';
    };

    // ── Helper: badge class dari kualitas ─────────────────────────────────
    $badgeKualitas = function ($kualitas) {
        return match ($kualitas) {
            'normal' => 'badge-ok',
            'warning' => 'badge-warn',
            'critical' => 'badge-danger',
            default => 'badge-off',
        };
    };
    $labelKualitas = function ($kualitas) {
        return match ($kualitas) {
            'normal' => 'Normal',
            'warning' => 'Perhatian',
            'critical' => 'Kritis',
            default => 'Error',
        };
    };

    // ── Cari komponen spesifik ────────────────────────────────────────────
    $ds18b20 = $cariSensor('DS18B20');
    $dht22 = $cariSensor('DHT22');
    $hcsr04 = $cariSensor('HC-SR04');
    $phProbe = $cariSensor('pH');
    $tdsProbe = $cariSensor('TDS');

    $ssr = $cariAktuator('SSR');
    $phDown = $cariAktuator('pH Down');
    $phUp = $cariAktuator('pH Up');

    // ── Log terbaru ───────────────────────────────────────────────────────
    $logDS18B20 = $ds18b20 ? ($latestLogs[$ds18b20->id] ?? null) : null;
    $logDHT22 = $dht22 ? ($latestLogs[$dht22->id] ?? null) : null;
    $logHCSR04 = $hcsr04 ? ($latestLogs[$hcsr04->id] ?? null) : null;
    $logPH = $phProbe ? ($latestLogs[$phProbe->id] ?? null) : null;
    $logTDS = $tdsProbe ? ($latestLogs[$tdsProbe->id] ?? null) : null;

    $kualitasDS18B20 = $ambilKualitas($logDS18B20);
    $kualitasDHT22 = $ambilKualitas($logDHT22);
    $kualitasHCSR04 = $ambilKualitas($logHCSR04);
    $kualitasPH = $ambilKualitas($logPH);
    $kualitasTDS = $ambilKualitas($logTDS);

    // ── Nilai sensor ──────────────────────────────────────────────────────
    $nilaiSuhuAir = $ambilNilai($logDS18B20, 'suhu_air', 0);
    $nilaiSuhuUdara = $ambilNilai($logDHT22, 'suhu', 0);
    $nilaiKelembaban = $ambilNilai($logDHT22, 'kelembapan', 0);
    $nilaiLevel = $ambilNilai($logHCSR04, 'level', 0);
    $nilaiPH = $ambilNilai($logPH, 'ph', 0);
    $nilaiTDS = $ambilNilai($logTDS, 'tds', 0);

    // ── Batas nilai ───────────────────────────────────────────────────────
    $batasDS18B20 = $ds18b20 ? (json_decode($ds18b20->batas_nilai, true) ?? []) : [];
    $batasDHT22 = $dht22 ? (json_decode($dht22->batas_nilai, true) ?? []) : [];
    $batasHCSR04 = $hcsr04 ? (json_decode($hcsr04->batas_nilai, true) ?? []) : [];
    $batasPH = $phProbe ? (json_decode($phProbe->batas_nilai, true) ?? []) : [];
    $batasTDS = $tdsProbe ? (json_decode($tdsProbe->batas_nilai, true) ?? []) : [];

    // ── Aktuator status ───────────────────────────────────────────────────
    $logSSR = $ssr ? ($latestAktuatorLogs[$ssr->id] ?? null) : null;
    $logPHDown = $phDown ? ($latestAktuatorLogs[$phDown->id] ?? null) : null;
    $logPHUp = $phUp ? ($latestAktuatorLogs[$phUp->id] ?? null) : null;

    $statusSSR = $logSSR ? $logSSR->status : 'off';
    $statusPHDown = $logPHDown ? $logPHDown->status : 'off';
    $statusPHUp = $logPHUp ? $logPHUp->status : 'off';

    $sumberSSR = $logSSR ? $logSSR->sumber_perintah : 'manual';
    $sumberPHDown = $logPHDown ? $logPHDown->sumber_perintah : 'manual';
    $sumberPHUp = $logPHUp ? $logPHUp->sumber_perintah : 'manual';
@endphp

@extends('layouts.admin')

@section('content')
    <!-- HEADER -->
    <header id="header">
        <button onclick="toggleSidebar()" class="lg:hidden"
            style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>

        <div style="flex:1;min-width:0;">
            <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Dashboard</div>
            <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Ringkasan sistem hidroponik Anda</div>
        </div>

        <div style="display:flex;align-items:center;gap:8px;">
            <div class="md:flex" id="last-update-badge"
                style="display:flex;align-items:center;gap:6px;background:#131613;border:1px solid #1E2C1E;border-radius:8px;padding:6px 12px;">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="#5A7A5A" stroke-width="1.5" />
                    <path d="M12 6v6l4 2" stroke="#5A7A5A" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <span style="font-size:11px;color:#5A7A5A;">Update: <span id="last-update" style="color:#16C47F;">--:--:--</span></span>
            </div>

            @include('components.admin.notif-dropdown')

            <a href="{{ route('profile.edit') }}"
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;text-decoration:none;">
                {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'G' }}</a>
        </div>
    </header>

    <main style="flex:1;padding:24px;max-width:1400px;width:100%;margin:0 auto;" id="page-content">

        <div id="page-dashboard">

            <!-- Welcome Banner -->
            <div
                style="background:linear-gradient(135deg,#1A2A1A,#131F13);border:1px solid #2A3A2A;border-radius:16px;padding:24px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;position:relative;overflow:hidden;">
                <div
                    style="position:absolute;right:0;top:0;bottom:0;width:300px;background:radial-gradient(ellipse at right,#16C47F0A,transparent);pointer-events:none;">
                </div>
                <div>
                    <div style="font-size:12px;font-weight:600;color:#16C47F;letter-spacing:0.08em;margin-bottom:6px;">●
                        SISTEM AKTIF</div>
                    <div style="font-size:22px;font-weight:800;color:#E8F0E8;margin-bottom:4px;">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}! 👋</div>
                    <div style="font-size:14px;color:#5A7A5A;max-width:500px;">
                        @if ($sensorStats['warning'] > 0 || $sensorStats['critical'] > 0)
                            {{ $sensorStats['normal'] }} dari {{ $sensorStats['total'] }} parameter normal.
                            {{ $sensorStats['warning'] }} peringatan, {{ $sensorStats['critical'] }} kritis.
                        @else
                            Semua sensor berjalan normal. {{ $sensorStats['total'] }} parameter dalam kondisi optimal.
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <div
                        style="text-align:center;background:#131613;border:1px solid #1E2C1E;border-radius:12px;padding:14px 20px;">
                        <div style="font-size:24px;font-weight:800;color:#16C47F;" id="uptime-display">--</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Uptime</div>
                    </div>
                    <div
                        style="text-align:center;background:#131613;border:1px solid #1E2C1E;border-radius:12px;padding:14px 20px;">
                        <div style="font-size:24px;font-weight:800;color:#E8F0E8;">{{ $sensorStats['total'] }}/{{ $sensorStats['total'] }}</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Sensor Online</div>
                    </div>
                </div>
            </div>

            <!-- SENSOR STATS GRID -->
            <div style="margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                    Sensor Real-time</div>
                <div class="badge-live">LIVE</div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:28px;">

                <!-- Suhu Air -->
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div
                                style="width:32px;height:32px;background:#16C47F18;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 2a3 3 0 0 0-3 3v7a5 5 0 1 0 6 0V5a3 3 0 0 0-3-3z" stroke="#16C47F" stroke-width="1.8" />
                                    <circle cx="12" cy="17" r="2" fill="#16C47F" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">SUHU AIR</div>
                                <div style="font-size:10px;color:#3D4E3D;">DS18B20</div>
                            </div>
                        </div>
                        <span class="{{ $badgeKualitas($kualitasDS18B20) }}" id="s-suhu-air-status">{{ $labelKualitas($kualitasDS18B20) }}</span>
                    </div>
                    <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-suhu-air">{{ number_format($nilaiSuhuAir, 1) }}<span style="font-size:18px;color:#16C47F;font-weight:600;">°C</span></div>
                    <div style="margin-top:12px;">
                        <div class="flex justify-between" style="font-size:10px;color:#4D5E4D;margin-bottom:4px;display:flex;justify-content:space-between;">
                            <span>Optimal: {{ $batasDS18B20['optimal_min'] ?? 20 }}–{{ $batasDS18B20['optimal_max'] ?? 28 }}°C</span>
                            <span id="s-suhu-air-pct">{{ $batasDS18B20 ? min(100, max(0, (($nilaiSuhuAir - ($batasDS18B20['min'] ?? 15)) / (($batasDS18B20['max'] ?? 35) - ($batasDS18B20['min'] ?? 15))) * 100)) : 0 }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" id="s-suhu-air-bar"
                                style="width:{{ $batasDS18B20 ? min(100, max(0, (($nilaiSuhuAir - ($batasDS18B20['min'] ?? 15)) / (($batasDS18B20['max'] ?? 35) - ($batasDS18B20['min'] ?? 15))) * 100)) : 0 }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Suhu Udara -->
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div
                                style="width:32px;height:32px;background:#4A8AF018;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                    <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9z" stroke="#4A8AF0" stroke-width="1.8" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">SUHU UDARA</div>
                                <div style="font-size:10px;color:#3D4E3D;">DHT22</div>
                            </div>
                        </div>
                        <span class="{{ $badgeKualitas($kualitasDHT22) }}" id="s-suhu-udara-status">{{ $labelKualitas($kualitasDHT22) }}</span>
                    </div>
                    <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-suhu-udara">{{ number_format($nilaiSuhuUdara, 1) }}<span style="font-size:18px;color:#4A8AF0;font-weight:600;">°C</span></div>
                    <div style="margin-top:12px;">
                        <div class="flex justify-between" style="font-size:10px;color:#4D5E4D;margin-bottom:4px;display:flex;justify-content:space-between;">
                            <span>Optimal: {{ $batasDHT22['optimal_min'] ?? 22 }}–{{ $batasDHT22['optimal_max'] ?? 32 }}°C</span>
                            <span id="s-suhu-udara-pct">{{ $batasDHT22 ? min(100, max(0, (($nilaiSuhuUdara - ($batasDHT22['min'] ?? 18)) / (($batasDHT22['max'] ?? 38) - ($batasDHT22['min'] ?? 18))) * 100)) : 0 }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" id="s-suhu-udara-bar"
                                style="width:{{ $batasDHT22 ? min(100, max(0, (($nilaiSuhuUdara - ($batasDHT22['min'] ?? 18)) / (($batasDHT22['max'] ?? 38) - ($batasDHT22['min'] ?? 18))) * 100)) : 0 }}%;background:linear-gradient(90deg,#4A8AF0,#7AB0FF);"></div>
                        </div>
                    </div>
                </div>

                <!-- Level Air -->
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div
                                style="width:32px;height:32px;background:#4AF0C818;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 2C6 9 4 13 4 16a8 8 0 0 0 16 0c0-3-2-7-8-14z" stroke="#4AF0C8" stroke-width="1.8" fill="#4AF0C822" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">LEVEL AIR</div>
                                <div style="font-size:10px;color:#3D4E3D;">HC-SR04</div>
                            </div>
                        </div>
                        <span class="{{ $badgeKualitas($kualitasHCSR04) }}" id="s-level-status">{{ $labelKualitas($kualitasHCSR04) }}</span>
                    </div>
                    <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-level">{{ (int)$nilaiLevel }}<span style="font-size:18px;color:#4AF0C8;font-weight:600;">%</span></div>
                    <div style="margin-top:12px;">
                        <div class="flex justify-between" style="font-size:10px;color:#4D5E4D;margin-bottom:4px;display:flex;justify-content:space-between;">
                            <span>Min: {{ $batasHCSR04['min'] ?? 20 }}% Max: {{ $batasHCSR04['max'] ?? 100 }}%</span>
                            <span id="s-level-pct">{{ (int)$nilaiLevel }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" id="s-level-bar"
                                style="width:{{ max(0, min(100, $nilaiLevel)) }}%;background:linear-gradient(90deg,#4AF0C8,#7AFFD8);"></div>
                        </div>
                    </div>
                </div>

                <!-- pH -->
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div
                                style="width:32px;height:32px;background:#F0A84A18;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                    <path d="M8 3v18M8 3c0 0 3 2 6 2s6-2 6-2v9c0 0-3 2-6 2s-6-2-6-2" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">pH LARUTAN</div>
                                <div style="font-size:10px;color:#3D4E3D;">Probe pH</div>
                            </div>
                        </div>
                        <span class="{{ $badgeKualitas($kualitasPH) }}" id="s-ph-status">{{ $labelKualitas($kualitasPH) }}</span>
                    </div>
                    <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-ph">{{ number_format($nilaiPH, 1) }}<span style="font-size:18px;color:#F0A84A;font-weight:600;">pH</span></div>
                    <div style="margin-top:12px;">
                        <div class="flex justify-between" style="font-size:10px;color:#4D5E4D;margin-bottom:4px;display:flex;justify-content:space-between;">
                            <span>Optimal: {{ $batasPH['optimal_min'] ?? 5.5 }}–{{ $batasPH['optimal_max'] ?? 7.0 }}</span>
                            <span id="s-ph-pct">{{ $nilaiPH > 0 ? min(100, max(0, ($nilaiPH / 14) * 100)) : 0 }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" id="s-ph-bar"
                                style="width:{{ $nilaiPH > 0 ? min(100, max(0, ($nilaiPH / 14) * 100)) : 0 }}%;background:linear-gradient(90deg,#F0A84A,#FFCA7A);"></div>
                        </div>
                    </div>
                </div>

                <!-- TDS -->
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div
                                style="width:32px;height:32px;background:#A84AF018;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" stroke="#A84AF0" stroke-width="1.8" />
                                    <path d="M8 12h8M12 8v8" stroke="#A84AF0" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">TDS NUTRISI</div>
                                <div style="font-size:10px;color:#3D4E3D;">EC/TDS Probe</div>
                            </div>
                        </div>
                        <span class="{{ $badgeKualitas($kualitasTDS) }}" id="s-tds-status">{{ $labelKualitas($kualitasTDS) }}</span>
                    </div>
                    <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-tds">{{ (int)$nilaiTDS }}<span style="font-size:18px;color:#A84AF0;font-weight:600;">ppm</span></div>
                    <div style="margin-top:12px;">
                        <div class="flex justify-between" style="font-size:10px;color:#4D5E4D;margin-bottom:4px;display:flex;justify-content:space-between;">
                            <span>Optimal: {{ $batasTDS['optimal_min'] ?? 800 }}–{{ $batasTDS['optimal_max'] ?? 1600 }}ppm</span>
                            <span id="s-tds-pct">{{ $batasTDS ? min(100, max(0, ($nilaiTDS / ($batasTDS['max'] ?? 2500)) * 100)) : 0 }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" id="s-tds-bar"
                                style="width:{{ $batasTDS ? min(100, max(0, ($nilaiTDS / ($batasTDS['max'] ?? 2500)) * 100)) : 0 }}%;background:linear-gradient(90deg,#A84AF0,#C87AFF);"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CHARTS ROW -->
            <div style="display:grid;grid-template-columns:1fr;gap:20px;margin-bottom:28px;" id="chart-row">

                <!-- pH & TDS Chart -->
                <div class="stat-card" style="padding:20px;">
                    <div
                        style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
                        <div>
                            <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Grafik pH & TDS</div>
                            <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Riwayat 20 data terakhir</div>
                        </div>
                        <div style="display:flex;gap:16px;align-items:center;">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="width:10px;height:3px;background:#F0A84A;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">pH</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="width:10px;height:3px;background:#A84AF0;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">TDS</span>
                            </div>
                            <div class="badge-live">LIVE</div>
                        </div>
                    </div>
                    <div class="chart-container" style="height:180px;">
                        <canvas id="chart-ph-tds"></canvas>
                    </div>
                </div>

            </div>

            <!-- Bottom Row: Aktuator + Temp Chart + Notifications -->
            <div
                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-bottom:20px;">

                <!-- Aktuator Status -->
                <div class="stat-card" style="padding:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Status Aktuator</div>
                        <a href="{{ route('admin.monitor.akuator.index') }}"
                            style="font-size:11px;color:#16C47F;background:none;border:none;cursor:pointer;font-weight:600;text-decoration:none;">Lihat Semua →</a>
                    </div>

                    @foreach ($aktuators as $aktuator)
                    @php
                        $logA = $latestAktuatorLogs[$aktuator->id] ?? null;
                        $isOn = $logA && $logA->status === 'on';
                        $sumber = $logA ? $logA->sumber_perintah : 'manual';
                        $labelSumber = match($sumber) { 'otomatis' => 'Auto Mode', 'ai' => 'AI Mode', 'threshold' => 'Threshold', default => 'Manual Mode' };
                        $warnaA = match(true) {
                            str_contains(strtolower($aktuator->tipe_komponen ?? ''), 'ssr') => '#16C47F',
                            str_contains(strtolower($aktuator->nama_komponen ?? ''), 'ph down') => '#F04A4A',
                            str_contains(strtolower($aktuator->nama_komponen ?? ''), 'ph up') => '#4AF070',
                            default => '#16C47F',
                        };
                        $idJs = match(true) {
                            str_contains(strtolower($aktuator->nama_komponen ?? ''), 'ssr') => 'pompa-ssr',
                            str_contains(strtolower($aktuator->nama_komponen ?? ''), 'ph down') => 'ph-down',
                            str_contains(strtolower($aktuator->nama_komponen ?? ''), 'ph up') => 'ph-up',
                            default => 'akt-' . $aktuator->id,
                        };
                    @endphp
                    <div class="actuator-card @if($isOn) on @endif" id="card-{{ $idJs }}" style="margin-bottom:10px;" data-komponen-id="{{ $aktuator->id }}">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div
                                style="width:38px;height:38px;background:{{ $warnaA }}18;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid {{ $warnaA }}33;">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="{{ $warnaA }}" stroke-width="1.5" />
                                    <path d="M12 8v4l3 3" stroke="{{ $warnaA }}" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#C4D4C4;">{{ $aktuator->nama_komponen }}</div>
                                <div style="font-size:11px;color:#5A7A5A;">{{ $aktuator->tipe_komponen ?? 'Aktuator' }} · {{ $labelSumber }}</div>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                            <label class="toggle-wrap">
                                <input type="checkbox" @if($isOn) checked @endif onchange="toggleActuator(this, '{{ $idJs }}', {{ $aktuator->id }})">
                                <span class="toggle-slider"></span>
                            </label>
                            <span style="font-size:10px;color:{{ $isOn ? '#16C47F' : '#5A7A5A' }};font-weight:600;" id="{{ $idJs }}-label">{{ $isOn ? 'ON' : 'OFF' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Suhu Chart -->
                <div class="stat-card" style="padding:20px;">
                    <div
                        style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
                        <div>
                            <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Grafik Suhu</div>
                            <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Air & Udara · 20 data</div>
                        </div>
                        <div style="display:flex;gap:12px;align-items:center;">
                            <div style="display:flex;align-items:center;gap:5px;">
                                <div style="width:10px;height:3px;background:#16C47F;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">Air</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;">
                                <div style="width:10px;height:3px;background:#4A8AF0;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">Udara</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container" style="height:160px;">
                        <canvas id="chart-suhu"></canvas>
                    </div>
                </div>

                <!-- Notifikasi -->
                <div class="stat-card" style="padding:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Notifikasi Sistem</div>
                        <span
                            style="background:#16C47F18;border:1px solid #16C47F33;border-radius:99px;padding:2px 8px;font-size:11px;color:#16C47F;font-weight:700;">{{ $notifUnreadCount }}</span>
                    </div>

                    @forelse ($notifications as $notif)
                    @php
                        $notifClass = match($notif->tipe) {
                            'alert' => 'warn',
                            'error' => 'warn',
                            'ai_insight' => 'info',
                            'jadwal' => 'info',
                            default => 'info',
                        };
                        $notifIcon = match($notif->tipe) {
                            'alert' => '<path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round"/>',
                            'error' => '<path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="#F04A4A" stroke-width="1.8" stroke-linecap="round"/>',
                            'ai_insight' => '<circle cx="12" cy="12" r="10" stroke="#A84AF0" stroke-width="1.8"/><path d="M12 16v-4M12 8h.01" stroke="#A84AF0" stroke-width="1.8" stroke-linecap="round"/>',
                            default => '<circle cx="12" cy="12" r="10" stroke="#4A8AF0" stroke-width="1.8"/><path d="M12 16v-4M12 8h.01" stroke="#4A8AF0" stroke-width="1.8" stroke-linecap="round"/>',
                        };
                        $notifBg = match($notif->tipe) {
                            'alert' => '#F0A84A18',
                            'error' => '#F04A4A18',
                            'ai_insight' => '#A84AF018',
                            default => '#4A8AF018',
                        };
                    @endphp
                    <div class="alert-item {{ $notifClass }}">
                        <div style="width:28px;height:28px;background:{{ $notifBg }};border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24">{!! $notifIcon !!}</svg>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:600;color:#D4B87A;">{{ $notif->judul }}</div>
                            <div style="font-size:11px;color:#5A6B5A;margin-top:2px;">{{ $notif->pesan }}</div>
                            <div style="font-size:10px;color:#3D4E3D;margin-top:4px;">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:30px 10px;font-size:13px;color:#4D5E4D;">Belum ada notifikasi</div>
                    @endforelse
                </div>

            </div>

            <!-- System Health Row -->
            <div style="background:#131613;border:1px solid #1A2A1A;border-radius:14px;padding:20px;margin-bottom:8px;">
                <div
                    style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:16px;">
                    Ringkasan Kesehatan Sistem</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;">

                    <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">KONEKSI</div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div style="width:8px;height:8px;background:#16C47F;border-radius:50%;"></div>
                            <span style="font-size:14px;font-weight:700;color:#16C47F;">Stabil</span>
                        </div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">{{ $sensorStats['total'] }} sensor aktif</div>
                    </div>

                    <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">AKTUATOR AKTIF</div>
                        <div style="font-size:24px;font-weight:800;color:#E8F0E8;" id="aktuator-count">{{ $aktuatorAktif }}/{{ $aktuators->count() }}</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">
                            @if ($statusSSR === 'on') SSR Pompa ON @else Semua off @endif
                        </div>
                    </div>

                    <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">LOG HARI INI</div>
                        <div style="font-size:24px;font-weight:800;color:#E8F0E8;">{{ $logHariIni }}</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">Total pembacaan</div>
                    </div>

                    <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">PERINGATAN AKTIF</div>
                        <div style="font-size:24px;font-weight:800;color:{{ $sensorStats['warning'] > 0 ? '#F0A84A' : '#16C47F' }};">{{ $sensorStats['warning'] + $sensorStats['critical'] }}</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">
                            @if ($sensorStats['warning'] > 0) {{ $sensorStats['warning'] }} peringatan @else Tidak ada @endif
                        </div>
                    </div>

                    <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
                        @php
                            $totalSensor = $sensorStats['total'] ?: 1;
                            $skorKesehatan = (($sensorStats['normal'] / $totalSensor) * 100);
                            $skorWarna = $skorKesehatan >= 80 ? '#16C47F' : ($skorKesehatan >= 50 ? '#F0A84A' : '#F04A4A');
                            $skorLabel = $skorKesehatan >= 80 ? 'Sangat Baik' : ($skorKesehatan >= 50 ? 'Perlu Perhatian' : 'Kritis');
                        @endphp
                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">SKOR KESEHATAN</div>
                        <div style="font-size:24px;font-weight:800;color:{{ $skorWarna }};">{{ number_format($skorKesehatan, 0) }}%</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">{{ $skorLabel }}</div>
                    </div>

                </div>
            </div>

        </div><!-- end page-dashboard -->

    </main>
@endsection

@section('scripts')
<script>
    // ═══════════════════════════════════════════════════
    //  DATA DARI SERVER (initial page load)
    // ═══════════════════════════════════════════════════
    const serverData = {
        sensors: @json($sensorsJs),
        chartData: @json($chartData),
        latestLogs: {
            @foreach ($latestLogs as $id => $log)
            {{ $id }}: @json($log ? ['nilai' => $log->nilai, 'kualitas' => $log->kualitas_data, 'created_at' => $log->created_at->format('H:i:s')] : null)@if(!$loop->last),@endif
            @endforeach
        },
        warna: @json($warnaSensor),
        aktuators: @json($aktuators->map(fn($a) => ['id' => $a->id, 'nama' => $a->nama_komponen])),
    };

    // ═══════════════════════════════════════════════════
    //  CHART SETUP — initial data dari server
    // ═══════════════════════════════════════════════════
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1A2A1A',
                borderColor: '#2A3A2A',
                borderWidth: 1,
                titleColor: '#16C47F',
                bodyColor: '#C4D4C4',
                padding: 10,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                ticks: { color: '#3D4E3D', font: { size: 10 }, maxTicksLimit: 6 },
                grid: { color: '#1A2A1A', drawBorder: false }
            },
            y: {
                ticks: { color: '#3D4E3D', font: { size: 10 } },
                grid: { color: '#1A2A1A', drawBorder: false }
            }
        }
    };

    // ── Ambil data chart dari server ─────────────────────────────────────
    const phSensorId = serverData.sensors.find(s => s.tipe.includes('ph'))?.id;
    const tdsSensorId = serverData.sensors.find(s => s.tipe.includes('tds'))?.id;
    const ds18b20Id = serverData.sensors.find(s => s.tipe.includes('ds18b20'))?.id;
    const dht22Id = serverData.sensors.find(s => s.tipe.includes('dht22'))?.id;

    const phChartData = phSensorId && serverData.chartData[phSensorId]
        ? serverData.chartData[phSensorId].map(d => d.nilai.ph ?? 0) : [];
    const tdsChartData = tdsSensorId && serverData.chartData[tdsSensorId]
        ? serverData.chartData[tdsSensorId].map(d => d.nilai.tds ?? 0) : [];
    const suhuAirChartData = ds18b20Id && serverData.chartData[ds18b20Id]
        ? serverData.chartData[ds18b20Id].map(d => d.nilai.suhu_air ?? 0) : [];
    const suhuUdaraChartData = dht22Id && serverData.chartData[dht22Id]
        ? serverData.chartData[dht22Id].map(d => d.nilai.suhu ?? 0) : [];
    const chartLabels = phSensorId && serverData.chartData[phSensorId]
        ? serverData.chartData[phSensorId].map(d => d.waktu) : [];

    // ── pH & TDS Chart ──────────────────────────────────────────────────
    const ctxPh = document.getElementById('chart-ph-tds').getContext('2d');
    const chartPhTds = new Chart(ctxPh, {
        type: 'line',
        data: {
            labels: [...chartLabels],
            datasets: [{
                label: 'pH',
                data: [...phChartData],
                borderColor: '#F0A84A',
                backgroundColor: 'rgba(240,168,74,0.08)',
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 5,
                tension: 0.4,
                fill: true,
                yAxisID: 'yPh'
            }, {
                label: 'TDS (ppm)',
                data: [...tdsChartData],
                borderColor: '#A84AF0',
                backgroundColor: 'rgba(168,74,240,0.06)',
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 5,
                tension: 0.4,
                fill: true,
                yAxisID: 'yTds'
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: chartDefaults.scales.x,
                yPh: {
                    type: 'linear', position: 'left',
                    ticks: { color: '#F0A84A', font: { size: 10 } },
                    grid: { color: '#1A2A1A' },
                    title: { display: true, text: 'pH', color: '#F0A84A', font: { size: 10 } }
                },
                yTds: {
                    type: 'linear', position: 'right',
                    ticks: { color: '#A84AF0', font: { size: 10 } },
                    grid: { display: false },
                    title: { display: true, text: 'ppm', color: '#A84AF0', font: { size: 10 } }
                }
            }
        }
    });

    // ── Suhu Chart ──────────────────────────────────────────────────────
    const ctxSuhu = document.getElementById('chart-suhu').getContext('2d');
    const chartSuhu = new Chart(ctxSuhu, {
        type: 'line',
        data: {
            labels: [...chartLabels],
            datasets: [{
                label: 'Suhu Air (°C)',
                data: [...suhuAirChartData],
                borderColor: '#16C47F',
                backgroundColor: 'rgba(22,196,127,0.07)',
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 5,
                tension: 0.4,
                fill: true
            }, {
                label: 'Suhu Udara (°C)',
                data: [...suhuUdaraChartData],
                borderColor: '#4A8AF0',
                backgroundColor: 'rgba(74,138,240,0.07)',
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 5,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: chartDefaults.scales.x,
                y: {
                    ticks: { color: '#3D4E3D', font: { size: 10 }, callback: v => v + '°C' },
                    grid: { color: '#1A2A1A' }
                }
            }
        }
    });

    // ═══════════════════════════════════════════════════
    //  REAL-TIME POLLING dari server
    // ═══════════════════════════════════════════════════
    const liveUrl = '{{ route("admin.dashboard.live") }}';

    function fetchLiveData() {
        fetch(liveUrl)
            .then(r => r.json())
            .then(data => {
                updateSensorCards(data.sensors);
                updateAktuatorCards(data.aktuators);
                updateStats(data);
                updateChartsFromLive(data.sensors);

                const lu = document.getElementById('last-update');
                if (lu) lu.textContent = new Date().toLocaleTimeString('id-ID');
            })
            .catch(() => {});
    }

    function findSensorValue(sensors, keyword, nilaiKey) {
        const sensorId = serverData.sensors.find(s => s.tipe.includes(keyword))?.id;
        if (!sensorId || !sensors[sensorId]) return null;
        const nilai = sensors[sensorId].nilai;
        return nilai ? (nilai[nilaiKey] ?? (typeof nilai === 'number' ? nilai : null)) : null;
    }

    function findSensorKualitas(sensors, keyword) {
        const sensorId = serverData.sensors.find(s => s.tipe.includes(keyword))?.id;
        if (!sensorId || !sensors[sensorId]) return 'error';
        return sensors[sensorId].kualitas || 'error';
    }

    function updateSensorCards(sensors) {
        // Suhu Air
        const sa = findSensorValue(sensors, 'ds18b20', 'suhu_air');
        if (sa !== null) {
            const el = document.getElementById('s-suhu-air');
            if (el) el.innerHTML = sa.toFixed(1) + '<span style="font-size:18px;color:#16C47F;font-weight:600;">°C</span>';
            const batas = { min: 15, max: 35, optMin: 20, optMax: 28 };
            const pct = Math.min(100, Math.max(0, ((sa - batas.min) / (batas.max - batas.min)) * 100)).toFixed(0);
            document.getElementById('s-suhu-air-pct').textContent = pct + '%';
            document.getElementById('s-suhu-air-bar').style.width = pct + '%';
            const s = getStatus(sa, batas.min, batas.max, batas.optMin, batas.optMax);
            setStatus('s-suhu-air-status', s, s === 'ok' ? 'Normal' : s === 'warn' ? 'Perhatian' : 'Kritis');
        }

        // Suhu Udara
        const su = findSensorValue(sensors, 'dht22', 'suhu');
        if (su !== null) {
            const el = document.getElementById('s-suhu-udara');
            if (el) el.innerHTML = su.toFixed(1) + '<span style="font-size:18px;color:#4A8AF0;font-weight:600;">°C</span>';
            const batas = { min: 18, max: 38, optMin: 22, optMax: 32 };
            const pct = Math.min(100, Math.max(0, ((su - batas.min) / (batas.max - batas.min)) * 100)).toFixed(0);
            document.getElementById('s-suhu-udara-pct').textContent = pct + '%';
            document.getElementById('s-suhu-udara-bar').style.width = pct + '%';
            const s = getStatus(su, batas.min, batas.max, batas.optMin, batas.optMax);
            setStatus('s-suhu-udara-status', s, s === 'ok' ? 'Normal' : s === 'warn' ? 'Perhatian' : 'Kritis');
        }

        // Level Air
        const lv = findSensorValue(sensors, 'hc', 'level');
        if (lv !== null) {
            const el = document.getElementById('s-level');
            if (el) el.innerHTML = Math.round(lv) + '<span style="font-size:18px;color:#4AF0C8;font-weight:600;">%</span>';
            document.getElementById('s-level-pct').textContent = Math.round(lv) + '%';
            document.getElementById('s-level-bar').style.width = Math.min(100, Math.max(0, lv)) + '%';
            const s = getStatus(lv, 20, 100, 35, 95);
            setStatus('s-level-status', s, s === 'ok' ? 'Normal' : s === 'warn' ? 'Perhatian' : 'Kritis');
        }

        // pH
        const ph = findSensorValue(sensors, 'ph', 'ph');
        if (ph !== null) {
            const el = document.getElementById('s-ph');
            if (el) el.innerHTML = ph.toFixed(1) + '<span style="font-size:18px;color:#F0A84A;font-weight:600;">pH</span>';
            const pct = Math.min(100, Math.max(0, (ph / 14) * 100)).toFixed(0);
            document.getElementById('s-ph-pct').textContent = pct + '%';
            document.getElementById('s-ph-bar').style.width = pct + '%';
            const s = getStatus(ph, 4, 10, 5.5, 7.0);
            setStatus('s-ph-status', s, s === 'ok' ? 'Normal' : s === 'warn' ? 'Perhatian' : 'Kritis');
        }

        // TDS
        const tds = findSensorValue(sensors, 'tds', 'tds');
        if (tds !== null) {
            const el = document.getElementById('s-tds');
            if (el) el.innerHTML = Math.round(tds) + '<span style="font-size:18px;color:#A84AF0;font-weight:600;">ppm</span>';
            const pct = Math.min(100, Math.max(0, (tds / 2500) * 100)).toFixed(0);
            document.getElementById('s-tds-pct').textContent = pct + '%';
            document.getElementById('s-tds-bar').style.width = pct + '%';
            const s = getStatus(tds, 400, 2200, 800, 1600);
            setStatus('s-tds-status', s, s === 'ok' ? 'Normal' : s === 'warn' ? 'Perhatian' : 'Kritis');
        }
    }

    function updateAktuatorCards(aktuators) {
        if (!aktuators) return;
        Object.keys(aktuators).forEach(id => {
            const a = aktuators[id];
            if (!a) return;
            const komponen = serverData.aktuators.find(k => k.id == id);
            if (!komponen) return;
            const nama = komponen.nama.toLowerCase();
            let idJs = 'pompa-ssr';
            if (nama.includes('ph down')) idJs = 'ph-down';
            else if (nama.includes('ph up')) idJs = 'ph-up';
            else if (nama.includes('ssr')) idJs = 'pompa-ssr';

            const label = document.getElementById(idJs + '-label');
            const card = document.getElementById('card-' + idJs);
            const checkbox = card?.querySelector('input[type="checkbox"]');

            if (label) {
                label.textContent = a.is_on ? 'ON' : 'OFF';
                label.style.color = a.is_on ? '#16C47F' : '#5A7A5A';
            }
            if (card) {
                if (a.is_on) card.classList.add('on');
                else card.classList.remove('on');
            }
            if (checkbox && checkbox !== document.activeElement) {
                checkbox.checked = a.is_on;
            }
        });

        // Hitung total aktif
        let cnt = 0;
        const total = Object.keys(aktuators).length || 1;
        Object.values(aktuators).forEach(a => { if (a && a.is_on) cnt++; });
        const el = document.getElementById('aktuator-count');
        if (el) el.textContent = cnt + '/' + total;
    }

    function updateStats(data) {
        updateNotifCount(data.notif_unread);
    }

    function updateChartsFromLive(sensors) {
        const ts = new Date().toLocaleTimeString('id-ID');

        // pH
        const phVal = findSensorValue(sensors, 'ph', 'ph');
        if (phVal !== null) {
            chartPhTds.data.labels.push(ts);
            chartPhTds.data.labels.shift();
            chartPhTds.data.datasets[0].data.push(+phVal.toFixed(2));
            chartPhTds.data.datasets[0].data.shift();
        }

        // TDS
        const tdsVal = findSensorValue(sensors, 'tds', 'tds');
        if (tdsVal !== null) {
            if (phVal === null) { chartPhTds.data.labels.push(ts); chartPhTds.data.labels.shift(); }
            chartPhTds.data.datasets[1].data.push(Math.round(tdsVal));
            chartPhTds.data.datasets[1].data.shift();
        }
        chartPhTds.update('none');

        // Suhu Air
        const saVal = findSensorValue(sensors, 'ds18b20', 'suhu_air');
        if (saVal !== null) {
            chartSuhu.data.labels.push(ts);
            chartSuhu.data.labels.shift();
            chartSuhu.data.datasets[0].data.push(+saVal.toFixed(1));
            chartSuhu.data.datasets[0].data.shift();
        }

        // Suhu Udara
        const suVal = findSensorValue(sensors, 'dht22', 'suhu');
        if (suVal !== null) {
            if (saVal === null) { chartSuhu.data.labels.push(ts); chartSuhu.data.labels.shift(); }
            chartSuhu.data.datasets[1].data.push(+suVal.toFixed(1));
            chartSuhu.data.datasets[1].data.shift();
        }
        chartSuhu.update('none');
    }

    // ═══════════════════════════════════════════════════
    //  ACTUATOR TOGGLE (via API)
    // ═══════════════════════════════════════════════════
    function toggleActuator(el, id, komponenId) {
        const isOn = el.checked;
        const status = isOn ? 'on' : 'off';
        const label = document.getElementById(id + '-label');
        const card = document.getElementById('card-' + id);

        // Optimistic update
        if (label) {
            label.textContent = isOn ? 'ON' : 'OFF';
            label.style.color = isOn ? '#16C47F' : '#5A7A5A';
        }
        if (card) {
            if (isOn) card.classList.add('on');
            else card.classList.remove('on');
        }

        // Kirim ke server
        fetch('/admin/aktuator-komponen/' + komponenId + '/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(r => r.json())
        .then(data => {
            if (data.log) {
                if (label) {
                    label.textContent = data.log.is_on ? 'ON' : 'OFF';
                    label.style.color = data.log.is_on ? '#16C47F' : '#5A7A5A';
                }
                if (card) {
                    if (data.log.is_on) card.classList.add('on');
                    else card.classList.remove('on');
                }
                el.checked = data.log.is_on;
            }
        })
        .catch(() => {
            // Rollback on error
            el.checked = !isOn;
            if (label) {
                label.textContent = !isOn ? 'ON' : 'OFF';
                label.style.color = !isOn ? '#16C47F' : '#5A7A5A';
            }
            if (card) {
                if (!isOn) card.classList.add('on');
                else card.classList.remove('on');
            }
        });
    }

    // ═══════════════════════════════════════════════════
    //  HELPERS (dari admin.blade.php)
    // ═══════════════════════════════════════════════════
    function getStatus(value, min, max, warnMin, warnMax) {
        if (value < min || value > max) return 'danger';
        if (value < warnMin || value > warnMax) return 'warn';
        return 'ok';
    }

    function setStatus(elId, statusClass, text) {
        const el = document.getElementById(elId);
        if (!el) return;
        el.className = 'badge-' + statusClass;
        el.textContent = text;
    }

    // ═══════════════════════════════════════════════════
    //  INIT
    // ═══════════════════════════════════════════════════
    document.getElementById('last-update').textContent = new Date().toLocaleTimeString('id-ID');

    // Poll every 3 seconds
    setInterval(fetchLiveData, 3000);

    // Responsive chart row
    function adjustLayout() {
        const row = document.getElementById('chart-row');
        if (!row) return;
        row.style.gridTemplateColumns = '1fr';
    }
    window.addEventListener('resize', adjustLayout);
    adjustLayout();

    console.log('%c🌿 Gdronic Admin Panel v1.0', 'color:#16C47F;font-size:16px;font-weight:bold;');
    console.log('%cDashboard dinamis — data dari database.', 'color:#5A7A5A;');
</script>
@endsection
