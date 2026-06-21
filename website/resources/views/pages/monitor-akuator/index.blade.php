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
            <div id="page-title"
                style="font-size:15px;font-weight:700;color:#E8F0E8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                Monitoring Aktuator</div>
            <div id="page-breadcrumb"
                style="font-size:11px;color:#4D5E4D;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                Status dan kontrol semua aktuator</div>
        </div>

        <!-- Header Right -->
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <!-- Last update (disembunyikan di mobile, hanya tampil mulai md) -->
            <div id="last-update-badge"
                style="display:none;align-items:center;gap:6px;background:#131613;border:1px solid #1E2C1E;border-radius:8px;padding:6px 12px;white-space:nowrap;">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="#5A7A5A" stroke-width="1.5" />
                    <path d="M12 6v6l4 2" stroke="#5A7A5A" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <span style="font-size:11px;color:#5A7A5A;">Update: <span id="last-update"
                        style="color:#16C47F;">--:--:--</span></span>
            </div>

            @include('components.admin.notif-dropdown')

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;text-decoration:none;">
                {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'G' }}</a>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main style="flex:1;width:100%;min-width:0;" id="page-content-wrap">
        <div id="page-content">
            {{-- resources/views/pages/monitor-akuator/index.blade.php --}}
            {{-- Halaman ini didesain untuk di-include ke dalam layout admin yang sama
                 dengan monitor-sensor (sidebar, header, dsb sudah ada di layout induk).
                 Bagian di bawah ini adalah isi <div id="page-monitoring-aktuator"> saja. --}}

            <div id="page-monitoring-aktuator">

                {{-- ── STATS HEADER ──────────────────────────────────────────────────── --}}
                <div class="astat-grid"
                    style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px;">

                    <div class="stat-card" style="padding:16px;">
                        <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                            TOTAL AKTUATOR</div>
                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;" id="astat-total">
                            {{ $stats['total'] }}</div>
                        <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Terdaftar di sistem</div>
                    </div>

                    <div class="stat-card" style="padding:16px;">
                        <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                            SEDANG AKTIF</div>
                        <div style="font-size:28px;font-weight:800;color:#16C47F;" id="astat-aktif">
                            {{ $stats['aktif'] }}</div>
                        <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Status ON saat ini</div>
                    </div>

                    <div class="stat-card" style="padding:16px;">
                        <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                            NONAKTIF</div>
                        <div style="font-size:28px;font-weight:800;color:#6B7F6B;" id="astat-nonaktif">
                            {{ $stats['nonaktif'] }}</div>
                        <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Status OFF saat ini</div>
                    </div>

                    <div class="stat-card" style="padding:16px;">
                        <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                            MODE OTOMATIS</div>
                        <div style="font-size:28px;font-weight:800;color:#4A8AF0;">{{ $stats['otomatis'] }}
                        </div>
                        <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Dikontrol sistem/AI</div>
                    </div>

                    <div class="stat-card" style="padding:16px;">
                        <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;margin-bottom:8px;">
                            LOG HARI INI</div>
                        <div style="font-size:28px;font-weight:800;color:#E8F0E8;" id="astat-log">
                            {{ number_format($stats['log_hari_ini']) }}</div>
                        <div style="font-size:11px;color:#5A7A5A;margin-top:4px;">Total perintah</div>
                    </div>

                </div>

                {{-- ── ACTUATOR CARDS ───────────────────────────────────────────────────── --}}
                <div class="asection-head"
                    style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:8px;flex-wrap:wrap;">
                    <div
                        style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                        Kontrol Aktuator Real-time <span class="hide-on-mobile"
                            style="font-size:10px;color:#4D5E4D;font-weight:500;text-transform:none;letter-spacing:normal;">—
                            update setiap 1 detik</span>
                    </div>
                    <div class="badge-live">LIVE</div>
                </div>

                <div class="aktuator-grid"
                    style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:28px;">

                    @foreach ($aktuators as $aktuator)
                        @php
                            $log = $latestLogs[$aktuator->id] ?? null;
                            $isOn = $log && $log->status === \App\Models\AktuatorLog::STATUS_ON;
                            $sumber = $log->sumber_perintah ?? null;
                            $tipe = strtolower($aktuator->tipe_komponen ?? '');
                            $durasiDetik = $durasiHariIni[$aktuator->id] ?? 0;
                            $durasiMenit = round($durasiDetik / 60, 1);

                            $warna = match (true) {
                                str_contains($tipe, 'pompa'), str_contains($tipe, 'ssr') => [
                                    'hex' => '#16C47F',
                                    'bg' => '#16C47F18',
                                    'border' => '#16C47F22',
                                ],
                                str_contains($tipe, 'ph down') => [
                                    'hex' => '#F04A4A',
                                    'bg' => '#F04A4A18',
                                    'border' => '#F04A4A33',
                                ],
                                str_contains($tipe, 'ph up') => [
                                    'hex' => '#4A8AF0',
                                    'bg' => '#4A8AF018',
                                    'border' => '#4A8AF022',
                                ],
                                str_contains($tipe, 'motor') => [
                                    'hex' => '#F0A84A',
                                    'bg' => '#F0A84A18',
                                    'border' => '#F0A84A33',
                                ],
                                str_contains($tipe, 'kipas') => [
                                    'hex' => '#4AF0C8',
                                    'bg' => '#4AF0C818',
                                    'border' => '#4AF0C822',
                                ],
                                str_contains($tipe, 'lampu') => [
                                    'hex' => '#F0E04A',
                                    'bg' => '#F0E04A18',
                                    'border' => '#F0E04A33',
                                ],
                                default => ['hex' => '#A84AF0', 'bg' => '#A84AF018', 'border' => '#A84AF022'],
                            };

                            $sumberBadge = match ($sumber) {
                                'manual' => ['cls' => 'badge-off', 'txt' => 'Manual'],
                                'otomatis' => ['cls' => 'badge-ok', 'txt' => 'Otomatis'],
                                'threshold' => ['cls' => 'badge-warn', 'txt' => 'Threshold'],
                                'ai' => ['cls' => 'badge-ok', 'txt' => 'AI'],
                                default => ['cls' => 'badge-off', 'txt' => '—'],
                            };
                        @endphp

                        <div class="actuator-card {{ $isOn ? 'on' : '' }}"
                            style="flex-direction:column;align-items:stretch;" id="actuator-card-{{ $aktuator->id }}"
                            data-aktuator="{{ $aktuator->id }}">

                            {{-- Header --}}
                            <div
                                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:8px;">
                                <div style="display:flex;align-items:center;gap:10px;min-width:0;flex:1;">
                                    <div
                                        style="width:40px;height:40px;background:{{ $warna['bg'] }};border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid {{ $warna['border'] }};flex-shrink:0;">
                                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="3" fill="{{ $warna['hex'] }}" />
                                            <path d="M19.07 4.93A10 10 0 1 0 21 12" stroke="{{ $warna['hex'] }}"
                                                stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <div style="min-width:0;flex:1;">
                                        <div
                                            style="font-size:13px;font-weight:700;color:#C4D4C4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $aktuator->tipe_komponen }}</div>
                                        <div
                                            style="font-size:11px;color:#4D5E4D;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $aktuator->nama_komponen }} · {{ $aktuator->protokol }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Toggle Switch --}}
                                <label class="toggle-wrap">
                                    <input type="checkbox" class="actuator-toggle-input"
                                        data-aktuator="{{ $aktuator->id }}" {{ $isOn ? 'checked' : '' }}
                                        onchange="handleToggle({{ $aktuator->id }}, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            {{-- Status besar --}}
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                                <div data-aktuator="{{ $aktuator->id }}" data-field="status-dot"
                                    style="width:10px;height:10px;border-radius:50%;background:{{ $isOn ? $warna['hex'] : '#3A4A3A' }};{{ $isOn ? 'animation:pulse-dot 1.5s infinite;' : '' }}flex-shrink:0;">
                                </div>
                                <div data-aktuator="{{ $aktuator->id }}" data-field="status-text"
                                    style="font-size:24px;font-weight:800;color:{{ $isOn ? $warna['hex'] : '#5A6B5A' }};">
                                    {{ $isOn ? 'ON' : 'OFF' }}
                                </div>
                                <span class="{{ $sumberBadge['cls'] }}" data-aktuator="{{ $aktuator->id }}"
                                    data-field="sumber-badge" style="margin-left:auto;">{{ $sumberBadge['txt'] }}</span>
                            </div>

                            {{-- Info grid: PWM & durasi hari ini --}}
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                                <div style="background:#131613;border-radius:10px;padding:12px;min-width:0;">
                                    <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                        PWM /
                                        DAYA
                                    </div>
                                    <div style="font-size:22px;font-weight:800;color:#E8F0E8;line-height:1;">
                                        <span data-aktuator="{{ $aktuator->id }}"
                                            data-field="pwm">{{ $log->nilai_pwm ?? '-' }}</span>
                                        @if ($log && $log->nilai_pwm !== null)
                                            <span style="font-size:12px;color:{{ $warna['hex'] }};">/255</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="background:#131613;border-radius:10px;padding:12px;min-width:0;">
                                    <div style="font-size:10px;color:#4D5E4D;margin-bottom:4px;font-weight:600;">
                                        NYALA
                                        HARI
                                        INI</div>
                                    <div style="font-size:22px;font-weight:800;color:#E8F0E8;line-height:1;">
                                        <span data-aktuator="{{ $aktuator->id }}"
                                            data-field="durasi-menit">{{ $durasiMenit }}</span>
                                        <span style="font-size:12px;color:{{ $warna['hex'] }};">menit</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Mini Chart (riwayat status ON/OFF) --}}
                            <div class="chart-container" style="height:60px;margin-bottom:10px;">
                                <canvas id="amc-{{ $aktuator->id }}"></canvas>
                            </div>

                            {{-- Footer --}}
                            <div
                                style="display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid #1A2A1A;gap:8px;">
                                <div
                                    style="font-size:10px;color:#3D4E3D;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    Update terakhir:
                                    <span style="color:#5A7A5A;" data-aktuator="{{ $aktuator->id }}"
                                        data-field="updated_at">
                                        {{ $log ? $log->created_at->diffForHumans() : 'Belum ada data' }}
                                    </span>
                                </div>
                                <button onclick="openCatatanModal({{ $aktuator->id }}, '{{ $aktuator->nama_komponen }}')"
                                    style="background:#131613;border:1px solid #1E2C1E;color:#5A7A5A;border-radius:6px;padding:4px 10px;font-size:10px;cursor:pointer;font-family:'Inter',sans-serif;flex-shrink:0;">
                                    + Catatan
                                </button>
                            </div>

                        </div>{{-- end actuator-card --}}
                    @endforeach

                </div>

                {{-- ── GRAFIK HISTORIS STATUS ─────────────────────────────────────────────── --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div
                        style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                        Grafik Riwayat Status</div>
                </div>

                <div class="agraf-grid"
                    style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;margin-bottom:28px;">
                    @foreach ($aktuators as $aktuator)
                        @php
                            $tipe = strtolower($aktuator->tipe_komponen ?? '');
                            $warna = match (true) {
                                str_contains($tipe, 'pompa'), str_contains($tipe, 'ssr') => '#16C47F',
                                str_contains($tipe, 'ph down') => '#F04A4A',
                                str_contains($tipe, 'ph up') => '#4A8AF0',
                                str_contains($tipe, 'motor') => '#F0A84A',
                                str_contains($tipe, 'kipas') => '#4AF0C8',
                                str_contains($tipe, 'lampu') => '#F0E04A',
                                default => '#A84AF0',
                            };
                        @endphp
                        <div class="stat-card" style="padding:20px;">
                            <div
                                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;">
                                <div style="min-width:0;">
                                    <div
                                        style="font-size:14px;font-weight:700;color:#C4D4C4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $aktuator->nama_komponen }}
                                    </div>
                                    <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">
                                        {{ $aktuator->tipe_komponen }} ·
                                        20 perintah terakhir</div>
                                </div>
                                <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                                    <div style="width:10px;height:3px;background:{{ $warna }};border-radius:99px;">
                                    </div>
                                    <span style="font-size:10px;color:#5A7A5A;">status</span>
                                </div>
                            </div>
                            <div class="chart-container" style="height:140px;">
                                <canvas id="amg-{{ $aktuator->id }}"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── HISTORI LOG TABLE ───────────────────────────────────────────────── --}}
                <div
                    style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:10px;">
                    <div
                        style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">
                        Histori Log Aktuator</div>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;width:100%;max-width:100%;">
                        <form method="GET" action="{{ route('admin.monitor.akuator.index') }}"
                            class="alog-filter-form" style="display:flex;gap:8px;flex-wrap:wrap;">
                            <select name="aktuator" onchange="this.form.submit()"
                                style="background:#131613;border:1px solid #1E2C1E;color:#7A9A7A;border-radius:8px;padding:5px 10px;font-size:11px;cursor:pointer;font-family:'Inter',sans-serif;">
                                <option value="all">Semua Aktuator</option>
                                @foreach ($aktuators as $a)
                                    <option value="{{ $a->id }}"
                                        {{ request('aktuator') == $a->id ? 'selected' : '' }}>
                                        {{ $a->tipe_komponen }} — {{ $a->nama_komponen }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" onchange="this.form.submit()"
                                style="background:#131613;border:1px solid #1E2C1E;color:#7A9A7A;border-radius:8px;padding:5px 10px;font-size:11px;cursor:pointer;font-family:'Inter',sans-serif;">
                                <option value="all">Semua Status</option>
                                <option value="on" {{ request('status') === 'on' ? 'selected' : '' }}>ON
                                </option>
                                <option value="off" {{ request('status') === 'off' ? 'selected' : '' }}>OFF
                                </option>
                            </select>
                            <select name="sumber" onchange="this.form.submit()"
                                style="background:#131613;border:1px solid #1E2C1E;color:#7A9A7A;border-radius:8px;padding:5px 10px;font-size:11px;cursor:pointer;font-family:'Inter',sans-serif;">
                                <option value="all">Semua Sumber</option>
                                <option value="manual" {{ request('sumber') === 'manual' ? 'selected' : '' }}>
                                    Manual
                                </option>
                                <option value="otomatis" {{ request('sumber') === 'otomatis' ? 'selected' : '' }}>
                                    Otomatis
                                </option>
                                <option value="threshold" {{ request('sumber') === 'threshold' ? 'selected' : '' }}>
                                    Threshold
                                </option>
                                <option value="ai" {{ request('sumber') === 'ai' ? 'selected' : '' }}>AI
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="stat-card" style="padding:0;overflow:hidden;">
                    <div class="table-scroll-wrap">
                        <table>
                            <thead>
                                <tr style="border-bottom:1px solid #1E2C1E;">
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        ID</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        AKTUATOR</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        STATUS</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        PWM</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        DURASI</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        SUMBER</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        CATATAN</th>
                                    <th
                                        style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.08em;">
                                        WAKTU</th>
                                </tr>
                            </thead>
                            <tbody id="alog-table-body">
                                @forelse($logs as $log)
                                    @php
                                        $isOn = $log->status === \App\Models\AktuatorLog::STATUS_ON;
                                        $tipe = strtolower($log->komponen->tipe_komponen ?? '');
                                        $warna = match (true) {
                                            str_contains($tipe, 'pompa'), str_contains($tipe, 'ssr') => [
                                                'hex' => '#16C47F',
                                                'bg' => '#16C47F18',
                                                'border' => '#16C47F22',
                                                'abbr' => 'PM',
                                            ],
                                            str_contains($tipe, 'ph down') => [
                                                'hex' => '#F04A4A',
                                                'bg' => '#F04A4A18',
                                                'border' => '#F04A4A33',
                                                'abbr' => 'pD',
                                            ],
                                            str_contains($tipe, 'ph up') => [
                                                'hex' => '#4A8AF0',
                                                'bg' => '#4A8AF018',
                                                'border' => '#4A8AF022',
                                                'abbr' => 'pU',
                                            ],
                                            str_contains($tipe, 'motor') => [
                                                'hex' => '#F0A84A',
                                                'bg' => '#F0A84A18',
                                                'border' => '#F0A84A33',
                                                'abbr' => 'MT',
                                            ],
                                            str_contains($tipe, 'kipas') => [
                                                'hex' => '#4AF0C8',
                                                'bg' => '#4AF0C818',
                                                'border' => '#4AF0C822',
                                                'abbr' => 'KP',
                                            ],
                                            str_contains($tipe, 'lampu') => [
                                                'hex' => '#F0E04A',
                                                'bg' => '#F0E04A18',
                                                'border' => '#F0E04A33',
                                                'abbr' => 'LP',
                                            ],
                                            default => [
                                                'hex' => '#A84AF0',
                                                'bg' => '#A84AF018',
                                                'border' => '#A84AF022',
                                                'abbr' => 'AK',
                                            ],
                                        };
                                        $sumberBadge = match ($log->sumber_perintah) {
                                            'manual' => ['cls' => 'badge-off', 'txt' => 'Manual'],
                                            'otomatis' => ['cls' => 'badge-ok', 'txt' => 'Otomatis'],
                                            'threshold' => ['cls' => 'badge-warn', 'txt' => 'Threshold'],
                                            'ai' => ['cls' => 'badge-ok', 'txt' => 'AI'],
                                            default => ['cls' => 'badge-off', 'txt' => '—'],
                                        };
                                    @endphp
                                    <tr style="border-bottom:1px solid #131613;transition:background 0.15s;"
                                        onmouseenter="this.style.background='#1A231A'"
                                        onmouseleave="this.style.background=''">

                                        <td style="padding:12px 16px;font-size:11px;color:#3D4E3D;font-family:monospace;">
                                            #{{ $log->id }}</td>

                                        <td style="padding:12px 16px;">
                                            <div style="display:flex;align-items:center;gap:8px;">
                                                <div
                                                    style="width:28px;height:28px;background:{{ $warna['bg'] }};border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:{{ $warna['hex'] }};border:1px solid {{ $warna['border'] }};flex-shrink:0;">
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

                                        <td style="padding:12px 16px;">
                                            <span
                                                class="{{ $isOn ? 'badge-ok' : 'badge-off' }}">{{ strtoupper($log->status) }}</span>
                                        </td>

                                        <td style="padding:12px 16px;font-size:11px;color:#7A9A7A;font-family:monospace;">
                                            {{ $log->nilai_pwm ?? '-' }}
                                        </td>

                                        <td style="padding:12px 16px;font-size:11px;color:#7A9A7A;">
                                            {{ $log->durasi_detik ? $log->durasi_detik . ' detik' : '-' }}
                                        </td>

                                        <td style="padding:12px 16px;"><span
                                                class="{{ $sumberBadge['cls'] }}">{{ $sumberBadge['txt'] }}</span>
                                        </td>

                                        <td
                                            style="padding:12px 16px;font-size:11px;color:#5A7A5A;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $log->catatan ?? '-' }}
                                        </td>

                                        <td style="padding:12px 16px;font-size:11px;color:#5A7A5A;white-space:nowrap;">
                                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            style="padding:40px;text-align:center;color:#3D4E3D;font-size:13px;">
                                            Belum ada data log</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div
                        style="padding:12px 16px;border-top:1px solid #1A2A1A;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                        <div style="font-size:11px;color:#3D4E3D;">
                            Menampilkan <span
                                style="color:#7A9A7A;">{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</span>
                            dari <span style="color:#7A9A7A;">{{ $logs->total() }}</span> log
                        </div>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
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
                                        style="font-size:11px;background:#16C47F18;border:1px solid #16C47F33;color:#16C47F;border-radius:6px;padding:4px 10px;font-weight:600;">{{ $page }}</span>
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

            </div>{{-- end page-monitoring-aktuator --}}

            {{-- ── MODAL CATATAN (untuk toggle dengan catatan opsional) ──────────────── --}}
            <div id="catatan-modal"
                style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:100;align-items:center;justify-content:center;padding:16px;">
                <div
                    style="background:#181C18;border:1px solid #1E2C1E;border-radius:16px;padding:24px;max-width:380px;width:100%;">
                    <div style="font-size:15px;font-weight:700;color:#E8F0E8;margin-bottom:4px;" id="catatan-modal-title">
                        Catatan Aktuator</div>
                    <div style="font-size:11px;color:#5A7A5A;margin-bottom:16px;">Tambahkan catatan opsional
                        untuk
                        perintah
                        manual ini.</div>
                    <textarea id="catatan-modal-input" rows="3" placeholder="Contoh: Disiram manual karena tangki kosong"
                        style="width:100%;background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:10px 12px;color:#E8F0E8;font-size:13px;font-family:'Inter',sans-serif;resize:none;outline:none;"></textarea>
                    <div style="display:flex;gap:8px;margin-top:16px;">
                        <button onclick="closeCatatanModal()"
                            style="flex:1;background:#131613;border:1px solid #1E2C1E;color:#7A9A7A;border-radius:10px;padding:10px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">Batal</button>
                        <button onclick="saveCatatanModal()"
                            style="flex:1;background:#16C47F;border:none;color:#0D1A0D;border-radius:10px;padding:10px;font-size:13px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;">Simpan</button>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // ════════════════════════════════════════════════════════════════
        //  SIDEBAR TOGGLE (mobile)
        // ════════════════════════════════════════════════════════════════
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('open');
        }

        function setPage(page) {
            // Placeholder navigasi SPA-style — sesuaikan dengan router asli proyek.
            closeSidebar();
        }

        // Tampilkan badge "last update" hanya di layar ≥768px (setara md:flex Tailwind)
        function syncLastUpdateBadge() {
            const badge = document.getElementById('last-update-badge');
            if (!badge) return;
            badge.style.display = window.innerWidth >= 768 ? 'flex' : 'none';
        }
        syncLastUpdateBadge();
        window.addEventListener('resize', syncLastUpdateBadge);

        const aktuatorsData = @json($aktuatorsJs);
        const aWarnaMap = @json($warnaJs);
        const aMiniCharts = {}; // amc-{id}
        const aMainCharts = {}; // amg-{id}
        const aGrafikData = @json($grafik);

        // ── Mini Charts (riwayat ON/OFF sebagai step line) ──────────────────────
        aktuatorsData.forEach(akt => {
            const ctx = document.getElementById('amc-' + akt.id);
            if (!ctx) return;

            const data = aGrafikData[akt.id] ?? [];
            const vals = data.map(d => d.status);
            while (vals.length < 20) vals.unshift(0);

            const color = aWarnaMap[akt.id] || '#A84AF0';

            aMiniCharts[akt.id] = new Chart(ctx, {
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
                        stepped: true,
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
                            display: false,
                            min: 0,
                            max: 1
                        }
                    },
                    animation: {
                        duration: 0
                    }
                }
            });
        });

        // ── Main Graphs (status ON/OFF dari waktu ke waktu) ──────────────────────
        const amgOptsBase = {
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
                    titleColor: '#16C47F',
                    bodyColor: '#C4D4C4',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: (ctx) => ctx.parsed.y === 1 ? 'ON' : 'OFF'
                    }
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
                    min: 0,
                    max: 1,
                    ticks: {
                        color: '#3D4E3D',
                        font: {
                            size: 10
                        },
                        stepSize: 1,
                        callback: v => v === 1 ? 'ON' : 'OFF'
                    },
                    grid: {
                        color: '#1A2A1A'
                    }
                }
            }
        };

        aktuatorsData.forEach(akt => {
            const ctx = document.getElementById('amg-' + akt.id);
            if (!ctx) return;

            const data = aGrafikData[akt.id] ?? [];
            const labels = data.map(d => d.waktu);
            const vals = data.map(d => d.status);
            const color = aWarnaMap[akt.id] || '#A84AF0';

            aMainCharts[akt.id] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: akt.nama,
                        data: vals,
                        borderColor: color,
                        backgroundColor: color + '15',
                        borderWidth: 2,
                        pointRadius: 2,
                        stepped: true,
                        fill: true
                    }]
                },
                options: amgOptsBase
            });
        });

        // ══════════════════════════════════════════════════════════════════════
        //  TOGGLE ON/OFF — Optimistic update + anti race-condition
        // ══════════════════════════════════════════════════════════════════════
        const pendingAktuator = new Set(); // id aktuator yg sedang proses toggle

        function handleToggle(aktuatorId, checkboxEl) {
            const status = checkboxEl.checked ? 'on' : 'off';
            sendToggle(aktuatorId, status, null, checkboxEl);
        }

        function openCatatanModal(aktuatorId, namaAktuator) {
            document.getElementById('catatan-modal-title').textContent = 'Catatan — ' + namaAktuator;
            document.getElementById('catatan-modal-input').value = '';
            document.getElementById('catatan-modal').style.display = 'flex';
            document.getElementById('catatan-modal').dataset.aktuator = aktuatorId;
        }

        function closeCatatanModal() {
            document.getElementById('catatan-modal').style.display = 'none';
        }

        function saveCatatanModal() {
            const modal = document.getElementById('catatan-modal');
            const aktuatorId = modal.dataset.aktuator;
            const catatan = document.getElementById('catatan-modal-input').value.trim();
            const checkbox = document.querySelector(`.actuator-toggle-input[data-aktuator="${aktuatorId}"]`);
            const statusSaatIni = checkbox && checkbox.checked ? 'on' : 'off';

            sendToggle(aktuatorId, statusSaatIni, catatan || null, checkbox);
            closeCatatanModal();
        }

        async function sendToggle(aktuatorId, status, catatan, checkboxEl) {
            const id = String(aktuatorId);
            const isOn = status === 'on';
            const card = document.getElementById('actuator-card-' + id);

            // ── 1. OPTIMISTIC UPDATE: langsung render perubahan di UI ──────────
            pendingAktuator.add(id);
            if (card) card.classList.add('pending');

            applyAktuatorState(id, {
                is_on: isOn,
                nilai_pwm: undefined,
                sumber_perintah: undefined,
                updated_at: 'menyimpan…',
            }, {
                skipCheckbox: true,
                skipChart: true
            });

            try {
                const res = await fetch(`/admin/aktuator-komponen/${aktuatorId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status,
                        catatan
                    })
                });

                if (!res.ok) throw new Error('Gagal mengubah status aktuator');
                const data = await res.json();

                // ── 2. SINKRONKAN ke state asli dari server (sumber kebenaran) ──
                applyAktuatorState(id, {
                    is_on: data.log.is_on,
                    nilai_pwm: data.log.nilai_pwm,
                    sumber_perintah: data.log.sumber_perintah,
                    updated_at: data.log.updated_at,
                });

            } catch (err) {
                console.warn('[toggle] gagal:', err);
                if (checkboxEl) checkboxEl.checked = !isOn;
                applyAktuatorState(id, {
                    is_on: !isOn,
                    nilai_pwm: undefined,
                    sumber_perintah: undefined,
                    updated_at: 'gagal tersimpan',
                }, {
                    skipCheckbox: true
                });
                alert('Gagal mengubah status aktuator. Coba lagi.');
            } finally {
                pendingAktuator.delete(id);
                if (card) card.classList.remove('pending');
            }
        }

        const prevAktuatorData = {};

        function applyAktuatorState(id, state, opts = {}) {
            const card = document.getElementById('actuator-card-' + id);
            const color = aWarnaMap[id] || '#A84AF0';

            if (card) card.classList.toggle('on', state.is_on);

            document.querySelectorAll(`[data-aktuator="${id}"][data-field="status-dot"]`).forEach(el => {
                el.style.background = state.is_on ? color : '#3A4A3A';
                el.style.animation = state.is_on ? 'pulse-dot 1.5s infinite' : 'none';
            });

            document.querySelectorAll(`[data-aktuator="${id}"][data-field="status-text"]`).forEach(el => {
                el.textContent = state.is_on ? 'ON' : 'OFF';
                el.style.color = state.is_on ? color : '#5A6B5A';
            });

            if (state.nilai_pwm !== undefined) {
                document.querySelectorAll(`[data-aktuator="${id}"][data-field="pwm"]`).forEach(el => {
                    el.textContent = state.nilai_pwm ?? '-';
                });
            }

            if (state.sumber_perintah !== undefined) {
                const sumberMap = {
                    manual: {
                        cls: 'badge-off',
                        txt: 'Manual'
                    },
                    otomatis: {
                        cls: 'badge-ok',
                        txt: 'Otomatis'
                    },
                    threshold: {
                        cls: 'badge-warn',
                        txt: 'Threshold'
                    },
                    ai: {
                        cls: 'badge-ok',
                        txt: 'AI'
                    },
                };
                const sb = sumberMap[state.sumber_perintah] ?? {
                    cls: 'badge-off',
                    txt: '—'
                };
                document.querySelectorAll(`[data-aktuator="${id}"][data-field="sumber-badge"]`).forEach(el => {
                    el.className = sb.cls;
                    el.textContent = sb.txt;
                });
            }

            document.querySelectorAll(`[data-aktuator="${id}"][data-field="updated_at"]`).forEach(el => {
                el.textContent = state.updated_at;
            });

            if (!opts.skipCheckbox) {
                const checkbox = document.querySelector(`.actuator-toggle-input[data-aktuator="${id}"]`);
                if (checkbox) checkbox.checked = state.is_on;
            }

            if (opts.skipChart) return;

            const hash = state.is_on + '|' + (state.nilai_pwm ?? '');
            const isNew = prevAktuatorData[id] !== hash;
            prevAktuatorData[id] = hash;
            if (!isNew) return;

            if (aMiniCharts[id]) {
                const mc = aMiniCharts[id];
                mc.data.datasets[0].data.push(state.is_on ? 1 : 0);
                if (mc.data.datasets[0].data.length > 20) mc.data.datasets[0].data.shift();
                mc.update('none');
            }

            if (aMainCharts[id]) {
                const mc = aMainCharts[id];
                const now = new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                mc.data.labels.push(now);
                if (mc.data.labels.length > 20) mc.data.labels.shift();
                mc.data.datasets[0].data.push(state.is_on ? 1 : 0);
                if (mc.data.datasets[0].data.length > 20) mc.data.datasets[0].data.shift();
                mc.update('none');
            }
        }

        // ── Live Polling ──────────────────────────────────────────────────────────
        const AKTUATOR_POLL_URL = "{{ route('admin.monitor.akuator.live') }}";
        const AKTUATOR_POLL_MS = 1000;

        let pollInFlight = false;

        async function pollAktuator() {
            if (pollInFlight) return;
            pollInFlight = true;

            try {
                const res = await fetch(AKTUATOR_POLL_URL);
                const data = await res.json();

                const elAktif = document.getElementById('astat-aktif');
                const elNonaktif = document.getElementById('astat-nonaktif');
                const elLog = document.getElementById('astat-log');
                if (elAktif) elAktif.textContent = data.stats.aktif;
                if (elNonaktif) elNonaktif.textContent = data.stats.nonaktif;
                if (elLog) elLog.textContent = data.log_hari_ini.toLocaleString('id-ID');

                const lastUpdateEl = document.getElementById('last-update');
                if (lastUpdateEl) {
                    lastUpdateEl.textContent = new Date().toLocaleTimeString('id-ID');
                }

                Object.entries(data.logs).forEach(([id, log]) => {
                    if (!log) return;
                    if (pendingAktuator.has(String(id))) return;

                    const durasiDetikHariIni = data.durasi_hari_ini?.[id] ?? 0;
                    document.querySelectorAll(`[data-aktuator="${id}"][data-field="durasi-menit"]`).forEach(
                        el => {
                            el.textContent = (durasiDetikHariIni / 60).toFixed(1);
                        });

                    applyAktuatorState(id, {
                        is_on: log.is_on,
                        nilai_pwm: log.nilai_pwm,
                        sumber_perintah: log.sumber_perintah,
                        updated_at: log.updated_at,
                    });
                });

            } catch (err) {
                console.warn('[poll-aktuator] gagal fetch:', err);
            } finally {
                pollInFlight = false;
            }
        }

        pollAktuator();
        setInterval(pollAktuator, AKTUATOR_POLL_MS);
    </script>
@endsection
