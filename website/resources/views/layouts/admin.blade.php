<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gdronic — Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style type="text/tailwindcss">
        @theme {
            --color-primary: #A8F04A;
            --color-primary-dark: #8AD636;
            --color-primary-light: #C4F87A;
            --color-bg-base: #0D0F0D;
            --color-bg-surface: #131613;
            --color-bg-card: #181C18;
            --color-bg-card2: #1E2420;
            --color-bg-sidebar: #111411;
            --color-border: #252C25;
            --color-text-muted: #5A6B5A;
            --color-text-secondary: #8A9E8A;
        }
    </style>
    
    <style>
        /* ════════════════════════════════════════════════════════════════
           ANTI-OVERFLOW BASE — kunci di SETIAP level supaya horizontal
           scroll tidak bisa "bocor" naik ke body, dari manapun sumbernya.
           ════════════════════════════════════════════════════════════════ */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        * {
            box-sizing: border-box;
            min-width: 0; /* kunci: flex/grid children tidak boleh memaksa lebar > container */
        }

        img, svg, canvas, table {
            max-width: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0D0F0D;
            color: #E8F0E8;
            margin: 0;
            padding: 0;
            width: 100%;
            position: relative;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #131613;
        }

        ::-webkit-scrollbar-thumb {
            background: #2A3A2A;
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #A8F04A44;
        }

        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            max-width: 80vw; /* di hp sangat kecil, jangan sampai sidebar sendiri overflow */
            background: #111411;
            border-right: 1px solid #1E2C1E;
            z-index: 50;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            transform: translateX(-100%);
        }

        #sidebar.open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0);
            }
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 40;
            backdrop-filter: blur(2px);
        }

        #sidebar-overlay.open {
            display: block;
        }

        @media (min-width: 1024px) {
            #sidebar-overlay {
                display: none !important;
            }
        }

        #main-content {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        @media (min-width: 1024px) {
            #main-content {
                margin-left: 260px;
                width: calc(100% - 260px);
            }
        }

        /* Nav items */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            color: #6B7F6B;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            margin: 2px 12px;
        }

        .nav-item:hover {
            background: #1A231A;
            color: #C4F878;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #A8F04A18, #A8F04A0A);
            color: #A8F04A;
            border: 1px solid #A8F04A22;
        }

        .nav-item .nav-icon {
            width: 36px;
            height: 36px;
            background: #1A231A;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            transition: all 0.2s;
        }

        .nav-item.active .nav-icon {
            background: #A8F04A22;
        }

        .nav-item:hover .nav-icon {
            background: #A8F04A18;
        }

        /* Cards */
        .stat-card {
            background: #181C18;
            border: 1px solid #1E2C1E;
            border-radius: 16px;
            padding: 20px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            min-width: 0;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #A8F04A44, transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            border-color: #A8F04A33;
            transform: translateY(-1px);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        /* Badge live */
        .badge-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #A8F04A18;
            border: 1px solid #A8F04A44;
            border-radius: 99px;
            padding: 4px 10px;
            font-size: 11px;
            color: #A8F04A;
            font-weight: 600;
            letter-spacing: 0.05em;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .badge-live::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #A8F04A;
            border-radius: 50%;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.8);
            }
        }

        /* Status badges */
        .badge-ok {
            background: #A8F04A18;
            border: 1px solid #A8F04A44;
            color: #A8F04A;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-warn {
            background: #F0A84A18;
            border: 1px solid #F0A84A44;
            color: #F0A84A;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-danger {
            background: #F04A4A18;
            border: 1px solid #F04A4A44;
            color: #F04A4A;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-off {
            background: #2A2A2A;
            border: 1px solid #3A3A3A;
            color: #6B7F6B;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Progress bar */
        .progress-track {
            background: #1E2C1E;
            border-radius: 99px;
            height: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #A8F04A, #C4F878);
            transition: width 1s ease;
        }

        /* Toggle switch */
        .toggle-wrap {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .toggle-wrap input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #2A3A2A;
            border-radius: 24px;
            transition: 0.3s;
            border: 1px solid #3A4A3A;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background: #5A6B5A;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-wrap input:checked+.toggle-slider {
            background: #A8F04A22;
            border-color: #A8F04A55;
        }

        .toggle-wrap input:checked+.toggle-slider:before {
            transform: translateX(20px);
            background: #A8F04A;
        }

        /* Chart container */
        .chart-container {
            position: relative;
            height: 200px;
            width: 100%;
            max-width: 100%;
        }

        .chart-container canvas {
            max-width: 100% !important;
        }

        /* Gauge circle */
        .gauge-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .gauge-svg {
            transform: rotate(-90deg);
        }

        .gauge-center {
            position: absolute;
            text-align: center;
        }

        /* Sidebar section label */
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #3D4E3D;
            padding: 16px 16px 6px;
            text-transform: uppercase;
        }

        /* Header */
        #header {
            position: sticky;
            top: 0;
            z-index: 30;
            background: #0D0F0DCC;
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #1A2A1A;
            min-height: 64px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 12px;
            width: 100%;
            max-width: 100%;
        }

        @media (min-width: 640px) {
            #header {
                padding: 0 24px;
                gap: 16px;
            }
        }

        /* Notification dot */
        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #A8F04A;
            border-radius: 50%;
            border: 2px solid #0D0F0D;
        }

        /* Sensor value animation */
        @keyframes value-update {
            0% {
                color: #A8F04A;
            }

            100% {
                color: #E8F0E8;
            }
        }

        .value-flash {
            animation: value-update 0.8s ease;
        }

        /* Actuator card */
        .actuator-card {
            background: #181C18;
            border: 1px solid #1E2C1E;
            border-radius: 14px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: all 0.2s;
            min-width: 0;
        }

        .actuator-card:hover {
            border-color: #A8F04A22;
        }

        .actuator-card.on {
            border-color: #A8F04A33;
            background: linear-gradient(135deg, #181C18, #1A2018);
        }

        /* Alert item */
        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            background: #131613;
            border-radius: 10px;
            border-left: 3px solid;
            margin-bottom: 8px;
        }

        .alert-item.info {
            border-color: #4A8AF0;
        }

        .alert-item.warn {
            border-color: #F0A84A;
        }

        .alert-item.ok {
            border-color: #A8F04A;
        }

        /* Mobile nav bottom */
        #mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #111411;
            border-top: 1px solid #1E2C1E;
            z-index: 50;
            padding: 8px 0 12px;
            width: 100%;
            max-width: 100%;
        }

        @media (max-width: 767px) {
            #mobile-bottom-nav {
                display: flex;
            }

            body {
                padding-bottom: 72px;
            }
        }

        .mobile-nav-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: #5A6B5A;
            font-size: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
            background: none;
            border: none;
            padding: 0;
        }

        .mobile-nav-btn.active {
            color: #A8F04A;
        }

        .mobile-nav-btn svg {
            width: 20px;
            height: 20px;
        }

        /* Glow effect */
        .glow-primary {
            box-shadow: 0 0 20px #A8F04A22;
        }

        /* ════════════════════════════════════════════════════════════════
           PAGE CONTENT PADDING — responsive, jangan fixed 24px di hp
           ════════════════════════════════════════════════════════════════ */
        #page-content {
            padding: 14px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            min-width: 0;
        }

        @media (min-width: 640px) {
            #page-content {
                padding: 24px;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .tbl-row:hover td {
            background: #1A2A1A !important;
        }

        select option {
            background: #0D0F0D;
            color: #C4D4C4;
        }

        .tbl-row-hidden {
            display: none !important;
        }

        /* ════════════════════════════════════════════════════════════════
           TABEL HISTORI LOG — scroll HANYA di dalam wrapper-nya sendiri,
           tidak boleh mendorong lebar halaman.
           ════════════════════════════════════════════════════════════════ */
        .table-scroll-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
            /* garis bantu visual supaya user tahu bisa digeser di mobile */
            scrollbar-gutter: stable;
        }

        .table-scroll-wrap table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        /* ── Responsive: tablet & mobile ─────────────────────────────────── */
        @media (max-width: 640px) {
            .astat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 10px !important;
            }

            .aktuator-grid {
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 12px !important;
            }

            .agraf-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .asection-head {
                align-items: flex-start !important;
            }

            .hide-on-mobile {
                display: none !important;
            }

            .alog-filter-form {
                width: 100%;
            }

            .alog-filter-form select {
                flex: 1 1 auto;
                min-width: 0;
                max-width: 100%;
            }

            #catatan-modal>div {
                padding: 18px !important;
            }

            .stat-card {
                padding: 14px !important;
            }
        }

        @media (max-width: 380px) {
            .astat-grid {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) !important;
            }
        }

        /* Disable hover-lift effect on touch devices (feels laggy on tap) */
        @media (hover: none) {
            .stat-card:hover {
                transform: none !important;
            }

            .actuator-card:hover {
                border-color: inherit !important;
            }
        }

        /* Toggle gets a touch-friendly hit area on small screens */
        @media (max-width: 640px) {
            .toggle-wrap {
                width: 48px !important;
                height: 26px !important;
            }

            .toggle-wrap input:checked+.toggle-slider:before {
                transform: translateX(22px) !important;
            }
        }

        /* Subtle "saving" state for optimistic toggle while awaiting server confirmation */
        .actuator-card.pending {
            opacity: 0.75;
        }

        .actuator-card.pending [data-field="status-text"]::after {
            content: ' …';
            font-size: 12px;
            color: #5A7A5A;
            font-weight: 500;
        }
    </style>

    @yield('styles')
</head>

<body>

    @include('components.admin.sidebar')

    <!-- MAIN CONTENT -->
    <div id="main-content" style="min-height:100vh; display:flex; flex-direction:column;">

        @yield('content')
    </div>

    @include('components.admin.bottomnav')

    <script>
        // ==============================
        // SIDEBAR TOGGLE
        // ==============================
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebar-overlay');
            sb.classList.toggle('open');
            ov.classList.toggle('open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('open');
        }

        // ==============================
        // PAGE NAVIGATION
        // ==============================
        const pages = {
            'dashboard': {
                title: 'Dashboard',
                breadcrumb: 'Ringkasan sistem hidroponik Anda'
            },
            'monitoring-sensor': {
                title: 'Monitoring Sensor',
                breadcrumb: 'Data real-time semua sensor'
            },
            'monitoring-aktuator': {
                title: 'Monitoring Aktuator',
                breadcrumb: 'Status dan kontrol semua aktuator'
            },
            'kelola-sensor': {
                title: 'Kelola Sensor',
                breadcrumb: 'Konfigurasi dan manajemen sensor'
            },
            'pengaturan': {
                title: 'Pengaturan Sistem',
                breadcrumb: 'Konfigurasi sistem hidroponik'
            }
        };

        function setPage(pageId) {
            // Hide all pages
            document.querySelectorAll('[id^="page-"]').forEach(p => p.style.display = 'none');

            // Show target page
            const target = document.getElementById('page-' + pageId);
            if (target) target.style.display = 'block';

            // Update nav items
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(pageId)) {
                    item.classList.add('active');
                }
            });

            // Update mobile nav
            document.querySelectorAll('.mobile-nav-btn').forEach(b => b.classList.remove('active'));
            const mBtn = document.getElementById('mnav-' + pageId);
            if (mBtn) mBtn.classList.add('active');

            // Update header
            if (pages[pageId]) {
                document.getElementById('page-title').textContent = pages[pageId].title;
                document.getElementById('page-breadcrumb').textContent = pages[pageId].breadcrumb;
            }

            // Close sidebar on mobile
            closeSidebar();

            // Scroll top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ==============================
        // ACTUATOR TOGGLE
        // ==============================
        let actuatorCount = 1;

        function toggleActuator(el, id) {
            const isOn = el.checked;
            const labels = {
                'pompa-ssr': 'ssr-label',
                'ph-down': 'phdown-label',
                'ph-up': 'phup-label'
            };
            const cards = {
                'ph-down': 'card-ph-down',
                'ph-up': 'card-ph-up'
            };
            const label = document.getElementById(labels[id]);

            if (label) {
                label.textContent = isOn ? 'ON' : 'OFF';
                label.style.color = isOn ? '#A8F04A' : '#5A7A5A';
            }
            if (cards[id]) {
                const card = document.getElementById(cards[id]);
                if (isOn) card.classList.add('on');
                else card.classList.remove('on');
            }

            // Update count
            const checkboxes = document.querySelectorAll('.toggle-wrap input[type="checkbox"]');
            let cnt = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) cnt++;
            });
            const countEl = document.getElementById('aktuator-count');
            if (countEl) countEl.textContent = cnt + '/3';
        }

        // ==============================
        // STATUS BADGE HELPERS
        // Dipakai oleh halaman dashboard maupun monitoring sensor
        // ==============================
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
    </script>
    @yield('scripts')
</body>

</html>
