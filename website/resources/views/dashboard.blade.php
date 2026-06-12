<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gdronic — Admin Panel</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
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
    * { box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background: #0D0F0D;
      color: #E8F0E8;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: #131613; }
    ::-webkit-scrollbar-thumb { background: #2A3A2A; border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: #A8F04A44; }

    /* Sidebar */
    #sidebar {
      position: fixed;
      top: 0; left: 0;
      height: 100vh;
      width: 260px;
      background: #111411;
      border-right: 1px solid #1E2C1E;
      z-index: 50;
      transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
    }
    #sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.7);
      z-index: 40;
      backdrop-filter: blur(2px);
    }
    @media (max-width: 1023px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.open { transform: translateX(0); }
      #sidebar-overlay.open { display: block; }
      #main-content { margin-left: 0 !important; }
    }
    @media (min-width: 1024px) {
      #main-content { margin-left: 260px; }
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
    .nav-item:hover { background: #1A231A; color: #C4F878; }
    .nav-item.active {
      background: linear-gradient(135deg, #A8F04A18, #A8F04A0A);
      color: #A8F04A;
      border: 1px solid #A8F04A22;
    }
    .nav-item .nav-icon {
      width: 36px; height: 36px;
      background: #1A231A;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      font-size: 16px;
      transition: all 0.2s;
    }
    .nav-item.active .nav-icon {
      background: #A8F04A22;
    }
    .nav-item:hover .nav-icon { background: #A8F04A18; }

    /* Cards */
    .stat-card {
      background: #181C18;
      border: 1px solid #1E2C1E;
      border-radius: 16px;
      padding: 20px;
      transition: all 0.2s;
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, #A8F04A44, transparent);
      opacity: 0;
      transition: opacity 0.3s;
    }
    .stat-card:hover { border-color: #A8F04A33; transform: translateY(-1px); }
    .stat-card:hover::before { opacity: 1; }

    /* Badge live */
    .badge-live {
      display: inline-flex; align-items: center; gap: 6px;
      background: #A8F04A18;
      border: 1px solid #A8F04A44;
      border-radius: 99px;
      padding: 4px 10px;
      font-size: 11px;
      color: #A8F04A;
      font-weight: 600;
      letter-spacing: 0.05em;
    }
    .badge-live::before {
      content: '';
      width: 6px; height: 6px;
      background: #A8F04A;
      border-radius: 50%;
      animation: pulse-dot 1.5s infinite;
    }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.5; transform: scale(0.8); }
    }

    /* Status badges */
    .badge-ok { background: #A8F04A18; border: 1px solid #A8F04A44; color: #A8F04A; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
    .badge-warn { background: #F0A84A18; border: 1px solid #F0A84A44; color: #F0A84A; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
    .badge-danger { background: #F04A4A18; border: 1px solid #F04A4A44; color: #F04A4A; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
    .badge-off { background: #2A2A2A; border: 1px solid #3A3A3A; color: #6B7F6B; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }

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
    .toggle-wrap { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-wrap input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
      position: absolute; cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background: #2A3A2A;
      border-radius: 24px;
      transition: 0.3s;
      border: 1px solid #3A4A3A;
    }
    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 18px; width: 18px;
      left: 2px; bottom: 2px;
      background: #5A6B5A;
      border-radius: 50%;
      transition: 0.3s;
    }
    .toggle-wrap input:checked + .toggle-slider { background: #A8F04A22; border-color: #A8F04A55; }
    .toggle-wrap input:checked + .toggle-slider:before { transform: translateX(20px); background: #A8F04A; }

    /* Chart container */
    .chart-container { position: relative; height: 200px; }

    /* Gauge circle */
    .gauge-wrap { position: relative; display: inline-flex; align-items: center; justify-content: center; }
    .gauge-svg { transform: rotate(-90deg); }
    .gauge-center { position: absolute; text-align: center; }

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
      height: 64px;
      display: flex;
      align-items: center;
      padding: 0 24px;
      gap: 16px;
    }

    /* Notification dot */
    .notif-dot {
      position: absolute;
      top: 6px; right: 6px;
      width: 8px; height: 8px;
      background: #A8F04A;
      border-radius: 50%;
      border: 2px solid #0D0F0D;
    }

    /* Sensor value animation */
    @keyframes value-update {
      0% { color: #A8F04A; }
      100% { color: #E8F0E8; }
    }
    .value-flash { animation: value-update 0.8s ease; }

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
    }
    .actuator-card:hover { border-color: #A8F04A22; }
    .actuator-card.on { border-color: #A8F04A33; background: linear-gradient(135deg, #181C18, #1A2018); }

    /* Alert item */
    .alert-item {
      display: flex; align-items: flex-start; gap: 12px;
      padding: 12px;
      background: #131613;
      border-radius: 10px;
      border-left: 3px solid;
      margin-bottom: 8px;
    }
    .alert-item.info { border-color: #4A8AF0; }
    .alert-item.warn { border-color: #F0A84A; }
    .alert-item.ok { border-color: #A8F04A; }

    /* Mobile nav bottom */
    #mobile-bottom-nav {
      display: none;
      position: fixed;
      bottom: 0; left: 0; right: 0;
      background: #111411;
      border-top: 1px solid #1E2C1E;
      z-index: 50;
      padding: 8px 0 12px;
    }
    @media (max-width: 767px) {
      #mobile-bottom-nav { display: flex; }
      body { padding-bottom: 72px; }
    }
    .mobile-nav-btn {
      flex: 1;
      display: flex; flex-direction: column; align-items: center; gap: 4px;
      color: #5A6B5A;
      font-size: 10px;
      font-weight: 500;
      cursor: pointer;
      transition: color 0.2s;
      background: none; border: none; padding: 0;
    }
    .mobile-nav-btn.active { color: #A8F04A; }
    .mobile-nav-btn svg { width: 20px; height: 20px; }

    /* Glow effect */
    .glow-primary { box-shadow: 0 0 20px #A8F04A22; }
  </style>
</head>
<body>

<!-- SIDEBAR OVERLAY -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar">
  <!-- Logo -->
  <div style="padding: 20px 20px 16px; border-bottom: 1px solid #1A2A1A;">
    <div style="display:flex; align-items:center; gap:10px;">
      <div style="width:36px;height:36px;background:linear-gradient(135deg,#A8F04A,#6BC42A);border-radius:10px;display:flex;align-items:center;justify-content:center;">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="#0D1A0D"/></svg>
      </div>
      <div>
        <div style="font-size:16px;font-weight:800;color:#E8F0E8;letter-spacing:-0.02em;">Gdronic<span style="color:#A8F04A;">.</span></div>
        <div style="font-size:10px;color:#4D5E4D;font-weight:500;letter-spacing:0.05em;">ADMIN PANEL</div>
      </div>
    </div>
  </div>

  <!-- System Status -->
  <div style="padding: 12px 16px;">
    <div style="background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;">
      <div style="font-size:11px;color:#5A6B5A;font-weight:600;">STATUS SISTEM</div>
      <div class="badge-live">ONLINE</div>
    </div>
  </div>

  <!-- Nav -->
  <nav style="flex:1;padding:4px 0;">
    <div class="nav-section-label">Menu Utama</div>

    <a class="nav-item active" onclick="setPage('dashboard')">
      <div class="nav-icon">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.9"/><rect x="13" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.5"/><rect x="2" y="13" width="9" height="9" rx="2" fill="currentColor" opacity="0.5"/><rect x="13" y="13" width="9" height="9" rx="2" fill="currentColor" opacity="0.5"/></svg>
      </div>
      Dashboard
    </a>

    <a class="nav-item" onclick="setPage('monitoring-sensor')">
      <div class="nav-icon">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M3 12h2l3-9 4 18 3-9h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      Monitoring Sensor
    </a>

    <a class="nav-item" onclick="setPage('monitoring-aktuator')">
      <div class="nav-icon">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="currentColor"/><path d="M19.07 4.93A10 10 0 1 0 21 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M21 3v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      Monitoring Aktuator
    </a>

    <a class="nav-item" onclick="setPage('kelola-sensor')">
      <div class="nav-icon">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="currentColor" stroke-width="1.5"/><path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </div>
      Kelola Sensor
    </a>

    <a class="nav-item" onclick="setPage('pengaturan')">
      <div class="nav-icon">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="currentColor" stroke-width="1.5"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="1.5"/></svg>
      </div>
      Pengaturan Sistem
    </a>

    <div class="nav-section-label" style="margin-top:8px;">Perangkat</div>

    <div style="margin: 4px 12px; background:#131613; border:1px solid #1A2A1A; border-radius:10px; overflow:hidden;">
      <div style="padding:10px 14px;border-bottom:1px solid #1A2A1A;">
        <div style="font-size:10px;color:#3D4E3D;font-weight:700;letter-spacing:0.1em;margin-bottom:8px;">SENSOR</div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #1A2A1A;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">4</div>
          <span style="font-size:12px;color:#7A8E7A;">DS18B20 Suhu Air</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#A8F04A;border-radius:50%;flex-shrink:0;"></div>
        </div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #1A2A1A;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">5</div>
          <span style="font-size:12px;color:#7A8E7A;">DHT22 Suhu Udara</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#A8F04A;border-radius:50%;flex-shrink:0;"></div>
        </div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #1A2A1A;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">6</div>
          <span style="font-size:12px;color:#7A8E7A;">HC-SR04 Level Air</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#A8F04A;border-radius:50%;flex-shrink:0;"></div>
        </div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #1A2A1A;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">7</div>
          <span style="font-size:12px;color:#7A8E7A;">Sensor pH</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#F0A84A;border-radius:50%;flex-shrink:0;"></div>
        </div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">8</div>
          <span style="font-size:12px;color:#7A8E7A;">Sensor TDS</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#A8F04A;border-radius:50%;flex-shrink:0;"></div>
        </div>
      </div>
      <div style="padding:10px 14px;">
        <div style="font-size:10px;color:#3D4E3D;font-weight:700;letter-spacing:0.1em;margin-bottom:8px;">AKTUATOR</div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #1A2A1A;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">9</div>
          <span style="font-size:12px;color:#7A8E7A;">SSR Pompa Utama</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#A8F04A;border-radius:50%;flex-shrink:0;"></div>
        </div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #1A2A1A;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">10</div>
          <span style="font-size:12px;color:#7A8E7A;">Motor pH Down</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#2A3A2A;border-radius:50%;flex-shrink:0;border:1px solid #3A4A3A;"></div>
        </div>
        <div class="sidebar-device-item" style="display:flex;align-items:center;gap:10px;padding:6px 0;">
          <div style="width:22px;height:22px;background:#1E2C1E;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5A7A5A;">11</div>
          <span style="font-size:12px;color:#7A8E7A;">Motor pH Up</span>
          <div style="margin-left:auto;width:6px;height:6px;background:#2A3A2A;border-radius:50%;flex-shrink:0;border:1px solid #3A4A3A;"></div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Sidebar footer -->
  <div style="padding:16px;border-top:1px solid #1A2A1A;margin-top:8px;">
    <div style="display:flex;align-items:center;gap:10px;">
      <div style="width:34px;height:34px;background:linear-gradient(135deg,#A8F04A,#6BC42A);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;flex-shrink:0;">G</div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:13px;font-weight:600;color:#C4D4C4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Admin Gdronic</div>
        <div style="font-size:11px;color:#4D5E4D;">Super Admin</div>
      </div>
      <button style="background:none;border:none;cursor:pointer;color:#4D5E4D;padding:4px;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </div>
  </div>
</aside>

<!-- MAIN CONTENT -->
<div id="main-content" style="min-height:100vh; display:flex; flex-direction:column;">

  <!-- HEADER -->
  <header id="header">
    <!-- Hamburger (mobile & tablet) -->
    <button onclick="toggleSidebar()" class="lg:hidden" style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>

    <!-- Page Title -->
    <div style="flex:1;min-width:0;">
      <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Dashboard</div>
      <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Ringkasan sistem hidroponik Anda</div>
    </div>

    <!-- Header Right -->
    <div style="display:flex;align-items:center;gap:8px;">
      <!-- Last update -->
      <div style="display:none;" class="md:flex" id="last-update-badge" style="display:flex;align-items:center;gap:6px;background:#131613;border:1px solid #1E2C1E;border-radius:8px;padding:6px 12px;">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#5A7A5A" stroke-width="1.5"/><path d="M12 6v6l4 2" stroke="#5A7A5A" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span style="font-size:11px;color:#5A7A5A;">Update: <span id="last-update" style="color:#A8F04A;">--:--:--</span></span>
      </div>

      <!-- Notif -->
      <button style="background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:8px;cursor:pointer;position:relative;color:#7A9A7A;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <div class="notif-dot"></div>
      </button>

      <!-- Profile -->
      <div style="width:36px;height:36px;background:linear-gradient(135deg,#A8F04A,#6BC42A);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;">G</div>
    </div>
  </header>

  <!-- PAGE CONTENT -->
  <main style="flex:1;padding:24px;max-width:1400px;width:100%;margin:0 auto;" id="page-content">

    <!-- ==================== DASHBOARD PAGE ==================== -->
    <div id="page-dashboard">

      <!-- Welcome Banner -->
      <div style="background:linear-gradient(135deg,#1A2A1A,#131F13);border:1px solid #2A3A2A;border-radius:16px;padding:24px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;position:relative;overflow:hidden;">
        <div style="position:absolute;right:0;top:0;bottom:0;width:300px;background:radial-gradient(ellipse at right,#A8F04A0A,transparent);pointer-events:none;"></div>
        <div>
          <div style="font-size:12px;font-weight:600;color:#A8F04A;letter-spacing:0.08em;margin-bottom:6px;">● SISTEM AKTIF</div>
          <div style="font-size:22px;font-weight:800;color:#E8F0E8;margin-bottom:4px;">Selamat Datang, Admin! 👋</div>
          <div style="font-size:14px;color:#5A7A5A;max-width:500px;">Semua sensor berjalan normal. 4 dari 5 parameter dalam kondisi optimal. Satu peringatan terdeteksi pada level pH.</div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <div style="text-align:center;background:#131613;border:1px solid #1E2C1E;border-radius:12px;padding:14px 20px;">
            <div style="font-size:24px;font-weight:800;color:#A8F04A;" id="uptime-display">6h 42m</div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Uptime</div>
          </div>
          <div style="text-align:center;background:#131613;border:1px solid #1E2C1E;border-radius:12px;padding:14px 20px;">
            <div style="font-size:24px;font-weight:800;color:#E8F0E8;">5/5</div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Sensor Online</div>
          </div>
        </div>
      </div>

      <!-- SENSOR STATS GRID -->
      <div style="margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;">Sensor Real-time</div>
        <div class="badge-live">LIVE</div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:28px;">

        <!-- Suhu Air -->
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:32px;height:32px;background:#A8F04A18;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M12 2a3 3 0 0 0-3 3v7a5 5 0 1 0 6 0V5a3 3 0 0 0-3-3z" stroke="#A8F04A" stroke-width="1.8"/><circle cx="12" cy="17" r="2" fill="#A8F04A"/></svg>
              </div>
              <div>
                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">SUHU AIR</div>
                <div style="font-size:10px;color:#3D4E3D;">DS18B20</div>
              </div>
            </div>
            <span class="badge-ok" id="s-suhu-air-status">Normal</span>
          </div>
          <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-suhu-air">24.3<span style="font-size:18px;color:#A8F04A;font-weight:600;">°C</span></div>
          <div style="margin-top:12px;">
            <div style="display:flex;justify-content:space-between;font-size:10px;color:#4D5E4D;margin-bottom:4px;">
              <span>Optimal: 20–28°C</span><span id="s-suhu-air-pct">78%</span>
            </div>
            <div class="progress-track"><div class="progress-fill" id="s-suhu-air-bar" style="width:78%;"></div></div>
          </div>
        </div>

        <!-- Suhu Udara -->
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:32px;height:32px;background:#4A8AF018;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9z" stroke="#4A8AF0" stroke-width="1.8"/></svg>
              </div>
              <div>
                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">SUHU UDARA</div>
                <div style="font-size:10px;color:#3D4E3D;">DHT22</div>
              </div>
            </div>
            <span class="badge-ok" id="s-suhu-udara-status">Normal</span>
          </div>
          <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-suhu-udara">28.7<span style="font-size:18px;color:#4A8AF0;font-weight:600;">°C</span></div>
          <div style="margin-top:12px;">
            <div style="display:flex;justify-content:space-between;font-size:10px;color:#4D5E4D;margin-bottom:4px;">
              <span>Optimal: 22–32°C</span><span id="s-suhu-udara-pct">68%</span>
            </div>
            <div class="progress-track"><div class="progress-fill" id="s-suhu-udara-bar" style="width:68%;background:linear-gradient(90deg,#4A8AF0,#7AB0FF);"></div></div>
          </div>
        </div>

        <!-- Level Air -->
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:32px;height:32px;background:#4AF0C818;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M12 2C6 9 4 13 4 16a8 8 0 0 0 16 0c0-3-2-7-8-14z" stroke="#4AF0C8" stroke-width="1.8" fill="#4AF0C822"/></svg>
              </div>
              <div>
                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">LEVEL AIR</div>
                <div style="font-size:10px;color:#3D4E3D;">HC-SR04 · Tangki 1</div>
              </div>
            </div>
            <span class="badge-ok" id="s-level-status">Normal</span>
          </div>
          <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-level">72<span style="font-size:18px;color:#4AF0C8;font-weight:600;">%</span></div>
          <div style="margin-top:12px;">
            <div style="display:flex;justify-content:space-between;font-size:10px;color:#4D5E4D;margin-bottom:4px;">
              <span>Min: 30%  Max: 100%</span><span id="s-level-pct">72%</span>
            </div>
            <div class="progress-track"><div class="progress-fill" id="s-level-bar" style="width:72%;background:linear-gradient(90deg,#4AF0C8,#7AFFD8);"></div></div>
          </div>
        </div>

        <!-- pH -->
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:32px;height:32px;background:#F0A84A18;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M8 3v18M8 3c0 0 3 2 6 2s6-2 6-2v9c0 0-3 2-6 2s-6-2-6-2" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <div>
                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">pH LARUTAN</div>
                <div style="font-size:10px;color:#3D4E3D;">Probe pH</div>
              </div>
            </div>
            <span class="badge-warn" id="s-ph-status">Perhatian</span>
          </div>
          <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-ph">7.4<span style="font-size:18px;color:#F0A84A;font-weight:600;">pH</span></div>
          <div style="margin-top:12px;">
            <div style="display:flex;justify-content:space-between;font-size:10px;color:#4D5E4D;margin-bottom:4px;">
              <span>Optimal: 5.5–7.0</span><span id="s-ph-pct">53%</span>
            </div>
            <div class="progress-track"><div class="progress-fill" id="s-ph-bar" style="width:53%;background:linear-gradient(90deg,#F0A84A,#FFCA7A);"></div></div>
          </div>
        </div>

        <!-- TDS -->
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:32px;height:32px;background:#A84AF018;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="#A84AF0" stroke-width="1.8"/><path d="M8 12h8M12 8v8" stroke="#A84AF0" stroke-width="1.8" stroke-linecap="round"/></svg>
              </div>
              <div>
                <div style="font-size:10px;color:#4D5E4D;font-weight:600;letter-spacing:0.05em;">TDS NUTRISI</div>
                <div style="font-size:10px;color:#3D4E3D;">EC/TDS Probe</div>
              </div>
            </div>
            <span class="badge-ok" id="s-tds-status">Normal</span>
          </div>
          <div style="font-size:36px;font-weight:800;color:#E8F0E8;line-height:1;" id="s-tds">1247<span style="font-size:18px;color:#A84AF0;font-weight:600;">ppm</span></div>
          <div style="margin-top:12px;">
            <div style="display:flex;justify-content:space-between;font-size:10px;color:#4D5E4D;margin-bottom:4px;">
              <span>Optimal: 800–1600ppm</span><span id="s-tds-pct">62%</span>
            </div>
            <div class="progress-track"><div class="progress-fill" id="s-tds-bar" style="width:62%;background:linear-gradient(90deg,#A84AF0,#C87AFF);"></div></div>
          </div>
        </div>

      </div>

      <!-- CHARTS + ACTUATOR ROW -->
      <div style="display:grid;grid-template-columns:1fr;gap:20px;margin-bottom:28px;" id="chart-row">

        <!-- pH Chart -->
        <div class="stat-card" style="padding:20px;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
            <div>
              <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Grafik pH & TDS</div>
              <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Riwayat 10 menit terakhir</div>
            </div>
            <div style="display:flex;gap:16px;align-items:center;">
              <div style="display:flex;align-items:center;gap:6px;"><div style="width:10px;height:3px;background:#F0A84A;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">pH</span></div>
              <div style="display:flex;align-items:center;gap:6px;"><div style="width:10px;height:3px;background:#A84AF0;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">TDS</span></div>
              <div class="badge-live">LIVE</div>
            </div>
          </div>
          <div class="chart-container" style="height:180px;">
            <canvas id="chart-ph-tds"></canvas>
          </div>
        </div>

      </div>

      <!-- Bottom Row: Aktuator + Alerts + Temp Chart -->
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-bottom:20px;">

        <!-- Aktuator Status -->
        <div class="stat-card" style="padding:20px;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Status Aktuator</div>
            <button onclick="setPage('monitoring-aktuator')" style="font-size:11px;color:#A8F04A;background:none;border:none;cursor:pointer;font-weight:600;">Lihat Semua →</button>
          </div>

          <!-- SSR Pompa Utama -->
          <div class="actuator-card on" style="margin-bottom:10px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:38px;height:38px;background:#A8F04A18;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid #A8F04A33;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="#A8F04A" stroke-width="1.5"/><path d="M12 8v4l3 3" stroke="#A8F04A" stroke-width="1.5" stroke-linecap="round"/></svg>
              </div>
              <div>
                <div style="font-size:13px;font-weight:600;color:#C4D4C4;">SSR Pompa Utama</div>
                <div style="font-size:11px;color:#5A7A5A;">Relay SSR · Auto Mode</div>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
              <label class="toggle-wrap">
                <input type="checkbox" checked onchange="toggleActuator(this,'pompa-ssr')">
                <span class="toggle-slider"></span>
              </label>
              <span style="font-size:10px;color:#A8F04A;font-weight:600;" id="ssr-label">ON</span>
            </div>
          </div>

          <!-- Motor pH Down -->
          <div class="actuator-card" id="card-ph-down" style="margin-bottom:10px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:38px;height:38px;background:#F04A4A18;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid #F04A4A22;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke="#F04A4A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <div>
                <div style="font-size:13px;font-weight:600;color:#C4D4C4;">Motor pH Down</div>
                <div style="font-size:11px;color:#5A7A5A;">L298N · Manual Mode</div>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
              <label class="toggle-wrap">
                <input type="checkbox" onchange="toggleActuator(this,'ph-down')">
                <span class="toggle-slider"></span>
              </label>
              <span style="font-size:10px;color:#5A7A5A;font-weight:600;" id="phdown-label">OFF</span>
            </div>
          </div>

          <!-- Motor pH Up -->
          <div class="actuator-card" id="card-ph-up">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:38px;height:38px;background:#4AF07018;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid #4AF07022;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7" stroke="#4AF070" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <div>
                <div style="font-size:13px;font-weight:600;color:#C4D4C4;">Motor pH Up</div>
                <div style="font-size:11px;color:#5A7A5A;">L298N · Manual Mode</div>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
              <label class="toggle-wrap">
                <input type="checkbox" onchange="toggleActuator(this,'ph-up')">
                <span class="toggle-slider"></span>
              </label>
              <span style="font-size:10px;color:#5A7A5A;font-weight:600;" id="phup-label">OFF</span>
            </div>
          </div>
        </div>

        <!-- Suhu Chart -->
        <div class="stat-card" style="padding:20px;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
            <div>
              <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Grafik Suhu</div>
              <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Air & Udara · 10 menit</div>
            </div>
            <div style="display:flex;gap:12px;align-items:center;">
              <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:3px;background:#A8F04A;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">Air</span></div>
              <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:3px;background:#4A8AF0;border-radius:99px;"></div><span style="font-size:11px;color:#5A7A5A;">Udara</span></div>
            </div>
          </div>
          <div class="chart-container" style="height:160px;">
            <canvas id="chart-suhu"></canvas>
          </div>
        </div>

        <!-- Alerts / Notifikasi -->
        <div class="stat-card" style="padding:20px;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Notifikasi Sistem</div>
            <span style="background:#A8F04A18;border:1px solid #A8F04A33;border-radius:99px;padding:2px 8px;font-size:11px;color:#A8F04A;font-weight:700;">3</span>
          </div>

          <div class="alert-item warn">
            <div style="width:28px;height:28px;background:#F0A84A18;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#D4B87A;">pH Melebihi Batas Optimal</div>
              <div style="font-size:11px;color:#5A6B5A;margin-top:2px;">pH: 7.4 melebihi batas optimal 7.0. Pertimbangkan penambahan pH Down.</div>
              <div style="font-size:10px;color:#3D4E3D;margin-top:4px;">2 menit lalu</div>
            </div>
          </div>

          <div class="alert-item ok">
            <div style="width:28px;height:28px;background:#A8F04A18;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="#A8F04A" stroke-width="1.8" stroke-linecap="round"/><path d="M22 4 12 14.01l-3-3" stroke="#A8F04A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#A8D47A;">Pompa Utama Aktif</div>
              <div style="font-size:11px;color:#5A6B5A;margin-top:2px;">SSR Pompa Utama berhasil diaktifkan. Mode otomatis berjalan.</div>
              <div style="font-size:10px;color:#3D4E3D;margin-top:4px;">15 menit lalu</div>
            </div>
          </div>

          <div class="alert-item info">
            <div style="width:28px;height:28px;background:#4A8AF018;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#4A8AF0" stroke-width="1.8"/><path d="M12 16v-4M12 8h.01" stroke="#4A8AF0" stroke-width="1.8" stroke-linecap="round"/></svg>
            </div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#7AAAD4;">Data TDS Diperbarui</div>
              <div style="font-size:11px;color:#5A6B5A;margin-top:2px;">TDS nutrisi terbaca 1247 ppm. Dalam batas optimal 800–1600 ppm.</div>
              <div style="font-size:10px;color:#3D4E3D;margin-top:4px;">28 menit lalu</div>
            </div>
          </div>
        </div>

      </div>

      <!-- System Health Row -->
      <div style="background:#131613;border:1px solid #1A2A1A;border-radius:14px;padding:20px;margin-bottom:8px;">
        <div style="font-size:13px;font-weight:700;color:#7A9A7A;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:16px;">Ringkasan Kesehatan Sistem</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;">

          <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
            <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">KONEKSI</div>
            <div style="display:flex;align-items:center;gap:6px;">
              <div style="width:8px;height:8px;background:#A8F04A;border-radius:50%;"></div>
              <span style="font-size:14px;font-weight:700;color:#A8F04A;">Stabil</span>
            </div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">WiFi · -62 dBm</div>
          </div>

          <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
            <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">AKTUATOR AKTIF</div>
            <div style="font-size:24px;font-weight:800;color:#E8F0E8;" id="aktuator-count">1/3</div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">SSR Pompa ON</div>
          </div>

          <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
            <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">PEMBACAAN/MENIT</div>
            <div style="font-size:24px;font-weight:800;color:#E8F0E8;">60</div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">Interval 1s</div>
          </div>

          <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
            <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">PERINGATAN AKTIF</div>
            <div style="font-size:24px;font-weight:800;color:#F0A84A;">1</div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">pH tinggi</div>
          </div>

          <div style="background:#181C18;border-radius:10px;padding:14px;border:1px solid #1E2C1E;">
            <div style="font-size:10px;color:#4D5E4D;margin-bottom:6px;letter-spacing:0.05em;">SKOR KESEHATAN</div>
            <div style="font-size:24px;font-weight:800;color:#A8F04A;">82%</div>
            <div style="font-size:11px;color:#4D5E4D;margin-top:4px;">Sangat Baik</div>
          </div>

        </div>
      </div>

    </div><!-- end page-dashboard -->

    <!-- ========= PLACEHOLDER PAGES ========= -->
    <div id="page-monitoring-sensor" style="display:none;">
      <div style="text-align:center;padding:80px 20px;">
        <div style="font-size:48px;margin-bottom:16px;">📡</div>
        <div style="font-size:22px;font-weight:800;color:#C4D4C4;margin-bottom:8px;">Monitoring Sensor</div>
        <div style="font-size:14px;color:#5A7A5A;">Halaman ini akan dikembangkan pada tahap selanjutnya.</div>
        <div style="margin-top:24px;"><span style="background:#A8F04A18;border:1px solid #A8F04A33;border-radius:8px;padding:8px 20px;font-size:12px;color:#A8F04A;font-weight:600;">Segera Hadir</span></div>
      </div>
    </div>

    <div id="page-monitoring-aktuator" style="display:none;">
      <div style="text-align:center;padding:80px 20px;">
        <div style="font-size:48px;margin-bottom:16px;">⚙️</div>
        <div style="font-size:22px;font-weight:800;color:#C4D4C4;margin-bottom:8px;">Monitoring Aktuator</div>
        <div style="font-size:14px;color:#5A7A5A;">Halaman ini akan dikembangkan pada tahap selanjutnya.</div>
        <div style="margin-top:24px;"><span style="background:#A8F04A18;border:1px solid #A8F04A33;border-radius:8px;padding:8px 20px;font-size:12px;color:#A8F04A;font-weight:600;">Segera Hadir</span></div>
      </div>
    </div>

    <div id="page-kelola-sensor" style="display:none;">
      <div style="text-align:center;padding:80px 20px;">
        <div style="font-size:48px;margin-bottom:16px;">🔧</div>
        <div style="font-size:22px;font-weight:800;color:#C4D4C4;margin-bottom:8px;">Kelola Sensor</div>
        <div style="font-size:14px;color:#5A7A5A;">Halaman ini akan dikembangkan pada tahap selanjutnya.</div>
        <div style="margin-top:24px;"><span style="background:#A8F04A18;border:1px solid #A8F04A33;border-radius:8px;padding:8px 20px;font-size:12px;color:#A8F04A;font-weight:600;">Segera Hadir</span></div>
      </div>
    </div>

    <div id="page-pengaturan" style="display:none;">
      <div style="text-align:center;padding:80px 20px;">
        <div style="font-size:48px;margin-bottom:16px;">⚙️</div>
        <div style="font-size:22px;font-weight:800;color:#C4D4C4;margin-bottom:8px;">Pengaturan Sistem</div>
        <div style="font-size:14px;color:#5A7A5A;">Halaman ini akan dikembangkan pada tahap selanjutnya.</div>
        <div style="margin-top:24px;"><span style="background:#A8F04A18;border:1px solid #A8F04A33;border-radius:8px;padding:8px 20px;font-size:12px;color:#A8F04A;font-weight:600;">Segera Hadir</span></div>
      </div>
    </div>

  </main>
</div>

<!-- MOBILE BOTTOM NAV -->
<nav id="mobile-bottom-nav">
  <button class="mobile-nav-btn active" onclick="setPage('dashboard')" id="mnav-dashboard">
    <svg fill="none" viewBox="0 0 24 24"><rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.9"/><rect x="13" y="2" width="9" height="9" rx="2" fill="currentColor" opacity="0.4"/><rect x="2" y="13" width="9" height="9" rx="2" fill="currentColor" opacity="0.4"/><rect x="13" y="13" width="9" height="9" rx="2" fill="currentColor" opacity="0.4"/></svg>
    Beranda
  </button>
  <button class="mobile-nav-btn" onclick="setPage('monitoring-sensor')" id="mnav-monitoring-sensor">
    <svg fill="none" viewBox="0 0 24 24"><path d="M3 12h2l3-9 4 18 3-9h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Sensor
  </button>
  <button class="mobile-nav-btn" onclick="setPage('monitoring-aktuator')" id="mnav-monitoring-aktuator">
    <svg fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="currentColor"/><path d="M19.07 4.93A10 10 0 1 0 21 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    Aktuator
  </button>
  <button class="mobile-nav-btn" onclick="setPage('kelola-sensor')" id="mnav-kelola-sensor">
    <svg fill="none" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="currentColor" stroke-width="1.5"/><path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    Kelola
  </button>
  <button class="mobile-nav-btn" onclick="setPage('pengaturan')" id="mnav-pengaturan">
    <svg fill="none" viewBox="0 0 24 24"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="currentColor" stroke-width="1.5"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="1.5"/></svg>
    Pengaturan
  </button>
</nav>

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
  'dashboard': { title: 'Dashboard', breadcrumb: 'Ringkasan sistem hidroponik Anda' },
  'monitoring-sensor': { title: 'Monitoring Sensor', breadcrumb: 'Data real-time semua sensor' },
  'monitoring-aktuator': { title: 'Monitoring Aktuator', breadcrumb: 'Status dan kontrol semua aktuator' },
  'kelola-sensor': { title: 'Kelola Sensor', breadcrumb: 'Konfigurasi dan manajemen sensor' },
  'pengaturan': { title: 'Pengaturan Sistem', breadcrumb: 'Konfigurasi sistem hidroponik' }
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
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ==============================
// ACTUATOR TOGGLE
// ==============================
let actuatorCount = 1;
function toggleActuator(el, id) {
  const isOn = el.checked;
  const labels = { 'pompa-ssr': 'ssr-label', 'ph-down': 'phdown-label', 'ph-up': 'phup-label' };
  const cards = { 'ph-down': 'card-ph-down', 'ph-up': 'card-ph-up' };
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
  checkboxes.forEach(cb => { if (cb.checked) cnt++; });
  const countEl = document.getElementById('aktuator-count');
  if (countEl) countEl.textContent = cnt + '/3';
}

// ==============================
// SENSOR SIMULATION DATA
// ==============================
const sensorData = {
  suhuAir: 24.3,
  suhuUdara: 28.7,
  level: 72,
  ph: 7.4,
  tds: 1247
};

const historyLen = 20;
const labels = Array.from({ length: historyLen }, (_, i) => {
  const d = new Date(Date.now() - (historyLen - 1 - i) * 30000);
  return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
});

const phHistory = Array.from({ length: historyLen }, (_, i) => +(6.8 + Math.sin(i * 0.3) * 0.5 + Math.random() * 0.2).toFixed(2));
const tdsHistory = Array.from({ length: historyLen }, (_, i) => Math.round(1200 + Math.sin(i * 0.2) * 60 + Math.random() * 30));
const suhuAirHistory = Array.from({ length: historyLen }, (_, i) => +(24 + Math.sin(i * 0.15) * 1 + Math.random() * 0.3).toFixed(1));
const suhuUdaraHistory = Array.from({ length: historyLen }, (_, i) => +(28 + Math.sin(i * 0.2) * 1.5 + Math.random() * 0.4).toFixed(1));

// ==============================
// CHART SETUP
// ==============================
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
      titleColor: '#A8F04A',
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

// pH & TDS Chart
const ctxPh = document.getElementById('chart-ph-tds').getContext('2d');
const chartPhTds = new Chart(ctxPh, {
  type: 'line',
  data: {
    labels: [...labels],
    datasets: [
      {
        label: 'pH',
        data: [...phHistory],
        borderColor: '#F0A84A',
        backgroundColor: 'rgba(240,168,74,0.08)',
        borderWidth: 2,
        pointRadius: 2,
        pointHoverRadius: 5,
        tension: 0.4,
        fill: true,
        yAxisID: 'yPh'
      },
      {
        label: 'TDS (ppm)',
        data: [...tdsHistory],
        borderColor: '#A84AF0',
        backgroundColor: 'rgba(168,74,240,0.06)',
        borderWidth: 2,
        pointRadius: 2,
        pointHoverRadius: 5,
        tension: 0.4,
        fill: true,
        yAxisID: 'yTds'
      }
    ]
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

// Suhu Chart
const ctxSuhu = document.getElementById('chart-suhu').getContext('2d');
const chartSuhu = new Chart(ctxSuhu, {
  type: 'line',
  data: {
    labels: [...labels],
    datasets: [
      {
        label: 'Suhu Air (°C)',
        data: [...suhuAirHistory],
        borderColor: '#A8F04A',
        backgroundColor: 'rgba(168,240,74,0.07)',
        borderWidth: 2,
        pointRadius: 2,
        pointHoverRadius: 5,
        tension: 0.4,
        fill: true
      },
      {
        label: 'Suhu Udara (°C)',
        data: [...suhuUdaraHistory],
        borderColor: '#4A8AF0',
        backgroundColor: 'rgba(74,138,240,0.07)',
        borderWidth: 2,
        pointRadius: 2,
        pointHoverRadius: 5,
        tension: 0.4,
        fill: true
      }
    ]
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

// ==============================
// REAL-TIME SIMULATION
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

function updateSensorDisplay() {
  // Suhu Air
  const sa = sensorData.suhuAir;
  document.getElementById('s-suhu-air').innerHTML = sa.toFixed(1) + '<span style="font-size:18px;color:#A8F04A;font-weight:600;">°C</span>';
  const saPct = Math.min(100, Math.max(0, ((sa - 15) / 20) * 100)).toFixed(0);
  document.getElementById('s-suhu-air-pct').textContent = saPct + '%';
  document.getElementById('s-suhu-air-bar').style.width = saPct + '%';
  const saS = getStatus(sa, 15, 35, 20, 28);
  setStatus('s-suhu-air-status', saS, saS === 'ok' ? 'Normal' : saS === 'warn' ? 'Perhatian' : 'Kritis');

  // Suhu Udara
  const su = sensorData.suhuUdara;
  document.getElementById('s-suhu-udara').innerHTML = su.toFixed(1) + '<span style="font-size:18px;color:#4A8AF0;font-weight:600;">°C</span>';
  const suPct = Math.min(100, Math.max(0, ((su - 15) / 25) * 100)).toFixed(0);
  document.getElementById('s-suhu-udara-pct').textContent = suPct + '%';
  document.getElementById('s-suhu-udara-bar').style.width = suPct + '%';
  const suS = getStatus(su, 18, 38, 22, 32);
  setStatus('s-suhu-udara-status', suS, suS === 'ok' ? 'Normal' : suS === 'warn' ? 'Perhatian' : 'Kritis');

  // Level Air
  const lv = sensorData.level;
  document.getElementById('s-level').innerHTML = Math.round(lv) + '<span style="font-size:18px;color:#4AF0C8;font-weight:600;">%</span>';
  document.getElementById('s-level-pct').textContent = Math.round(lv) + '%';
  document.getElementById('s-level-bar').style.width = lv + '%';
  const lvS = getStatus(lv, 20, 100, 35, 95);
  setStatus('s-level-status', lvS, lvS === 'ok' ? 'Normal' : lvS === 'warn' ? 'Perhatian' : 'Kritis');

  // pH
  const ph = sensorData.ph;
  document.getElementById('s-ph').innerHTML = ph.toFixed(1) + '<span style="font-size:18px;color:#F0A84A;font-weight:600;">pH</span>';
  const phPct = Math.min(100, Math.max(0, (ph / 14) * 100)).toFixed(0);
  document.getElementById('s-ph-pct').textContent = phPct + '%';
  document.getElementById('s-ph-bar').style.width = phPct + '%';
  const phS = getStatus(ph, 4, 10, 5.5, 7.0);
  setStatus('s-ph-status', phS, phS === 'ok' ? 'Normal' : phS === 'warn' ? 'Perhatian' : 'Kritis');

  // TDS
  const tds = sensorData.tds;
  document.getElementById('s-tds').innerHTML = Math.round(tds) + '<span style="font-size:18px;color:#A84AF0;font-weight:600;">ppm</span>';
  const tdsPct = Math.min(100, Math.max(0, (tds / 2500) * 100)).toFixed(0);
  document.getElementById('s-tds-pct').textContent = tdsPct + '%';
  document.getElementById('s-tds-bar').style.width = tdsPct + '%';
  const tdsS = getStatus(tds, 400, 2200, 800, 1600);
  setStatus('s-tds-status', tdsS, tdsS === 'ok' ? 'Normal' : tdsS === 'warn' ? 'Perhatian' : 'Kritis');
}

function updateCharts(ts) {
  // Push new data
  chartPhTds.data.labels.push(ts);
  chartPhTds.data.labels.shift();
  chartPhTds.data.datasets[0].data.push(+sensorData.ph.toFixed(2));
  chartPhTds.data.datasets[0].data.shift();
  chartPhTds.data.datasets[1].data.push(Math.round(sensorData.tds));
  chartPhTds.data.datasets[1].data.shift();
  chartPhTds.update('none');

  chartSuhu.data.labels.push(ts);
  chartSuhu.data.labels.shift();
  chartSuhu.data.datasets[0].data.push(+sensorData.suhuAir.toFixed(1));
  chartSuhu.data.datasets[0].data.shift();
  chartSuhu.data.datasets[1].data.push(+sensorData.suhuUdara.toFixed(1));
  chartSuhu.data.datasets[1].data.shift();
  chartSuhu.update('none');
}

function simulateSensors() {
  const now = new Date();
  const ts = now.toLocaleTimeString('id-ID');

  // Gentle random walk
  sensorData.suhuAir = +Math.max(18, Math.min(34, sensorData.suhuAir + (Math.random() - 0.5) * 0.3)).toFixed(1);
  sensorData.suhuUdara = +Math.max(20, Math.min(38, sensorData.suhuUdara + (Math.random() - 0.5) * 0.4)).toFixed(1);
  sensorData.level = +Math.max(20, Math.min(100, sensorData.level + (Math.random() - 0.5) * 0.5)).toFixed(1);
  sensorData.ph = +Math.max(5.0, Math.min(8.5, sensorData.ph + (Math.random() - 0.5) * 0.05)).toFixed(2);
  sensorData.tds = +Math.max(600, Math.min(2000, sensorData.tds + (Math.random() - 0.5) * 15)).toFixed(0);

  updateSensorDisplay();
  updateCharts(ts);

  // Update last update time
  const lu = document.getElementById('last-update');
  if (lu) lu.textContent = ts;
}

// Uptime counter
let uptimeSeconds = 6 * 3600 + 42 * 60;
function updateUptime() {
  uptimeSeconds++;
  const h = Math.floor(uptimeSeconds / 3600);
  const m = Math.floor((uptimeSeconds % 3600) / 60);
  const el = document.getElementById('uptime-display');
  if (el) el.textContent = h + 'h ' + m + 'm';
}

// Start simulation
updateSensorDisplay();
setInterval(simulateSensors, 2000);
setInterval(updateUptime, 1000);

// Init last update
document.getElementById('last-update').textContent = new Date().toLocaleTimeString('id-ID');

// ==============================
// RESPONSIVE: chart-row grid
// ==============================
function adjustLayout() {
  const row = document.getElementById('chart-row');
  if (!row) return;
  if (window.innerWidth >= 1024) {
    row.style.gridTemplateColumns = '1fr';
  } else {
    row.style.gridTemplateColumns = '1fr';
  }
}
window.addEventListener('resize', adjustLayout);
adjustLayout();

console.log('%c🌿 Gdronic Admin Panel v1.0', 'color:#A8F04A;font-size:16px;font-weight:bold;');
console.log('%cSistem monitoring IoT hidroponik siap.', 'color:#5A7A5A;');
</script>
</body>
</html>
