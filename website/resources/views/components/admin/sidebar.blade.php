<!-- SIDEBAR OVERLAY -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar">
    <!-- Logo -->
    <div style="padding: 20px 20px 16px; border-bottom: 1px solid #1A2A1A;">
        <div style="display:flex; align-items:center; gap:10px;">
            <div
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"
                        fill="#0D1A0D" />
                </svg>
            </div>
            <div>
                <div style="font-size:16px;font-weight:800;color:#E8F0E8;letter-spacing:-0.02em;">Gdronic<span
                        style="color:#16C47F;">.</span></div>
                <div style="font-size:10px;color:#4D5E4D;font-weight:500;letter-spacing:0.05em;">ADMIN PANEL</div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div style="padding: 12px 16px;">
        <div
            style="background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:11px;color:#5A6B5A;font-weight:600;">STATUS SISTEM</div>
            <div class="badge-live">ONLINE</div>
        </div>
    </div>

    <!-- Nav -->

    @php
        $sidebarSensors = \App\Models\Komponen::sensor()->orderBy('id')->get();
        $sidebarAktuators = \App\Models\Komponen::aktuator()->orderBy('id')->get();
    @endphp
    <nav style="flex:1;padding:4px 0;">
        <div class="nav-section-label">Menu Utama</div>

        <a class="nav-item {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}"
            href="{{ route('admin.dashboard.index') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.9" />
                    <rect x="13" y="2" width="9" height="9" rx="2" fill="currentColor"
                        opacity="0.5" />
                    <rect x="2" y="13" width="9" height="9" rx="2" fill="currentColor"
                        opacity="0.5" />
                    <rect x="13" y="13" width="9" height="9" rx="2" fill="currentColor"
                        opacity="0.5" />
                </svg>
            </div>
            Dashboard
        </a>

        <a class="nav-item {{ request()->routeIs('admin.monitor.sensor.index') ? 'active' : '' }}"
            href="{{ route('admin.monitor.sensor.index') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M3 12h2l3-9 4 18 3-9h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            Monitoring Sensor
        </a>

        <a class="nav-item {{ request()->routeIs('admin.monitor.akuator.index') ? 'active' : '' }}"
            href="{{ route('admin.monitor.akuator.index') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" fill="currentColor" />
                    <path d="M19.07 4.93A10 10 0 1 0 21 12" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
                    <path d="M21 3v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            Monitoring Aktuator
        </a>

        <a class="nav-item {{ request()->routeIs('kelola-sensor.index') ? 'active' : '' }}"
            href="{{ route('kelola-sensor.index') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="currentColor" stroke-width="1.5" />
                    <path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
            Kelola Sensor
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Lainnya</div>

        <a class="nav-item {{ request()->routeIs('kelola-notifikasi.index') ? 'active' : '' }}"
            href="{{ route('kelola-notifikasi.index') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            Kelola Notifikasi
        </a>

        <a class="nav-item {{ request()->routeIs('admin.pengaturan.index') ? 'active' : '' }}"
            href="{{ route('admin.pengaturan.index') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="currentColor" stroke-width="1.5" />
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"
                        stroke="currentColor" stroke-width="1.5" />
                </svg>
            </div>
            Pengaturan Sistem
        </a>

        <a class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
            href="{{ route('profile.edit') }}">
            <div class="nav-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </div>
            Profil Saya
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Perangkat</div>

        <div
            style="margin: 4px 12px; background:#131613; border:1px solid #1A2A1A; border-radius:10px; overflow:hidden;">
            {{-- SENSOR --}}
            <div style="padding:10px 14px;border-bottom:1px solid #1A2A1A;">
                <div style="font-size:10px;color:#3D4E3D;font-weight:700;letter-spacing:0.1em;margin-bottom:8px;">
                    SENSOR
                </div>
                @forelse ($sidebarSensors as $sensor)
                    <div class="sidebar-device-item"
                        style="display:flex;align-items:center;gap:10px;padding:6px 0;{{ !$loop->last ? 'border-bottom:1px solid #1A2A1A;' : '' }}">
                        <div
                            style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">
                            {{ $sensor->id }}
                        </div>
                        <span style="font-size:12px;color:#7A8E7A;">{{ $sensor->nama_komponen }}</span>
                        <div
                            style="margin-left:auto;width:6px;height:6px;border-radius:50%;flex-shrink:0;{{ $sensor->is_aktif ? 'background:#16C47F;' : 'background:#2A3A2A;border:1px solid #3A4A3A;' }}">
                        </div>
                    </div>
                @empty
                    <div style="font-size:11px;color:#3D4E3D;padding:4px 0;">Belum ada sensor</div>
                @endforelse
            </div>

            {{-- AKTUATOR --}}
            <div style="padding:10px 14px;">
                <div style="font-size:10px;color:#3D4E3D;font-weight:700;letter-spacing:0.1em;margin-bottom:8px;">
                    AKTUATOR
                </div>
                @forelse ($sidebarAktuators as $aktuator)
                    <div class="sidebar-device-item"
                        style="display:flex;align-items:center;gap:10px;padding:6px 0;{{ !$loop->last ? 'border-bottom:1px solid #1A2A1A;' : '' }}">
                        <div
                            style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">
                            {{ $aktuator->id }}
                        </div>
                        <span style="font-size:12px;color:#7A8E7A;">{{ $aktuator->nama_komponen }}</span>
                        <div
                            style="margin-left:auto;width:6px;height:6px;border-radius:50%;flex-shrink:0;{{ $aktuator->status_aktuator === 'on' ? 'background:#16C47F;' : 'background:#2A3A2A;border:1px solid #3A4A3A;' }}">
                        </div>
                    </div>
                @empty
                    <div style="font-size:11px;color:#3D4E3D;padding:4px 0;">Belum ada aktuator</div>
                @endforelse
            </div>
        </div>
    </nav>

    <!-- Sidebar footer -->
    <div style="padding:16px;border-top:1px solid #1A2A1A;margin-top:8px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div
                style="width:34px;height:34px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;flex-shrink:0;">
                G</div>
            <div style="flex:1;min-width:0;">
                <div
                    style="font-size:13px;font-weight:600;color:#C4D4C4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    Admin Gdronic</div>
                <div style="font-size:11px;color:#4D5E4D;">Super Admin</div>
            </div>
            <button style="background:none;border:none;cursor:pointer;color:#4D5E4D;padding:4px;"
                onclick="window.location.href = '/logout'">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</aside>
