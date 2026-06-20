@extends('layouts.admin')

@section('content')
    <!-- HEADER -->
    <header id="header">
        <!-- Hamburger (mobile & tablet) -->
        <button onclick="toggleSidebar()" class="lg:hidden"
            style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>

        <!-- Page Title -->
        <div style="flex:1;min-width:0;">
            <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Dashboard</div>
            <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Ringkasan sistem
                hidroponik Anda</div>
        </div>

        <!-- Header Right -->
        <div style="display:flex;align-items:center;gap:8px;">
            <!-- Last update -->
            <div style="display:none;" class="md:flex" id="last-update-badge"
                style="display:flex;align-items:center;gap:6px;background:#131613;border:1px solid #1E2C1E;border-radius:8px;padding:6px 12px;">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="#5A7A5A" stroke-width="1.5" />
                    <path d="M12 6v6l4 2" stroke="#5A7A5A" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <span style="font-size:11px;color:#5A7A5A;">Update: <span id="last-update"
                        style="color:#A8F04A;">--:--:--</span></span>
            </div>

            <!-- Notif -->
            <button
                style="background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:8px;cursor:pointer;position:relative;color:#7A9A7A;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="notif-dot"></div>
            </button>

            <!-- Profile -->
            <div
                style="width:36px;height:36px;background:linear-gradient(135deg,#A8F04A,#6BC42A);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;">
                G</div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main style="flex:1;padding:24px;max-width:1400px;width:100%;margin:0 auto;" id="page-content">

        <div id="page-monitoring-sensor">

            {{-- ── STATS HEADER ──────────────────────────────────────────────────── --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">

                <div class="stat-card" style="padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                        TOTAL SENSOR</div>
                    <div style="font-size:28px;font-weight:800;color:#E8F0E8;" id="stat-total">
                        {{ $stats['total'] }}</div>
                    <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Terdaftar di sistem</div>
                </div>

                <div class="stat-card" style="padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                        SENSOR ONLINE</div>
                    <div style="font-size:28px;font-weight:800;color:#A8F04A;" id="stat-online">
                        {{ $stats['online'] }}</div>
                    <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Aktif & membaca data</div>
                </div>

                <div class="stat-card" style="padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                        STATUS NORMAL</div>
                    <div style="font-size:28px;font-weight:800;color:#A8F04A;" id="stat-normal">
                        {{ $stats['normal'] }}</div>
                    <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Dalam batas optimal</div>
                </div>

                <div class="stat-card" style="padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                        PERINGATAN</div>
                    <div style="font-size:28px;font-weight:800;color:#F0A84A;" id="stat-warning">
                        {{ $stats['warning'] }}</div>
                    <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Perlu perhatian</div>
                </div>

                <div class="stat-card" style="padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                        LOG HARI INI</div>
                    <div style="font-size:28px;font-weight:800;color:#E8F0E8;" id="stat-log">
                        {{ number_format($stats['log_hari_ini']) }}</div>
                    <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Total pembacaan</div>
                </div>

            </div>

            {{-- ── SENSOR CARDS ───────────────────────────────────────────────────── --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <div style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                    Data Sensor Real-time <span class="text-[10px] text-gray-600">- Update setiap 1-5 detik</span></div>
                <div class="badge-live">LIVE</div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:28px;">

                @foreach ($sensors as $sensor)
                    @php
                        $log = $latestLogs[$sensor->id] ?? null;
                        $nilai = $log ? $log->nilai : [];
                        $kualitas = $log ? $log->kualitas_data : 'error';
                        $batas = json_decode($sensor->batas_nilai, true) ?? [];
                        $tipe = strtolower($sensor->tipe_komponen ?? '');

                        $badgeClass = match ($kualitas) {
                            'normal' => 'badge-ok',
                            'warning' => 'badge-warn',
                            'critical', 'error' => 'badge-danger',
                            default => 'badge-off',
                        };
                        $badgeText = match ($kualitas) {
                            'normal' => 'Normal',
                            'warning' => 'Perhatian',
                            'critical' => 'Kritis',
                            'error' => 'Error',
                            default => 'Offline',
                        };

                        $warna = match (true) {
                            str_contains($tipe, 'ds18b20') => [
                                'hex' => '#A8F04A',
                                'bg' => '#A8F04A18',
                                'border' => '#A8F04A22',
                            ],
                            str_contains($tipe, 'dht22') => [
                                'hex' => '#4A8AF0',
                                'bg' => '#4A8AF018',
                                'border' => '#4A8AF022',
                            ],
                            str_contains($tipe, 'hc-sr04') || str_contains($tipe, 'hcsr04') => [
                                'hex' => '#4AF0C8',
                                'bg' => '#4AF0C818',
                                'border' => '#4AF0C822',
                            ],
                            str_contains($tipe, 'ph') => [
                                'hex' => '#F0A84A',
                                'bg' => '#F0A84A18',
                                'border' => '#F0A84A33',
                            ],
                            str_contains($tipe, 'tds') => [
                                'hex' => '#A84AF0',
                                'bg' => '#A84AF018',
                                'border' => '#A84AF022',
                            ],
                            default => ['hex' => '#A8F04A', 'bg' => '#A8F04A18', 'border' => '#A8F04A22'],
                        };

                        $nilaiUtama = $nilai[array_key_first($nilai ?? [''])] ?? '-';
                        $satuan = $sensor->satuan ?? '';

                        $optMin = $batas['optimal_min'] ?? ($batas['min'] ?? 0);
                        $optMax = $batas['optimal_max'] ?? ($batas['max'] ?? 100);
                        $range = $optMax - $optMin ?: 1;
                        $pct =
                            $nilaiUtama !== '-' ? min(100, max(0, round((($nilaiUtama - $optMin) / $range) * 100))) : 0;
                    @endphp

                    <div class="stat-card" style="padding:20px;" id="sensor-card-{{ $sensor->id }}">

                        {{-- Header --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div
                                    style="width:40px;height:40px;background:{{ $warna['bg'] }};border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid {{ $warna['border'] }};">
                                    <span style="font-size:11px;font-weight:800;color:{{ $warna['hex'] }};">
                                        {{ strtoupper(substr($sensor->tipe_komponen ?? 'S', 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div style="font-size:13px;font-weight:700;color:#C4D4C4;">
                                        {{ $sensor->tipe_komponen }}</div>
                                    <div style="font-size:11px;color:#4D5E4D;">
                                        {{ $sensor->nama_komponen }} · {{ $sensor->protokol }}
                                        @if ($sensor->pin_data)
                                            @php
                                                $pinData = is_array($sensor->pin_data)
                                                    ? $sensor->pin_data
                                                    : json_decode($sensor->pin_data, true) ?? [];
                                            @endphp
                                            @if (!empty($pinData))
                                                · Pin {{ implode('/', array_values($pinData)) }}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Badge status — ditarget JS --}}
                            <span class="{{ $badgeClass }}" data-sensor="{{ $sensor->id }}"
                                data-field="badge">{{ $badgeText }}</span>
                        </div>

                        {{-- ── Nilai Utama ── --}}
                        @if ($log && $nilai)
                            {{-- DS18B20 --}}
                            @if (isset($nilai['suhu_air']))
                                <div style="display:flex;align-items:flex-end;gap:8px;margin-bottom:16px;">
                                    <div style="font-size:48px;font-weight:800;color:#E8F0E8;line-height:1;"
                                        data-sensor="{{ $sensor->id }}" data-field="suhu_air">
                                        {{ $nilai['suhu_air'] }}</div>
                                    <div
                                        style="font-size:20px;color:{{ $warna['hex'] }};font-weight:600;margin-bottom:6px;">
                                        °C</div>
                                </div>

                                {{-- DHT22 --}}
                            @elseif(isset($nilai['suhu']) && isset($nilai['kelembapan']))
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                                    <div style="background:#131613;border-radius:10px;padding:12px;">
                                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                            SUHU</div>
                                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;line-height:1;">
                                            <span data-sensor="{{ $sensor->id }}"
                                                data-field="suhu">{{ $nilai['suhu'] }}</span><span
                                                style="font-size:13px;color:{{ $warna['hex'] }};">°C</span>
                                        </div>
                                    </div>
                                    <div style="background:#131613;border-radius:10px;padding:12px;">
                                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                            KELEMBAPAN</div>
                                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;line-height:1;">
                                            <span data-sensor="{{ $sensor->id }}"
                                                data-field="kelembapan">{{ $nilai['kelembapan'] }}</span><span
                                                style="font-size:13px;color:#4AF0C8;">%</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- HC-SR04 --}}
                            @elseif(isset($nilai['level']))
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                                    <div style="background:#131613;border-radius:10px;padding:12px;">
                                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                            LEVEL</div>
                                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;line-height:1;">
                                            <span data-sensor="{{ $sensor->id }}"
                                                data-field="level">{{ $nilai['level'] }}</span><span
                                                style="font-size:13px;color:{{ $warna['hex'] }};">%</span>
                                        </div>
                                    </div>
                                    <div style="background:#131613;border-radius:10px;padding:12px;">
                                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                            JARAK</div>
                                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;line-height:1;">
                                            <span data-sensor="{{ $sensor->id }}"
                                                data-field="jarak_cm">{{ $nilai['jarak_cm'] ?? '-' }}</span><span
                                                style="font-size:13px;color:{{ $warna['hex'] }};">cm</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- pH --}}
                            @elseif(isset($nilai['ph']))
                                <div style="display:flex;align-items:flex-end;gap:8px;margin-bottom:16px;">
                                    <div style="font-size:48px;font-weight:800;color:#E8F0E8;line-height:1;"
                                        data-sensor="{{ $sensor->id }}" data-field="ph">{{ $nilai['ph'] }}
                                    </div>
                                    <div
                                        style="font-size:20px;color:{{ $warna['hex'] }};font-weight:600;margin-bottom:6px;">
                                        pH</div>
                                </div>

                                {{-- TDS + EC --}}
                            @elseif(isset($nilai['tds']))
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                                    <div style="background:#131613;border-radius:10px;padding:12px;">
                                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                            TDS</div>
                                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;line-height:1;">
                                            <span data-sensor="{{ $sensor->id }}"
                                                data-field="tds">{{ $nilai['tds'] }}</span><span
                                                style="font-size:12px;color:{{ $warna['hex'] }};">ppm</span>
                                        </div>
                                    </div>
                                    <div style="background:#131613;border-radius:10px;padding:12px;">
                                        <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                            EC</div>
                                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;line-height:1;">
                                            <span data-sensor="{{ $sensor->id }}"
                                                data-field="ec">{{ $nilai['ec'] ?? '-' }}</span><span
                                                style="font-size:12px;color:{{ $warna['hex'] }};">mS</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Generic fallback --}}
                                <div style="display:flex;align-items:flex-end;gap:8px;margin-bottom:16px;">
                                    <div style="font-size:48px;font-weight:800;color:#E8F0E8;line-height:1;"
                                        data-sensor="{{ $sensor->id }}" data-field="generic">
                                        {{ $nilaiUtama }}</div>
                                    <div
                                        style="font-size:20px;color:{{ $warna['hex'] }};font-weight:600;margin-bottom:6px;">
                                        {{ $satuan }}</div>
                                </div>
                            @endif
                        @else
                            <div
                                style="display:flex;align-items:center;justify-content:center;height:80px;margin-bottom:16px;">
                                <span style="font-size:13px;color:#3D4E3D;">Belum ada data log</span>
                            </div>
                        @endif

                        {{-- Batas Nilai (statis, tidak perlu update) --}}
                        @if ($batas)
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:14px;">
                                <div style="background:#131613;border-radius:8px;padding:8px;text-align:center;">
                                    <div style="font-size:10px;color:#3D4E3D;margin-bottom:2px;">MIN</div>
                                    <div style="font-size:12px;font-weight:700;color:#7A9A7A;">
                                        {{ $batas['min'] ?? '-' }}</div>
                                </div>
                                <div
                                    style="background:{{ $warna['bg'] }};border:1px solid {{ $warna['border'] }};border-radius:8px;padding:8px;text-align:center;">
                                    <div style="font-size:10px;color:#5A8A5A;margin-bottom:2px;">OPTIMAL</div>
                                    <div style="font-size:10px;font-weight:700;color:{{ $warna['hex'] }};">
                                        {{ $batas['optimal_min'] ?? '-' }}–{{ $batas['optimal_max'] ?? '-' }}
                                    </div>
                                </div>
                                <div style="background:#131613;border-radius:8px;padding:8px;text-align:center;">
                                    <div style="font-size:10px;color:#3D4E3D;margin-bottom:2px;">MAX</div>
                                    <div style="font-size:12px;font-weight:700;color:#7A9A7A;">
                                        {{ $batas['max'] ?? '-' }}</div>
                                </div>
                            </div>
                        @endif

                        {{-- Progress Bar --}}
                        <div style="margin-bottom:14px;">
                            <div
                                style="display:flex;justify-content:space-between;font-size:10px;color:#4D5E4D;margin-bottom:4px;">
                                <span>Posisi dalam rentang optimal</span>
                                <span style="color:{{ $warna['hex'] }};" data-sensor="{{ $sensor->id }}"
                                    data-field="pct">{{ $pct }}%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" data-sensor="{{ $sensor->id }}" data-field="progress"
                                    style="width:{{ $pct }}%;background:linear-gradient(90deg,{{ $warna['hex'] }},{{ $warna['hex'] }}AA);">
                                </div>
                            </div>
                        </div>

                        {{-- Mini Chart --}}
                        <div style="height:70px;position:relative;">
                            <canvas id="mc-{{ $sensor->id }}"></canvas>
                        </div>

                        {{-- Footer --}}
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;margin-top:10px;padding-top:10px;border-top:1px solid #1A2A1A;">
                            <div style="font-size:10px;color:#3D4E3D;">
                                Update terakhir:
                                <span style="color:#5A7A5A;" data-sensor="{{ $sensor->id }}" data-field="updated_at">
                                    {{ $log ? $log->created_at->diffForHumans() : 'Belum ada data' }}
                                </span>
                            </div>
                            <div style="display:flex;align-items:center;gap:4px;">
                                <div data-sensor="{{ $sensor->id }}" data-field="dot"
                                    style="width:6px;height:6px;background:{{ $kualitas === 'error' ? '#F04A4A' : '#A8F04A' }};border-radius:50%;{{ $kualitas !== 'error' ? 'animation:pulse-dot 1.5s infinite;' : '' }}">
                                </div>
                                <span data-sensor="{{ $sensor->id }}" data-field="online-text"
                                    style="font-size:10px;color:{{ $kualitas === 'error' ? '#F04A4A' : '#A8F04A' }};font-weight:600;">
                                    {{ $kualitas === 'error' ? 'OFFLINE' : 'ONLINE' }}
                                </span>
                            </div>
                        </div>

                    </div>{{-- end sensor-card --}}
                @endforeach

            </div>

            {{-- ── GRAFIK HISTORIS ─────────────────────────────────────────────────── --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <div style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                    Grafik Historis</div>
            </div>

            <div
                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:16px;margin-bottom:28px;">
                @foreach ($sensors as $sensor)
                    @php
                        $grafikData = $grafik[$sensor->id] ?? collect();
                        $tipe = strtolower($sensor->tipe_komponen ?? '');
                        $warna = match (true) {
                            str_contains($tipe, 'ds18b20') => '#A8F04A',
                            str_contains($tipe, 'dht22') => '#4A8AF0',
                            str_contains($tipe, 'hc-sr04') || str_contains($tipe, 'hcsr04') => '#4AF0C8',
                            str_contains($tipe, 'ph') => '#F0A84A',
                            str_contains($tipe, 'tds') => '#A84AF0',
                            default => '#A8F04A',
                        };
                    @endphp
                    <div class="stat-card" style="padding:20px;">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;">
                            <div>
                                <div style="font-size:14px;font-weight:700;color:#C4D4C4;">
                                    {{ $sensor->nama_komponen }}</div>
                                <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">
                                    {{ $sensor->tipe_komponen }} · 20 data terakhir</div>
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;">
                                <div style="width:10px;height:3px;background:{{ $warna }};border-radius:99px;">
                                </div>
                                <span style="font-size:10px;color:#5A7A5A;">{{ $sensor->satuan }}</span>
                            </div>
                        </div>
                        <div class="chart-container" style="height:160px;">
                            <canvas id="mg-{{ $sensor->id }}"></canvas>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── HISTORI LOG TABLE ───────────────────────────────────────────────── --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:10px;">
                <div style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                    Histori Log Sensor</div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <form method="GET" action="{{ route('admin.monitor.sensor.index') }}"
                        style="display:flex;gap:8px;flex-wrap:wrap;">
                        <select name="sensor" onchange="this.form.submit()"
                            style="background:#131613;border:1px solid #1E2C1E;color:#7A9A7A;border-radius:8px;padding:5px 10px;font-size:11px;cursor:pointer;font-family:'Inter',sans-serif;">
                            <option value="all">Semua Sensor</option>
                            @foreach ($sensors as $s)
                                <option value="{{ $s->id }}" {{ request('sensor') == $s->id ? 'selected' : '' }}>
                                    {{ $s->tipe_komponen }} — {{ $s->nama_komponen }}
                                </option>
                            @endforeach
                        </select>
                        <select name="kualitas" onchange="this.form.submit()"
                            style="background:#131613;border:1px solid #1E2C1E;color:#7A9A7A;border-radius:8px;padding:5px 10px;font-size:11px;cursor:pointer;font-family:'Inter',sans-serif;">
                            <option value="all">Semua Status</option>
                            <option value="normal" {{ request('kualitas') === 'normal' ? 'selected' : '' }}>
                                Normal</option>
                            <option value="warning" {{ request('kualitas') === 'warning' ? 'selected' : '' }}>
                                Warning</option>
                            <option value="critical" {{ request('kualitas') === 'critical' ? 'selected' : '' }}>
                                Critical</option>
                            <option value="error" {{ request('kualitas') === 'error' ? 'selected' : '' }}>
                                Error</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="stat-card" style="padding:0;overflow:hidden;">
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;min-width:700px;">
                        <thead>
                            <tr style="border-bottom:1px solid #1E2C1E;">
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    ID</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    SENSOR</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    TIPE</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    NILAI (JSON)</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    STATUS</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    DIPROSES</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                    WAKTU</th>
                            </tr>
                        </thead>
                        <tbody id="log-table-body">
                            @forelse($logs as $log)
                                @php
                                    $k = $log->kualitas_data;
                                    $rowBg = match ($k) {
                                        'warning' => 'background:#F0A84A04;',
                                        'critical', 'error' => 'background:#F04A4A04;',
                                        default => '',
                                    };
                                    $badgeClass = match ($k) {
                                        'normal' => 'badge-ok',
                                        'warning' => 'badge-warn',
                                        'critical', 'error' => 'badge-danger',
                                        default => 'badge-off',
                                    };
                                    $tipe = strtolower($log->komponen->tipe_komponen ?? '');
                                    $warna = match (true) {
                                        str_contains($tipe, 'ds18b20') => [
                                            'hex' => '#A8F04A',
                                            'bg' => '#A8F04A18',
                                            'border' => '#A8F04A22',
                                            'abbr' => 'DS',
                                        ],
                                        str_contains($tipe, 'dht22') => [
                                            'hex' => '#4A8AF0',
                                            'bg' => '#4A8AF018',
                                            'border' => '#4A8AF022',
                                            'abbr' => 'DH',
                                        ],
                                        str_contains($tipe, 'hc') => [
                                            'hex' => '#4AF0C8',
                                            'bg' => '#4AF0C818',
                                            'border' => '#4AF0C822',
                                            'abbr' => 'HC',
                                        ],
                                        str_contains($tipe, 'ph') => [
                                            'hex' => '#F0A84A',
                                            'bg' => '#F0A84A18',
                                            'border' => '#F0A84A33',
                                            'abbr' => 'pH',
                                        ],
                                        str_contains($tipe, 'tds') => [
                                            'hex' => '#A84AF0',
                                            'bg' => '#A84AF018',
                                            'border' => '#A84AF022',
                                            'abbr' => 'TD',
                                        ],
                                        default => [
                                            'hex' => '#A8F04A',
                                            'bg' => '#A8F04A18',
                                            'border' => '#A8F04A22',
                                            'abbr' => 'SN',
                                        ],
                                    };
                                @endphp
                                <tr style="border-bottom:1px solid #131613;transition:background 0.15s;{{ $rowBg }}"
                                    onmouseenter="this.style.background='#1A231A'"
                                    onmouseleave="this.style.background='{{ $rowBg ? substr($rowBg, 11, -1) : '' }}'">

                                    <td style="padding:12px 16px;font-size:11px;color:#3D4E3D;font-family:monospace;">
                                        #{{ $log->id }}</td>

                                    <td style="padding:12px 16px;">
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <div
                                                style="width:28px;height:28px;background:{{ $warna['bg'] }};border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:{{ $warna['hex'] }};border:1px solid {{ $warna['border'] }};">
                                                {{ $warna['abbr'] }}
                                            </div>
                                            <div>
                                                <div style="font-size:12px;font-weight:600;color:#C4D4C4;">
                                                    {{ $log->komponen->tipe_komponen ?? '-' }}</div>
                                                <div style="font-size:10px;color:#3D4E3D;">
                                                    {{ $log->komponen->nama_komponen ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="padding:12px 16px;font-size:11px;color:#5A7A5A;">sensor</td>

                                    <td style="padding:12px 16px;">
                                        <code
                                            style="font-size:11px;background:#0D0F0D;border:1px solid {{ $k === 'normal' ? '#1A2A1A' : ($k === 'warning' ? '#F0A84A33' : '#F04A4A33') }};border-radius:4px;padding:3px 8px;color:{{ $warna['hex'] }};font-family:monospace;">
                                            {{ json_encode($log->nilai) }}
                                        </code>
                                    </td>

                                    <td style="padding:12px 16px;"><span
                                            class="{{ $badgeClass }}">{{ $k }}</span></td>

                                    <td style="padding:12px 16px;">
                                        @if ($log->sudah_diproses)
                                            <span
                                                style="font-size:11px;color:#A8F04A;background:#A8F04A18;border:1px solid #A8F04A33;border-radius:4px;padding:2px 8px;font-weight:600;">✓
                                                Ya</span>
                                        @else
                                            <span
                                                style="font-size:11px;color:#5A7A5A;background:#1A2A1A;border:1px solid #2A3A2A;border-radius:4px;padding:2px 8px;font-weight:600;">—
                                                Belum</span>
                                        @endif
                                    </td>

                                    <td style="padding:12px 16px;font-size:11px;color:#5A7A5A;white-space:nowrap;">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        style="padding:40px;text-align:center;color:#3D4E3D;font-size:13px;">Belum
                                        ada data log</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div
                    style="padding:12px 16px;border-top:1px solid #1A2A1A;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <div style="font-size:11px;color:#3D4E3D;">
                        Menampilkan <span style="color:#7A9A7A;">{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</span>
                        dari <span style="color:#7A9A7A;">{{ $logs->total() }}</span> log
                    </div>
                    <div style="display:flex;gap:6px;">
                        @if ($logs->onFirstPage())
                            <span
                                style="font-size:11px;background:#131613;border:1px solid #1E2C1E;color:#3D4E3D;border-radius:6px;padding:4px 10px;">←
                                Prev</span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}"
                                style="font-size:11px;background:#131613;border:1px solid #1E2C1E;color:#5A7A5A;border-radius:6px;padding:4px 10px;text-decoration:none;">←
                                Prev</a>
                        @endif

                        @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                            @if ($page == $logs->currentPage())
                                <span
                                    style="font-size:11px;background:#A8F04A18;border:1px solid #A8F04A33;color:#A8F04A;border-radius:6px;padding:4px 10px;font-weight:600;">{{ $page }}</span>
                            @elseif(abs($page - $logs->currentPage()) <= 2)
                                <a href="{{ $url }}"
                                    style="font-size:11px;background:#131613;border:1px solid #1E2C1E;color:#5A7A5A;border-radius:6px;padding:4px 10px;text-decoration:none;">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}"
                                style="font-size:11px;background:#131613;border:1px solid #1E2C1E;color:#5A7A5A;border-radius:6px;padding:4px 10px;text-decoration:none;">Next
                                →</a>
                        @else
                            <span
                                style="font-size:11px;background:#131613;border:1px solid #1E2C1E;color:#3D4E3D;border-radius:6px;padding:4px 10px;">Next
                                →</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- end page-monitoring-sensor --}}
    </main>
@endsection

@section('scripts')
    <script>
        const grafikData = @json($grafik);
        const sensorsData = @json($sensorsJs);
        const warnaMap = @json($warnaJs);
        const miniCharts = {}; // mc-{id}
        const mainCharts = {}; // mg-{id}

        // ── Mini Charts ───────────────────────────────────────────────────────
        sensorsData.forEach(sensor => {
            const ctx = document.getElementById('mc-' + sensor.id);
            if (!ctx) return;

            const data = grafikData[sensor.id] ?? [];
            const vals = data.map(d => {
                const n = d.nilai;
                // Ambil nilai pertama dari objek
                return parseFloat(Object.values(n)[0]) || 0;
            });

            // Jika data kurang dari 20, isi dengan 0
            while (vals.length < 20) vals.unshift(0);

            const color = warnaMap[sensor.id] || '#A8F04A';


            miniCharts[sensor.id] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({
                        length: vals.length
                    }, (_, i) => i),
                    datasets: [{
                        data: vals,
                        borderColor: color,
                        backgroundColor: color + '22',
                        borderWidth: 1.5,
                        pointRadius: 0,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    },
                    animation: {
                        duration: 0
                    }
                }
            });
        });

        // ── Main Graphs ───────────────────────────────────────────────────────
        const mgOptsBase = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1A2A1A',
                    borderColor: '#2A3A2A',
                    borderWidth: 1,
                    titleColor: '#A8F04A',
                    bodyColor: '#C4D4C4',
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#3D4E3D',
                        font: {
                            size: 10
                        },
                        maxTicksLimit: 5
                    },
                    grid: {
                        color: '#1A2A1A'
                    }
                },
                y: {
                    ticks: {
                        color: '#3D4E3D',
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        color: '#1A2A1A'
                    }
                }
            }
        };

        sensorsData.forEach(sensor => {
            const ctx = document.getElementById('mg-' + sensor.id);
            if (!ctx) return;

            const data = grafikData[sensor.id] ?? [];
            const labels = data.map(d => d.waktu);
            const color = warnaMap[sensor.id] || '#A8F04A';
            const tipe = sensor.tipe;

            // Untuk DHT22 — 2 dataset
            if (tipe.includes('dht22')) {
                const suhuVals = data.map(d => d.nilai.suhu ?? null);
                const klVals = data.map(d => d.nilai.kelembapan ?? null);

                mainCharts[sensor.id] = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                                label: 'Suhu (°C)',
                                data: suhuVals,
                                borderColor: '#4A8AF0',
                                backgroundColor: '#4A8AF00A',
                                borderWidth: 2,
                                pointRadius: 2,
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Kelembapan (%)',
                                data: klVals,
                                borderColor: '#4AF0C8',
                                backgroundColor: '#4AF0C80A',
                                borderWidth: 2,
                                pointRadius: 2,
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: mgOptsBase
                });

                // HC-SR04 — 2 dataset
            } else if (tipe.includes('hc')) {
                const levelVals = data.map(d => d.nilai.level ?? null);

                mainCharts[sensor.id] = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Level (%)',
                            data: levelVals,
                            borderColor: color,
                            backgroundColor: color + '0A',
                            borderWidth: 2,
                            pointRadius: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        ...mgOptsBase,
                        scales: {
                            ...mgOptsBase.scales,
                            y: {
                                ...mgOptsBase.scales.y,
                                ticks: {
                                    ...mgOptsBase.scales.y.ticks,
                                    callback: v => v + '%'
                                }
                            }
                        }
                    }
                });

                // TDS — 2 dataset
            } else if (tipe.includes('tds')) {
                const tdsVals = data.map(d => d.nilai.tds ?? null);
                const ecVals = data.map(d => d.nilai.ec ?? null);

                mainCharts[sensor.id] = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                                label: 'TDS (ppm)',
                                data: tdsVals,
                                borderColor: '#A84AF0',
                                backgroundColor: '#A84AF00A',
                                borderWidth: 2,
                                pointRadius: 2,
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'yTds'
                            },
                            {
                                label: 'EC (mS)',
                                data: ecVals,
                                borderColor: '#4AF0C8',
                                backgroundColor: '#4AF0C80A',
                                borderWidth: 2,
                                pointRadius: 2,
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'yEc'
                            }
                        ]
                    },
                    options: {
                        ...mgOptsBase,
                        scales: {
                            x: mgOptsBase.scales.x,
                            yTds: {
                                type: 'linear',
                                position: 'left',
                                ticks: {
                                    color: '#A84AF0',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: '#1A2A1A'
                                }
                            },
                            yEc: {
                                type: 'linear',
                                position: 'right',
                                ticks: {
                                    color: '#4AF0C8',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // DS18B20 / pH / default — 1 dataset
            } else {
                const key = tipe.includes('ph') ? 'ph' : tipe.includes('ds18b20') ? 'suhu_air' : Object.keys(data[0]
                    ?.nilai ?? {})[0];
                const vals = data.map(d => d.nilai[key] ?? null);

                mainCharts[sensor.id] = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: sensor.nama,
                            data: vals,
                            borderColor: color,
                            backgroundColor: color + '0A',
                            borderWidth: 2,
                            pointRadius: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: mgOptsBase
                });
            }
        });

        // ── Update waktu ──────────────────────────────────────────────────────
        setInterval(() => {
            const el = document.getElementById('last-update');
            if (el) el.textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);

        // ── Sidebar toggle ────────────────────────────────────────────────────
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('open');
        }
    </script>
    <script>
        const POLL_URL = "{{ route('admin.monitor.sensor.live') }}";
        const POLL_MS = 3000; // 5 detik

        // Mapping kualitas → badge class & teks
        const BADGE = {
            normal: {
                cls: 'badge-ok',
                txt: 'Normal'
            },
            warning: {
                cls: 'badge-warn',
                txt: 'Perhatian'
            },
            critical: {
                cls: 'badge-danger',
                txt: 'Kritis'
            },
            error: {
                cls: 'badge-danger',
                txt: 'Error'
            },
        };

        function setField(sensorId, field, value) {
            document.querySelectorAll(
                `[data-sensor="${sensorId}"][data-field="${field}"]`
            ).forEach(el => {
                el.textContent = value;
            });
        }

        function flashValue(sensorId, field) {
            document.querySelectorAll(
                `[data-sensor="${sensorId}"][data-field="${field}"]`
            ).forEach(el => {
                el.classList.remove('value-flash');
                void el.offsetWidth; // reflow agar animasi restart
                el.classList.add('value-flash');
            });
        }

        async function pollSensors() {
            try {
                const res = await fetch(POLL_URL);
                const data = await res.json();

                // ── Update stat cards header ─────────────────────────────
                const elNormal = document.getElementById('stat-normal');
                const elWarning = document.getElementById('stat-warning');
                const elLog = document.getElementById('stat-log');
                if (elNormal) elNormal.textContent = data.stats.normal;
                if (elWarning) elWarning.textContent = data.stats.warning;
                if (elLog) elLog.textContent = data.log_hari_ini.toLocaleString('id-ID');

                // ── Update tiap sensor ───────────────────────────────────
                Object.entries(data.logs).forEach(([id, log]) => {
                    if (!log) return;
                    const nilai = log.nilai;

                    // Nilai per tipe sensor
                    if (nilai.suhu_air !== undefined) {
                        flashValue(id, 'suhu_air');
                        setField(id, 'suhu_air', nilai.suhu_air);
                    }
                    if (nilai.suhu !== undefined) setField(id, 'suhu', nilai.suhu);
                    if (nilai.kelembapan !== undefined) setField(id, 'kelembapan', nilai.kelembapan);
                    if (nilai.level !== undefined) setField(id, 'level', nilai.level);
                    if (nilai.jarak_cm !== undefined) setField(id, 'jarak_cm', nilai.jarak_cm);
                    if (nilai.ph !== undefined) setField(id, 'ph', nilai.ph);
                    if (nilai.tds !== undefined) setField(id, 'tds', nilai.tds);
                    if (nilai.ec !== undefined) setField(id, 'ec', nilai.ec);

                    // Badge status
                    const badge = BADGE[log.kualitas] ?? {
                        cls: 'badge-off',
                        txt: 'Offline'
                    };
                    document.querySelectorAll(`[data-sensor="${id}"][data-field="badge"]`)
                        .forEach(el => {
                            el.className = badge.cls;
                            el.textContent = badge.txt;
                        });

                    // Update terakhir
                    setField(id, 'updated_at', log.updated_at);

                    // Dot online/offline
                    document.querySelectorAll(`[data-sensor="${id}"][data-field="dot"]`)
                        .forEach(el => {
                            el.style.background = log.is_online ? '#A8F04A' : '#F04A4A';
                        });


                    // ── Update Mini Chart ────────────────────────────────────
                    if (miniCharts[id]) {
                        const mc = miniCharts[id];
                        const firstKey = Object.keys(nilai)[0];
                        const newVal = parseFloat(nilai[firstKey]) || 0;

                        mc.data.datasets[0].data.push(newVal);
                        if (mc.data.datasets[0].data.length > 20) {
                            mc.data.datasets[0].data.shift(); // hapus data paling lama
                        }
                        mc.update('none'); // 'none' = tanpa animasi, lebih smooth
                    }

                    // ── Update Main Graph ─────────────────────────────────────
                    if (mainCharts[id]) {
                        const mc = mainCharts[id];
                        const now = new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });

                        // Tambah label waktu
                        mc.data.labels.push(now);
                        if (mc.data.labels.length > 20) mc.data.labels.shift();

                        // Update tiap dataset sesuai tipe sensor
                        const tipe = sensorsData.find(s => s.id == id)?.tipe ?? '';

                        if (tipe.includes('dht22')) {
                            mc.data.datasets[0].data.push(nilai.suhu ?? null);
                            mc.data.datasets[1].data.push(nilai.kelembapan ?? null);
                        } else if (tipe.includes('hc')) {
                            mc.data.datasets[0].data.push(nilai.level ?? null);
                        } else if (tipe.includes('tds')) {
                            mc.data.datasets[0].data.push(nilai.tds ?? null);
                            mc.data.datasets[1].data.push(nilai.ec ?? null);
                        } else if (tipe.includes('ph')) {
                            mc.data.datasets[0].data.push(nilai.ph ?? null);
                        } else if (tipe.includes('ds18b20')) {
                            mc.data.datasets[0].data.push(nilai.suhu_air ?? null);
                        } else {
                            // fallback generic
                            const firstKey = Object.keys(nilai)[0];
                            mc.data.datasets[0].data.push(nilai[firstKey] ?? null);
                        }

                        // Trim semua dataset ke max 20 titik
                        mc.data.datasets.forEach(ds => {
                            if (ds.data.length > 20) ds.data.shift();
                        });

                        mc.update('none');
                    }

                    // ── Update Progress Bar ───────────────────────────────────
                    const sensorInfo = sensorsData.find(s => s.id == id);
                    if (sensorInfo && sensorInfo.batas) {
                        const batas = sensorInfo.batas;
                        const optMin = batas.optimal_min ?? batas.min ?? 0;
                        const optMax = batas.optimal_max ?? batas.max ?? 100;
                        const range = optMax - optMin || 1;

                        // Ambil nilai utama (nilai pertama dari objek)
                        const firstKey = Object.keys(nilai)[0];
                        const nilaiUtama = parseFloat(nilai[firstKey]);

                        if (!isNaN(nilaiUtama)) {
                            const pct = Math.min(100, Math.max(0, Math.round(((nilaiUtama - optMin) / range) *
                                100)));

                            // Update teks persentase
                            document.querySelectorAll(`[data-sensor="${id}"][data-field="pct"]`)
                                .forEach(el => el.textContent = pct + '%');

                            // Update lebar progress bar
                            document.querySelectorAll(`[data-sensor="${id}"][data-field="progress"]`)
                                .forEach(el => el.style.width = pct + '%');
                        }
                    }
                });

            } catch (err) {
                console.warn('[poll] gagal fetch:', err);
                // Diam saja, coba lagi di interval berikutnya
            }
        }

        // Jalankan pertama kali lalu tiap 5 detik
        pollSensors();
        setInterval(pollSensors, POLL_MS);
    </script>
@endsection
