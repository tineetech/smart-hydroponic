<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Gdronic — Admin Panel')</title>

    <!-- ===== Favicon & App Icons ===== -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo.svg') }}">

    <!-- ===== SEO Meta Tags ===== -->
    <meta name="description" content="Gdronic Admin Panel – Kelola sensor, aktuator, dan data hidroponik secara real-time.">
    <meta name="keywords" content="admin panel, hidroponik, iot, gdronic, dashboard, sensor, aktuator">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style type="text/tailwindcss">
        @theme {
            --color-primary: #16C47F;
            --color-primary-dark: #12A86B;
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
            background: #16C47F44;
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
            color: #1AE08F;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #16C47F18, #16C47F0A);
            color: #16C47F;
            border: 1px solid #16C47F22;
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
            background: #16C47F22;
        }

        .nav-item:hover .nav-icon {
            background: #16C47F18;
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
            background: linear-gradient(90deg, transparent, #16C47F44, transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            border-color: #16C47F33;
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
            background: #16C47F18;
            border: 1px solid #16C47F44;
            border-radius: 99px;
            padding: 4px 10px;
            font-size: 11px;
            color: #16C47F;
            font-weight: 600;
            letter-spacing: 0.05em;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .badge-live::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #16C47F;
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
            background: #16C47F18;
            border: 1px solid #16C47F44;
            color: #16C47F;
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
            background: linear-gradient(90deg, #16C47F, #1AE08F);
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
            background: #16C47F22;
            border-color: #16C47F55;
        }

        .toggle-wrap input:checked+.toggle-slider:before {
            transform: translateX(20px);
            background: #16C47F;
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
            background: #16C47F;
            border-radius: 50%;
            border: 2px solid #0D0F0D;
        }

        /* Sensor value animation */
        @keyframes value-update {
            0% {
                color: #16C47F;
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
            border-color: #16C47F22;
        }

        .actuator-card.on {
            border-color: #16C47F33;
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
            border-color: #16C47F;
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
            color: #16C47F;
        }

        .mobile-nav-btn svg {
            width: 20px;
            height: 20px;
        }

        /* Glow effect */
        .glow-primary {
            box-shadow: 0 0 20px #16C47F22;
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

        /* ═══════ AI CHATBOT POPUP ═══════ */
        #chat-fab {
            position: fixed; bottom: 24px; right: 24px; z-index: 999;
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #16C47F, #12A86B);
            border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 24px rgba(22,196,127,0.4);
            transition: transform .3s, box-shadow .3s;
        }
        #chat-fab:hover { transform: scale(1.1); box-shadow: 0 6px 32px rgba(22,196,127,0.55); }
        #chat-fab i { font-size: 22px; color: #080f0a; }
        @keyframes fab-pulse { 0%,100%{box-shadow:0 4px 24px rgba(22,196,127,0.4)}50%{box-shadow:0 4px 40px rgba(22,196,127,0.7)} }
        #chat-fab.pulse { animation: fab-pulse 2.5s ease-in-out infinite; }
        #chat-popup {
            position: fixed; bottom: 96px; right: 24px; z-index: 998;
            width: 380px; height: 540px; max-height: calc(100vh - 130px);
            background: #0d1a10; border: 1px solid rgba(22,196,127,0.2);
            border-radius: 20px; display: none; flex-direction: column;
            box-shadow: 0 16px 64px rgba(0,0,0,0.6), 0 0 80px rgba(22,196,127,0.05);
            overflow: hidden; transform: translateY(20px); opacity: 0;
            transition: transform .35s cubic-bezier(.4,0,.2,1), opacity .35s ease;
        }
        #chat-popup.open { display: flex; transform: translateY(0); opacity: 1; }
        .chat-header {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 20px; border-bottom: 1px solid rgba(22,196,127,0.12);
            background: rgba(22,196,127,0.04); flex-shrink: 0;
        }
        .chat-avatar {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, #16C47F, #12A86B);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 700; color: #080f0a; flex-shrink: 0;
        }
        .chat-info { flex: 1; min-width: 0; }
        .chat-title { font-size: 14px; font-weight: 700; color: #fff; }
        .chat-status { font-size: 11px; color: #16C47F; font-weight: 500; display: flex; align-items: center; gap: 5px; }
        .chat-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #16C47F; }
        #chat-close {
            width: 32px; height: 32px; border-radius: 10px; border: none;
            background: rgba(255,255,255,0.06); color: #5a7a60; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 16px;
            transition: background .2s, color .2s; flex-shrink: 0;
        }
        #chat-close:hover { background: rgba(255,255,255,0.12); color: #d4ecd9; }
        #chat-messages {
            flex: 1; overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 12px;
        }
        #chat-messages::-webkit-scrollbar { width: 3px; }
        #chat-messages::-webkit-scrollbar-track { background: transparent; }
        #chat-messages::-webkit-scrollbar-thumb { background: rgba(22,196,127,0.3); border-radius: 99px; }
        .msg { display: flex; gap: 8px; max-width: 88%; animation: msg-in .3s ease; }
        .msg.user { align-self: flex-end; flex-direction: row-reverse; }
        @keyframes msg-in { from { opacity:0; transform:translateY(10px) } to { opacity:1; transform:translateY(0) } }
        .msg-bubble {
            padding: 10px 14px; border-radius: 14px; font-size: 13px; line-height: 1.55; word-wrap: break-word;
        }
        .msg.ai .msg-bubble {
            background: #132016; border: 1px solid rgba(22,196,127,0.12); color: #d4ecd9;
            border-top-left-radius: 4px;
        }
        .msg.user .msg-bubble {
            background: #16C47F; color: #080f0a; font-weight: 500;
            border-top-right-radius: 4px;
        }
        .msg.user .msg-bubble p { margin: 0; }
        .msg.ai .msg-bubble p { margin: 0 0 6px; }
        .msg.ai .msg-bubble p:last-child { margin-bottom: 0; }
        .msg.ai .msg-bubble strong { color: #16C47F; }
        .msg.ai .msg-bubble code {
            background: rgba(22,196,127,0.1); border: 1px solid rgba(22,196,127,0.15);
            border-radius: 4px; padding: 1px 5px; font-size: 12px;
        }
        .msg.ai .msg-bubble pre {
            background: #080f0a; border: 1px solid rgba(22,196,127,0.12);
            border-radius: 8px; padding: 10px; margin: 6px 0; overflow-x: auto;
        }
        .msg.ai .msg-bubble pre code { background: none; border: none; padding: 0; }
        .msg.ai .msg-bubble a { color: #16C47F; text-decoration: underline; }
        .msg.ai .msg-bubble ul, .msg.ai .msg-bubble ol { margin: 4px 0; padding-left: 20px; }
        .msg.ai .msg-bubble li { margin-bottom: 3px; }
        .msg-avatar {
            width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;
        }
        .msg-avatar.ai { background: rgba(22,196,127,0.15); color: #16C47F; }
        .msg-avatar.user { background: rgba(255,255,255,0.1); color: #fff; }
        .typing-indicator { display: flex; gap: 4px; align-items: center; padding: 4px 0; }
        .typing-indicator span {
            width: 7px; height: 7px; border-radius: 50%; background: #5a7a60;
            animation: typing-bounce 1.4s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: .2s; }
        .typing-indicator span:nth-child(3) { animation-delay: .4s; }
        @keyframes typing-bounce { 0%,80%,100%{transform:translateY(0);background:#5a7a60}40%{transform:translateY(-6px);background:#16C47F} }
        .chat-input-area {
            display: flex; gap: 8px; padding: 12px 16px 16px;
            border-top: 1px solid rgba(22,196,127,0.08); flex-shrink: 0;
        }
        #chat-input {
            flex: 1; background: #132016; border: 1px solid rgba(22,196,127,0.15);
            border-radius: 14px; padding: 10px 16px; font-size: 13px; color: #d4ecd9;
            outline: none; transition: border-color .2s; font-family: inherit;
        }
        #chat-input:focus { border-color: #16C47F; }
        #chat-input::placeholder { color: #5a7a60; }
        #chat-send {
            width: 42px; height: 42px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, #16C47F, #12A86B); color: #080f0a;
            cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 15px;
            transition: transform .2s, box-shadow .2s; flex-shrink: 0;
        }
        #chat-send:hover { transform: scale(1.05); box-shadow: 0 2px 16px rgba(22,196,127,0.35); }
        #chat-send:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }
        .fallback-note { font-size: 11px; color: #5a7a60; margin-top: 6px; font-style: italic; }
        @media (max-width: 640px) {
            #chat-popup {
                right: 12px; bottom: 88px; width: calc(100vw - 24px); height: calc(100vh - 110px);
                border-radius: 16px; max-height: none;
            }
            #chat-fab { bottom: 20px; right: 20px; width: 52px; height: 52px; }
            #chat-fab i { font-size: 20px; }
            .chat-header { padding: 14px 16px; }
            #chat-messages { padding: 12px 16px; }
            .msg { max-width: 92%; }
            .chat-input-area { padding: 10px 12px 14px; }
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
                label.style.color = isOn ? '#16C47F' : '#5A7A5A';
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

        // ═══════════════════════════════════════════════════
        //  NOTIF DROPDOWN
        // ═══════════════════════════════════════════════════
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            const dropdown = document.getElementById('notif-dropdown');
            if (!wrapper || !dropdown) return;
            const btn = wrapper.querySelector('button');
            if (btn && btn.contains(e.target)) {
                e.stopPropagation();
                const isOpen = dropdown.style.display === 'block';
                dropdown.style.display = isOpen ? 'none' : 'block';
            } else if (!dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        function updateNotifCount(count) {
            const badge = document.getElementById('notif-count-badge');
            const dot = document.querySelector('#notif-wrapper .notif-dot');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count > 99 ? '99+' : count;
                } else {
                    const btn = document.querySelector('#notif-wrapper button');
                    if (btn) {
                        const newBadge = document.createElement('span');
                        newBadge.id = 'notif-count-badge';
                        newBadge.style.cssText = 'position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;background:#F04A4A;border-radius:99px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;border:2px solid #131613;line-height:1;padding:0 4px;';
                        newBadge.textContent = count > 99 ? '99+' : count;
                        btn.appendChild(newBadge);
                    }
                }
                if (dot) dot.style.display = 'none';
            } else {
                if (badge) badge.remove();
                if (dot) dot.style.display = 'none';
            }
        }
    </script>
    @yield('scripts')

    <!-- ═══ AI CHATBOT POPUP HTML ═══ -->
    <button id="chat-fab" class="pulse" onclick="toggleChat()" aria-label="Buka AI Chatbot">
        <i class="fa-solid fa-robot"></i>
    </button>
    <div id="chat-popup">
        <div class="chat-header">
            <div class="chat-avatar">G</div>
            <div class="chat-info">
                <div class="chat-title">Gdronic AI</div>
                <div class="chat-status">Online</div>
            </div>
            <button id="chat-close" onclick="toggleChat()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="chat-messages"></div>
        <div class="chat-input-area">
            <input type="text" id="chat-input" placeholder="Tanya sesuatu tentang hidroponik..." autocomplete="off">
            <button id="chat-send" onclick="sendMessage()" aria-label="Kirim"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>

    <script>
        // ── AI Chatbot ──
        const DEFAULT_GREETINGS = [
            'Halo! Saya <b>Gdronic AI</b>, asisten pintar untuk sistem hidroponik kamu.',
            'Tanya apa saja seputar hidroponik, nutrisi tanaman, atau fitur Gdronic!',
            'Sedang menggali ilmu soal IoT dan smart farming? Yuk diskusi!',
        ];
        let chatOpen = false;
        let chatHistory = [];
        function toggleChat() {
            const popup = document.getElementById('chat-popup');
            const fab = document.getElementById('chat-fab');
            chatOpen = !chatOpen;
            popup.classList.toggle('open', chatOpen);
            fab.classList.remove('pulse');
            if (chatOpen) {
                document.getElementById('chat-input').focus();
                if (document.querySelectorAll('#chat-messages .msg').length === 0) {
                    showDefaultMessages();
                }
            }
        }
        function showDefaultMessages() {
            const container = document.getElementById('chat-messages');
            const msg = document.createElement('div');
            msg.className = 'msg ai';
            msg.innerHTML = '<div class="msg-avatar ai">G</div><div class="msg-bubble">' + DEFAULT_GREETINGS.map(g => '<p>' + g + '</p>').join('') + '<div class="fallback-note">Gdronic AI — Smart Hydroponic</div></div>';
            container.appendChild(msg);
            container.scrollTop = container.scrollHeight;
        }
        function addMessage(role, text) {
            const container = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.className = 'msg ' + role;
            const avatar = role === 'ai' ? 'G' : 'U';
            const content = text.startsWith('<') ? text : '<p>' + text + '</p>';
            div.innerHTML = '<div class="msg-avatar ' + role + '">' + avatar + '</div><div class="msg-bubble">' + content + '</div>';
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
            const plain = div.textContent;
            if (role === 'user') {
                chatHistory.push({ role: 'user', content: plain });
            } else {
                chatHistory.push({ role: 'assistant', content: plain });
            }
        }
        function showTyping() {
            const container = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.className = 'msg ai';
            div.id = 'typing-msg';
            div.innerHTML = '<div class="msg-avatar ai">G</div><div class="msg-bubble"><div class="typing-indicator"><span></span><span></span><span></span></div></div>';
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }
        function hideTyping() {
            const el = document.getElementById('typing-msg');
            if (el) el.remove();
        }
        function sendMessage() {
            const input = document.getElementById('chat-input');
            const text = input.value.trim();
            if (!text) return;
            input.value = '';
            document.getElementById('chat-send').disabled = true;
            addMessage('user', escapeHtml(text));
            showTyping();
            fetch('{{ route('ai.chat') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message: text, history: chatHistory.slice(-20) })
            })
            .then(r => r.json())
            .then(data => {
                hideTyping();
                if (data.success && data.message) {
                    addMessage('ai', renderMarkdown(data.message));
                } else {
                    addMessage('ai', '<p>Maaf, terjadi kesalahan. Silakan coba lagi.</p>');
                }
            })
            .catch(() => {
                hideTyping();
                addMessage('ai', '<p>Maaf, terjadi kesalahan jaringan. Silakan coba lagi.</p>');
            })
            .finally(() => {
                document.getElementById('chat-send').disabled = false;
                document.getElementById('chat-input').focus();
            });
        }
        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML.replace(/\n/g, '<br>');
        }
        function renderMarkdown(text) {
            const div = document.createElement('div');
            div.textContent = text;
            let html = div.innerHTML;
            html = html.replace(/```(\w*)\n?([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
            html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
            html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
            html = html.replace(/\*([^*]+)\*/g, '<em>$1</em>');
            html = html.replace(/__([^_]+)__/g, '<strong>$1</strong>');
            html = html.replace(/_([^_]+)_/g, '<em>$1</em>');
            html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener" class="text-g-lime underline">$1</a>');
            html = html.replace(/\n{2,}/g, '</p><p>');
            html = html.replace(/\n/g, '<br>');
            return '<p>' + html + '</p>';
        }
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('chat-input');
            if (input) {
                input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
            }
        });
    </script>
</body>

</html>
