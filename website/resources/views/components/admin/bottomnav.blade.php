<!-- MOBILE BOTTOM NAV -->
<nav id="mobile-bottom-nav">
    <button class="mobile-nav-btn " id="mnav-dashboard" onclick="window.location.href = '{{ route('admin.dashboard.index') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.9" />
            <rect x="13" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.4" />
            <rect x="2" y="13" width="9" height="9" rx="2" fill="currentColor" opacity="0.4" />
            <rect x="13" y="13" width="9" height="9" rx="2" fill="currentColor" opacity="0.4" />
        </svg>
        Beranda
    </button>
    <button class="mobile-nav-btn" id="mnav-monitoring-sensor" onclick="window.location.href = '{{ route('admin.monitor.sensor.index') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M3 12h2l3-9 4 18 3-9h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        Sensor
    </button>
    <button class="mobile-nav-btn" id="mnav-monitoring-aktuator" onclick="window.location.href = '{{ route('admin.monitor.akuator.index') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3" fill="currentColor" />
            <path d="M19.07 4.93A10 10 0 1 0 21 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        Aktuator
    </button>
    <button class="mobile-nav-btn" id="mnav-kelola-sensor" onclick="window.location.href = '{{ route('kelola-sensor.index') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="currentColor" stroke-width="1.5" />
            <path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        Kelola
    </button>
    <button class="mobile-nav-btn"  id="mnav-pengaturan" onclick="window.location.href = '{{ route('admin.pengaturan.index') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="currentColor" stroke-width="1.5" />
            <path
                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"
                stroke="currentColor" stroke-width="1.5" />
        </svg>
        Pengaturan
    </button>
</nav>
