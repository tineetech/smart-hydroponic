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
    <button class="mobile-nav-btn" id="mnav-profile" onclick="window.location.href = '{{ route('profile.edit') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
        </svg>
        Profil
    </button>
    <button class="mobile-nav-btn" id="mnav-notifikasi" onclick="window.location.href = '{{ route('kelola-notifikasi.index') }}'">
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Notifikasi
    </button>
</nav>
