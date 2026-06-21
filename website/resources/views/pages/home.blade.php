<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gdronic – Smart Hydroponic System | IoT Hidroponik Otomatis</title>

  <!-- ===== Favicon & App Icons ===== -->
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo.svg') }}">

  <!-- ===== SEO Meta Tags ===== -->
  <meta name="description" content="Gdronic – Smart Hydroponic System. Kelola tanaman hidroponik dari mana saja. Sensor real-time, kontrol otomatis, dan AI chatbot dalam satu platform IoT berbasis ESP32 & Laravel.">
  <meta name="keywords" content="hidroponik, smart hydroponic, iot, iot hidroponik, smart farming, gdronic, tanaman, sensor, otomatis, ai chatbot, esp32, laravel, monitoring tanaman, pertanian urban">
  <meta name="author" content="Justine – SMKN 4 Bogor">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">

  <!-- ===== Open Graph (Facebook, LinkedIn, etc.) ===== -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="Gdronic – Smart Hydroponic System">
  <meta property="og:description" content="Kelola tanaman hidroponik dari mana saja dengan sensor real-time, kontrol otomatis, dan AI chatbot dalam satu platform IoT.">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('img/logo.png') }}">
  <meta property="og:image:width" content="512">
  <meta property="og:image:height" content="512">
  <meta property="og:site_name" content="Gdronic">
  <meta property="og:locale" content="id_ID">

  <!-- ===== Twitter Card ===== -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Gdronic – Smart Hydroponic System">
  <meta name="twitter:description" content="Kelola tanaman hidroponik dari mana saja dengan sensor real-time, kontrol otomatis, dan AI chatbot.">
  <meta name="twitter:image" content="{{ asset('img/logo.png') }}">

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'g-black':    '#080f0a',
            'g-dark':     '#0d1a10',
            'g-mid':      '#132016',
            'g-lime':     '#16C47F',
            'g-lime2':    '#1AE08F',
            'g-teal':     '#12A86B',
            'g-text':     '#d4ecd9',
            'g-muted':    '#5a7a60',
          },
          fontFamily: {
            sans:  ['Space Grotesk', 'sans-serif'],
            mono:  ['Space Mono', 'monospace'],
          },
        }
      }
    }
  </script>
  <style>
    html { scroll-behavior: smooth; }
    body { 
      background: #080f0a; 
      color: #d4ecd9; 
      font-family: 'Space Grotesk', sans-serif;
      overflow-x: hidden;
    }

    * {
      box-sizing: border-box;
    }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #0d1a10; }
    ::-webkit-scrollbar-thumb { background: #16C47F; border-radius: 99px; }

    .glow { text-shadow: 0 0 40px rgba(22,196,127,0.5), 0 0 80px rgba(22,196,127,0.2); }

    @keyframes scan {
      0%   { transform: translateY(-100%); opacity: 0; }
      10%  { opacity: 0.05; }
      90%  { opacity: 0.05; }
      100% { transform: translateY(200vh); opacity: 0; }
    }
    .scan-line {
      position: absolute; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent, #16C47F, transparent);
      animation: scan 7s linear infinite;
      pointer-events: none;
    }

    @keyframes blink { 0%,100%{opacity:1}50%{opacity:0.25} }
    .blink { animation: blink 2.2s ease-in-out infinite; }

    .feat-card { transition: transform .3s, box-shadow .3s; }
    .feat-card:hover { transform: translateY(-6px); box-shadow: 0 20px 60px rgba(22,196,127,0.1); }

    @keyframes orbit {
      from { transform: rotate(0deg) translateX(90px) rotate(0deg); }
      to   { transform: rotate(360deg) translateX(90px) rotate(-360deg); }
    }
    .orbit-dot {
      animation: orbit 10s linear infinite;
      position: absolute;
      width: 8px; height: 8px;
      background: #16C47F; border-radius: 50%;
      box-shadow: 0 0 12px rgba(22,196,127,0.8);
    }

    .navbar-blur { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }

    @keyframes cursor-blink { 0%,100%{opacity:1}50%{opacity:0} }
    .cursor { animation: cursor-blink 1s step-end infinite; }

    .reveal { opacity: 0; transform: translateY(36px); transition: opacity .75s ease, transform .75s ease; }
    .reveal.visible { opacity: 1; transform: none; }

    .grad-border {
      background: linear-gradient(135deg, #132016, #0d1a10);
      border: 1px solid rgba(22,196,127,0.12);
      transition: border-color .3s;
    }
    .grad-border:hover { border-color: rgba(22,196,127,0.35); }

    .ph-bar { transition: width 1.4s cubic-bezier(.4,0,.2,1); }

    #particles { position: absolute; inset: 0; pointer-events: none; }

    /* Hero full viewport */
    #hero {
      min-height: 100vh;
      min-height: 100svh;
    }

    /* Section spacing tokens */
    .section-py { padding-top: 5rem; padding-bottom: 5rem; }
    @media (min-width: 768px) {
      .section-py { padding-top: 9rem; padding-bottom: 9rem; }
    }
    @media (min-width: 1280px) {
      .section-py { padding-top: 10rem; padding-bottom: 10rem; }
    }

    /* Wide container */
    .container-wide {
      max-width: 1400px;
      margin-left: auto;
      margin-right: auto;
      padding-left: 1.25rem;
      padding-right: 1.25rem;
      width: 100%;
    }
    @media (min-width: 768px) {
      .container-wide { padding-left: 3rem; padding-right: 3rem; }
    }
    @media (min-width: 1280px) {
      .container-wide { padding-left: 5rem; padding-right: 5rem; }
    }

    /* Hide hero CTA on mobile */
    @media (max-width: 767px) {
      .hero-cta { display: none !important; }
    }

    /* FA icon sizing utility */
    .icon-box {
      width: 3rem; height: 3rem;
      border-radius: 0.875rem;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .icon-box-lg {
      width: 3.5rem; height: 3.5rem;
      border-radius: 1rem;
    }

    /* ═══════ SMOOTH TRANSITIONS FOR INTERACTIVE SECTIONS ═══════ */
    
    /* Feature Panel Transitions */
    .feat-panel {
      transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feat-panel.hiding {
      opacity: 0;
      transform: translateY(20px);
      pointer-events: none;
    }
    
    .feat-panel.showing {
      opacity: 1;
      transform: translateY(0);
    }

    /* Staggered Content Animation */
    .feat-panel > div > * {
      opacity: 0;
      transform: translateY(15px);
      transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feat-panel.showing > div > *:nth-child(1) { transition-delay: 0.1s; }
    .feat-panel.showing > div > *:nth-child(2) { transition-delay: 0.2s; }
    .feat-panel.showing > div > *:nth-child(3) { transition-delay: 0.3s; }
    .feat-panel.showing > div > *:nth-child(4) { transition-delay: 0.4s; }

    .feat-panel.showing > div > * {
      opacity: 1;
      transform: translateY(0);
    }

    /* Step Content Transitions */
    #step-content {
      transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #step-content.transitioning {
      opacity: 0;
      transform: scale(0.98) translateY(10px);
    }

    #step-content.active {
      opacity: 1;
      transform: scale(1) translateY(0);
    }

    /* Tab Button Smooth Transitions */
    .feat-tab, .step-btn {
      transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .feat-tab::before, .step-btn::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(22,196,127,0.1), rgba(18,168,107,0.1));
      opacity: 0;
      transition: opacity 0.35s ease;
      border-radius: inherit;
    }

    .feat-tab:hover::before, .step-btn:hover::before {
      opacity: 1;
    }

    .feat-tab.active, .step-btn.active {
      transform: scale(1.05);
      box-shadow: 0 4px 16px rgba(22,196,127,0.3);
    }

    /* Content Reveal Elements */
    .content-reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* List Items Stagger */
    .stagger-list li {
      opacity: 0;
      transform: translateX(-10px);
      transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stagger-list.animate li:nth-child(1) { transition-delay: 0.1s; }
    .stagger-list.animate li:nth-child(2) { transition-delay: 0.2s; }
    .stagger-list.animate li:nth-child(3) { transition-delay: 0.3s; }
    .stagger-list.animate li:nth-child(4) { transition-delay: 0.4s; }
    .stagger-list.animate li:nth-child(5) { transition-delay: 0.5s; }

    .stagger-list.animate li {
      opacity: 1;
      transform: translateX(0);
    }

    /* Card Fade In */
    .card-fade {
      opacity: 0;
      transform: scale(0.95);
      transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-fade.visible {
      opacity: 1;
      transform: scale(1);
    }

    /* Smooth Height Transition */
    .height-transition {
      transition: height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
    }

    /* Mobile Dashboard Adjustments */
    @media (max-width: 767px) {
      .hero-dashboard {
        max-width: 100%;
      }
      
      .sensor-grid-mobile {
        gap: 0.5rem;
      }
      
      .sensor-grid-mobile > div {
        padding: 0.75rem;
      }
      
      .sensor-grid-mobile p:first-child {
        margin-bottom: 0.5rem;
      }
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

    @keyframes fab-pulse {
      0%, 100% { box-shadow: 0 4px 24px rgba(22,196,127,0.4); }
      50% { box-shadow: 0 4px 40px rgba(22,196,127,0.7); }
    }
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
    @keyframes msg-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

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
      border-radius: 4px; padding: 1px 5px; font-size: 12px; font-family: 'Space Mono', monospace;
    }
    .msg.ai .msg-bubble pre {
      background: #080f0a; border: 1px solid rgba(22,196,127,0.12);
      border-radius: 8px; padding: 10px; margin: 6px 0; overflow-x: auto;
    }
    .msg.ai .msg-bubble pre code { background: none; border: none; padding: 0; }
    .msg.ai .msg-bubble a { color: #16C47F; text-decoration: underline; }
    .msg.ai .msg-bubble p { margin: 0 0 8px; }
    .msg.ai .msg-bubble p:last-child { margin-bottom: 0; }
    .msg.ai .msg-bubble ul, .msg.ai .msg-bubble ol { margin: 4px 0; padding-left: 20px; }
    .msg.ai .msg-bubble li { margin-bottom: 3px; }

    .msg-avatar {
      width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;
    }
    .msg-avatar.ai { background: rgba(22,196,127,0.15); color: #16C47F; }
    .msg-avatar.user { background: rgba(255,255,255,0.1); color: #fff; }

    .typing-indicator {
      display: flex; gap: 4px; align-items: center; padding: 4px 0;
    }
    .typing-indicator span {
      width: 7px; height: 7px; border-radius: 50%; background: #5a7a60;
      animation: typing-bounce 1.4s infinite;
    }
    .typing-indicator span:nth-child(2) { animation-delay: .2s; }
    .typing-indicator span:nth-child(3) { animation-delay: .4s; }
    @keyframes typing-bounce {
      0%,80%,100% { transform: translateY(0); background: #5a7a60; }
      40% { transform: translateY(-6px); background: #16C47F; }
    }

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
        right: 12px; bottom: 88px; width: calc(100vw - 24px);
        height: calc(100vh - 110px); height: calc(100dvh - 110px);
        border-radius: 16px; max-height: none;
      }
      #chat-fab { bottom: 20px; right: 20px; width: 52px; height: 52px; }
      #chat-fab i { font-size: 20px; }
      .chat-header { padding: 14px 16px; }
      #chat-messages { padding: 12px 16px; }
      .msg { max-width: 92%; }
      .chat-input-area { padding: 10px 12px 14px; }
    }
  </style>
</head>
<body>

<!-- ═══════════════════════ NAVBAR ═══════════════════════ -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
  <div id="nav-inner" class="transition-all duration-500 border-b border-transparent">
    <div class="container-wide py-4 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <div class="relative w-9 h-9">
          <div class="absolute inset-0 rounded-lg bg-g-lime/20 border border-g-lime/40"></div>
          <svg class="absolute inset-0 m-auto w-5 h-5" fill="none" viewBox="0 0 24 24">
            <path d="M12 2C6 2 2 8 2 14c0 4 2.5 7 6 8l1-3c-2-.8-3-2.8-3-5 0-4 2.7-8 6-8s6 4 6 8c0 2.2-1 4.2-3 5l1 3c3.5-1 6-4 6-8 0-6-4-12-10-12z" fill="#16C47F"/>
            <circle cx="12" cy="14" r="2.5" fill="#16C47F"/>
          </svg>
        </div>
        <span class="text-xl font-bold text-white tracking-tight">Gdronic<span class="text-g-lime">.</span></span>
      </div>
      <!-- Nav links desktop -->
      <div class="hidden md:flex items-center gap-10 text-sm font-medium text-g-muted">
        <a href="#features" class="hover:text-g-lime transition-colors">Fitur</a>
        <a href="#how" class="hover:text-g-lime transition-colors">Cara Kerja</a>
        <a href="#about" class="hover:text-g-lime transition-colors">Tentang</a>
        <a href="#contact" class="hover:text-g-lime transition-colors">Kontak</a>
      </div>
      <!-- CTA -->
      <div class="flex items-center gap-3">
        <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="hidden md:inline-flex items-center gap-2 text-sm text-g-text hover:text-g-lime transition-colors">
          Masuk
        </a>
        <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="hidden md:inline-flex items-center gap-2 bg-g-lime text-g-black font-semibold text-sm px-5 py-2.5 rounded-full hover:bg-g-lime2 transition-all">
          Mulai Sekarang
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
        <button id="menuBtn" class="md:hidden p-2 text-g-text">
          <i class="fa-solid fa-bars text-lg"></i>
        </button>
      </div>
    </div>
    <!-- Mobile menu -->
    <div id="mobileMenu" class="hidden md:hidden px-6 pb-6 flex flex-col gap-4 text-sm text-g-text pt-4 mt-1">
      <a href="#features" class="hover:text-g-lime py-1">Fitur</a>
      <a href="#how" class="hover:text-g-lime py-1">Cara Kerja</a>
      <a href="#about" class="hover:text-g-lime py-1">Tentang</a>
      <a href="#contact" class="hover:text-g-lime py-1">Kontak</a>
      <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="bg-g-lime text-g-black font-semibold px-5 py-2.5 rounded-full w-fit mt-1">Mulai Sekarang <i class="fa-solid fa-arrow-right text-xs ml-1"></i></a>
    </div>
  </div>
</nav>

<!-- ═══════════════════════ HERO BANNER ═══════════════════════ -->
<section id="hero" class="relative flex items-center overflow-hidden" style="min-height:100vh;min-height:100svh;">
  <div class="absolute inset-0 bg-gradient-to-br from-[#040d06] via-[#091a0d] to-[#030d05]"></div>
  <!-- Grid overlay -->
  <div class="absolute inset-0 opacity-[0.3]"
    style="background-image: url('https://png.pngtree.com/background/20250528/original/pngtree-modern-hydroponic-greenhouse-with-climate-control-system-for-cultivation-of-flowers-picture-image_15809863.jpg'),linear-gradient(#16C47F 1px, transparent 1px), linear-gradient(90deg, #16C47F 1px, transparent 1px); background-size: cover;"></div>
  <div class="scan-line" style="top:0"></div>
  <!-- Glow blobs -->
  <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] rounded-full bg-g-lime/6 blur-[140px] pointer-events-none"></div>
  <div class="absolute bottom-1/3 right-1/4 w-[400px] h-[400px] rounded-full bg-g-teal/5 blur-[120px] pointer-events-none"></div>
  <canvas id="particles"></canvas>

  <div class="relative z-10 container-wide pt-24 pb-12 md:pt-32 md:pb-24 grid lg:grid-cols-2 gap-10 lg:gap-16 xl:gap-20 items-center w-full">
    <!-- Kiri: teks -->
    <div>
      <div class="inline-flex items-center gap-2.5 bg-g-lime/10 border border-g-lime/25 rounded-full px-4 py-2 text-g-lime text-xs font-mono mb-8 md:mb-10">
        {{-- <span class="w-2 h-2 rounded-full bg-g-lime blink"></span> --}}
        Smart Hydroponic System
      </div>

      <h1 class="text-4xl md:text-6xl xl:text-6xl font-bold text-white leading-[1.05] mb-6 md:mb-7">
        Bertani Modern<br/>
        dengan<br/>
        <span class="text-g-lime glow">Gdronic IoT</span>
      </h1>
      <p class="text-g-text/65 text-base md:text-lg xl:text-xl leading-relaxed mb-10 md:mb-12 max-w-lg">
        Kelola tanaman hidroponik dari mana saja. Sensor real-time, kontrol otomatis, dan AI chatbot — semua dalam satu platform.
      </p>

      <div class="hero-cta flex flex-col sm:flex-row gap-4">
        <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="inline-flex items-center justify-center gap-2.5 bg-g-lime text-g-black font-bold px-9 py-4 rounded-full hover:bg-g-lime2 transition-all hover:scale-105 shadow-[0_0_36px_rgba(22,196,127,0.35)]">
          Coba Gratis Sekarang
          <i class="fa-solid fa-arrow-right"></i>
        </a>
        <a href="#how" class="inline-flex items-center justify-center gap-2.5 border border-g-lime/30 text-g-text px-9 py-4 rounded-full hover:border-g-lime hover:text-g-lime transition-all">
          <i class="fa-solid fa-circle-play"></i>
          Lihat Demo
        </a>
      </div>

      <div class="flex flex-wrap gap-8 md:gap-10 mt-10 md:mt-14 border-t border-g-lime/10 pt-8 md:pt-10">
        <div>
          <p class="text-2xl md:text-3xl xl:text-4xl font-bold text-white font-mono">98<span class="text-g-lime">%</span></p>
          <p class="text-xs text-g-muted mt-1.5 uppercase tracking-widest">Akurasi Sensor pH</p>
        </div>
        <div>
          <p class="text-2xl md:text-3xl xl:text-4xl font-bold text-white font-mono">24<span class="text-g-lime">/7</span></p>
          <p class="text-xs text-g-muted mt-1.5 uppercase tracking-widest">Monitoring Real-Time</p>
        </div>
        <div>
          <p class="text-2xl md:text-3xl xl:text-4xl font-bold text-white font-mono">4<span class="text-g-lime">x</span></p>
          <p class="text-xs text-g-muted mt-1.5 uppercase tracking-widest">Lebih Hemat Air</p>
        </div>
      </div>
    </div>

    <!-- Kanan: ilustrasi petani besar + dashboard -->
    <div class="relative flex justify-center lg:justify-end">
      <!-- Farmer large illustration -->
      <div class="absolute -top-16 -right-4 md:-top-24 md:-right-8 w-56 md:w-80 xl:w-96 opacity-70 md:opacity-90 pointer-events-none z-0 select-none">
        <svg viewBox="0 0 200 280" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Cap -->
          <path d="M52 68c0-8 8-14 18-14h60c10 0 18 6 18 14v6H52v-6z" fill="#16C47F" opacity="0.25"/>
          <path d="M44 74h112l-10-18c-5-8-14-12-23-12H77c-9 0-18 4-23 12l-10 18z" fill="#16C47F" opacity="0.45"/>
          <!-- Cap brim -->
          <path d="M42 74h116" stroke="#16C47F" stroke-width="2.5" opacity="0.3"/>
          <!-- Face -->
          <ellipse cx="100" cy="92" rx="34" ry="36" fill="#16C47F" opacity="0.07"/>
          <!-- Eyes -->
          <ellipse cx="86" cy="86" rx="4" ry="4.5" fill="#16C47F" opacity="0.5"/>
          <ellipse cx="114" cy="86" rx="4" ry="4.5" fill="#16C47F" opacity="0.5"/>
          <circle cx="86" cy="85" r="1.5" fill="#0D1A0D" opacity="0.3"/>
          <circle cx="114" cy="85" r="1.5" fill="#0D1A0D" opacity="0.3"/>
          <!-- Smile -->
          <path d="M86 102c6 5 12 7 14 7 2 0 8-2 14-7" stroke="#16C47F" stroke-width="2" stroke-linecap="round" opacity="0.4"/>
          <!-- Body (baju petani) -->
          <path d="M68 128c0-10 14-16 32-16s32 6 32 16v56H68v-56z" fill="#16C47F" opacity="0.1"/>
          <!-- Arms -->
          <path d="M68 134l-20 26M132 134l20 26" stroke="#16C47F" stroke-width="5" stroke-linecap="round" opacity="0.12"/>
          <!-- Left hand - memegang tanaman -->
          <g transform="translate(32,156) scale(0.45)" opacity="0.35">
            <rect x="8" y="30" width="5" height="22" rx="2" fill="#12A86B"/>
            <ellipse cx="10" cy="28" rx="6" ry="8" fill="#16C47F"/>
            <ellipse cx="4" cy="36" rx="5" ry="7" fill="#12A86B" opacity="0.8"/>
            <ellipse cx="16" cy="36" rx="5" ry="7" fill="#12A86B" opacity="0.8"/>
            <ellipse cx="10" cy="20" rx="4" ry="5" fill="#16C47F" opacity="0.7"/>
          </g>
          <!-- Right hand - memegang cangkul/skop -->
          <g transform="translate(152,145) scale(0.45)" opacity="0.25">
            <line x1="12" y1="0" x2="12" y2="50" stroke="#5A7A5A" stroke-width="3" stroke-linecap="round"/>
            <rect x="4" y="44" width="16" height="8" rx="2" fill="#5A7A5A"/>
          </g>
          <!-- Legs -->
          <path d="M84 184v24M116 184v24" stroke="#16C47F" stroke-width="5" stroke-linecap="round" opacity="0.12"/>
          <!-- Boots -->
          <ellipse cx="84" cy="210" rx="10" ry="5" fill="#16C47F" opacity="0.18"/>
          <ellipse cx="116" cy="210" rx="10" ry="5" fill="#16C47F" opacity="0.18"/>
          <!-- Tanaman / daun di samping -->
          <g transform="translate(10,160)" opacity="0.2">
            <path d="M0 50V10" stroke="#16C47F" stroke-width="3" stroke-linecap="round"/>
            <ellipse cx="-6" cy="20" rx="8" ry="14" fill="#16C47F" transform="rotate(-20,-6,20)"/>
            <ellipse cx="8" cy="14" rx="7" ry="12" fill="#12A86B" transform="rotate(15,8,14)"/>
            <ellipse cx="-3" cy="36" rx="6" ry="10" fill="#16C47F" transform="rotate(-10,-3,36)"/>
          </g>
          <!-- Decorative dots -->
          <circle cx="30" cy="30" r="2" fill="#16C47F" opacity="0.15"/>
          <circle cx="170" cy="20" r="1.5" fill="#16C47F" opacity="0.1"/>
          <circle cx="180" cy="100" r="2.5" fill="#16C47F" opacity="0.08"/>
        </svg>
      </div>

      <!-- Dashboard card -->
      <div class="relative w-full max-w-sm xl:max-w-md hero-dashboard z-10">
        <div class="absolute inset-[-50px] rounded-full border border-g-lime/8 items-center justify-center pointer-events-none hidden md:flex">
          <div class="orbit-dot" style="transform-origin: center;"></div>
        </div>
        <div class="grad-border rounded-3xl p-5 md:p-7 xl:p-8 backdrop-blur-sm relative z-10">
          <div class="flex items-center justify-between mb-4 md:mb-6">
            <span class="font-mono text-[10px] md:text-xs text-g-muted uppercase tracking-widest">Live Dashboard</span>
            <span class="flex items-center gap-2 text-[10px] md:text-xs text-g-lime font-mono">
              <span class="w-2 h-2 rounded-full bg-g-lime blink"></span>ONLINE
            </span>
          </div>

          <div class="mb-4 md:mb-5">
            <div class="flex justify-between text-xs mb-2">
              <span class="text-g-muted font-mono">pH Level</span>
              <span id="ph-val" class="text-g-lime font-mono font-bold">6.2</span>
            </div>
            <div class="h-2.5 rounded-full bg-g-dark overflow-hidden">
              <div class="ph-bar h-full rounded-full bg-gradient-to-r from-g-teal to-g-lime" style="width:62%"></div>
            </div>
            <div class="flex justify-between text-[10px] text-g-muted mt-1.5 font-mono">
              <span>0</span><span>Optimal: 5.5–7.0</span><span>14</span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-2 md:gap-3 mb-4 md:mb-5 sensor-grid-mobile">
            <div class="bg-g-black/60 rounded-xl md:rounded-2xl p-3 md:p-4 border border-g-lime/10">
              <p class="text-[9px] md:text-[10px] text-g-muted font-mono uppercase mb-1 md:mb-1.5 tracking-wider">Suhu Air</p>
              <p id="temp-val" class="text-xl md:text-2xl font-bold text-white font-mono">24.3<span class="text-g-lime text-xs md:text-sm">°C</span></p>
            </div>
            <div class="bg-g-black/60 rounded-xl md:rounded-2xl p-3 md:p-4 border border-g-lime/10">
              <p class="text-[9px] md:text-[10px] text-g-muted font-mono uppercase mb-1 md:mb-1.5 tracking-wider">EC Nutrisi</p>
              <p id="ec-val" class="text-xl md:text-2xl font-bold text-white font-mono">1.8<span class="text-g-lime text-xs md:text-sm">mS</span></p>
            </div>
            <div class="bg-g-black/60 rounded-xl md:rounded-2xl p-3 md:p-4 border border-g-lime/10">
              <p class="text-[9px] md:text-[10px] text-g-muted font-mono uppercase mb-1 md:mb-1.5 tracking-wider">Cahaya</p>
              <p id="lux-val" class="text-xl md:text-2xl font-bold text-white font-mono">850<span class="text-g-lime text-xs md:text-sm">lx</span></p>
            </div>
            <div class="bg-g-black/60 rounded-xl md:rounded-2xl p-3 md:p-4 border border-g-teal/20">
              <p class="text-[9px] md:text-[10px] text-g-muted font-mono uppercase mb-1 md:mb-1.5 tracking-wider">Pompa</p>
              <p class="text-xl md:text-2xl font-bold text-g-teal font-mono flex items-center gap-1.5">ON <i class="fa-solid fa-circle text-[8px] md:text-xs blink"></i></p>
            </div>
          </div>

          <div class="bg-g-lime/8 border border-g-lime/20 rounded-xl md:rounded-2xl px-3 py-2 md:px-4 md:py-3 flex items-start gap-2 md:gap-3">
            <i class="fa-solid fa-lightbulb text-g-lime text-xs md:text-sm mt-0.5 shrink-0"></i>
            <div>
              <p class="text-[10px] md:text-xs text-g-lime font-semibold mb-0.5">AI Gdronic</p>
              <p class="text-[10px] md:text-[11px] text-g-text/65 leading-relaxed">pH dalam kondisi optimal. Nutrisi perlu ditambahkan dalam 2 jam.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scroll hint -->
  <div class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-g-muted animate-bounce">
    <span class="font-mono uppercase tracking-[0.2em] text-[10px]">Scroll</span>
    <i class="fa-solid fa-chevron-down text-sm"></i>
  </div>
</section>

<!-- ═══════════════════════ VISI MISI / KEUNGGULAN ═══════════════════════ -->
<section id="vision" class="section-py relative overflow-hidden">
  <div class="absolute top-0 right-0 w-[700px] h-[500px] bg-g-teal/5 blur-[140px] rounded-full pointer-events-none"></div>
  <div class="container-wide">

    <div class="reveal text-center mb-16 md:mb-20">
      <p class="font-mono text-g-lime text-xs uppercase tracking-[0.35em] mb-5">Mengapa Gdronic?</p>
      <h2 class="text-3xl md:text-5xl xl:text-6xl font-bold text-white">Sistem Hidroponik yang<br/><span class="text-g-lime">Berpikir untuk Kamu</span></h2>
    </div>

    <div class="grid md:grid-cols-3 gap-6 xl:gap-8">
      <div class="reveal grad-border rounded-3xl p-8 xl:p-10 feat-card">
        <div class="icon-box icon-box-lg bg-g-lime/12 mb-7">
          <i class="fa-solid fa-eye text-g-lime text-lg"></i>
        </div>
        <p class="text-g-lime font-mono text-xs uppercase tracking-widest mb-3">Visi</p>
        <h3 class="text-xl font-bold text-white mb-4">Pertanian Urban Masa Depan</h3>
        <p class="text-g-text/55 leading-relaxed text-sm">Mewujudkan ekosistem pertanian perkotaan yang cerdas dan berkelanjutan, di mana siapapun dapat mengelola tanaman hidroponik dengan mudah lewat teknologi.</p>
      </div>

      <div class="reveal grad-border rounded-3xl p-8 xl:p-10 feat-card" style="transition-delay:.1s">
        <div class="icon-box icon-box-lg bg-g-teal/12 mb-7">
          <i class="fa-solid fa-bolt-lightning text-g-teal text-lg"></i>
        </div>
        <p class="text-g-teal font-mono text-xs uppercase tracking-widest mb-3">Misi</p>
        <h3 class="text-xl font-bold text-white mb-4">Automasi yang Dapat Diandalkan</h3>
        <p class="text-g-text/55 leading-relaxed text-sm">Menghadirkan solusi monitoring &amp; kontrol berbasis IoT yang akurat, hemat energi, dan terintegrasi AI agar petani urban fokus pada hasil, bukan kerumitan teknis.</p>
      </div>

      <div class="reveal grad-border rounded-3xl p-8 xl:p-10 feat-card" style="transition-delay:.2s">
        <div class="icon-box icon-box-lg bg-g-lime/12 mb-7">
          <i class="fa-solid fa-star text-g-lime text-lg"></i>
        </div>
        <p class="text-g-lime font-mono text-xs uppercase tracking-widest mb-3">Nilai</p>
        <h3 class="text-xl font-bold text-white mb-4">Inovasi Open &amp; Accessible</h3>
        <p class="text-g-text/55 leading-relaxed text-sm">Dibangun oleh pelajar, untuk semua orang. Gdronic percaya teknologi terbaik adalah yang dapat diakses dan dimanfaatkan oleh siapa saja, di mana saja.</p>
      </div>
    </div>

    <!-- Keunggulan bar -->
    <div class="reveal mt-12 md:mt-16 grad-border rounded-3xl p-6 md:p-12 xl:p-16">
      <p class="font-mono text-g-lime text-xs uppercase tracking-[0.35em] mb-8 md:mb-10 text-center">Keunggulan vs Konvensional</p>
      <div class="grid md:grid-cols-2 gap-8 md:gap-10 xl:gap-16">
        <div id="compare-bars" class="space-y-6"></div>
        <div class="flex flex-col justify-center pl-0 md:pl-10 border-t md:border-t-0 md:border-l border-g-lime/10 pt-8 md:pt-0">
          <p class="text-3xl md:text-4xl xl:text-5xl font-bold text-white font-mono mb-2">60<span class="text-g-lime">%</span></p>
          <p class="text-g-text/55 text-sm mb-6 md:mb-8">lebih sedikit air dibanding pertanian tanah biasa</p>
          <p class="text-3xl md:text-4xl xl:text-5xl font-bold text-white font-mono mb-2">3<span class="text-g-lime">x</span></p>
          <p class="text-g-text/55 text-sm mb-6 md:mb-8">kecepatan pertumbuhan tanaman lebih tinggi</p>
          <p class="text-3xl md:text-4xl xl:text-5xl font-bold text-white font-mono mb-2">0<span class="text-g-lime"> pestisida</span></p>
          <p class="text-g-text/55 text-sm">sistem tertutup bebas hama tanah</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ FITUR INTERAKTIF ═══════════════════════ -->
<section id="features" class="section-py bg-g-dark relative overflow-hidden">
  <div class="absolute inset-0 opacity-[0.025]"
    style="background-image: radial-gradient(#16C47F 1px, transparent 1px); background-size: 36px 36px;"></div>
  <div class="container-wide relative z-10">

    <div class="reveal text-center mb-12 md:mb-16">
      <p class="font-mono text-g-lime text-xs uppercase tracking-[0.35em] mb-5">Fitur Unggulan</p>
      <h2 class="text-3xl md:text-5xl xl:text-6xl font-bold text-white">Semua yang Kamu Butuhkan,<br/><span class="text-g-lime">Dalam Satu Platform</span></h2>
    </div>

    <!-- Feature tabs -->
    <div class="flex flex-wrap gap-2 md:gap-3 justify-center mb-10 md:mb-14" id="feat-tabs">
      <button data-tab="0" class="feat-tab active px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime bg-g-lime text-g-black transition-all">
        <i class="fa-solid fa-satellite-dish mr-1 md:mr-2"></i>Sensor
      </button>
      <button data-tab="1" class="feat-tab px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted hover:border-g-lime/50 hover:text-g-text transition-all">
        <i class="fa-solid fa-robot mr-1 md:mr-2"></i>AI Chatbot
      </button>
      <button data-tab="2" class="feat-tab px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted hover:border-g-lime/50 hover:text-g-text transition-all">
        <i class="fa-solid fa-droplet mr-1 md:mr-2"></i>Kontrol
      </button>
      <button data-tab="3" class="feat-tab px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted hover:border-g-lime/50 hover:text-g-text transition-all">
        <i class="fa-solid fa-chart-line mr-1 md:mr-2"></i>Analitik
      </button>
      <button data-tab="4" class="feat-tab px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted hover:border-g-lime/50 hover:text-g-text transition-all">
        <i class="fa-solid fa-bell mr-1 md:mr-2"></i>Notifikasi
      </button>
    </div>

    <!-- Feature panels -->
    <div id="feat-panels">
      <!-- Panel 0 -->
      <div data-panel="0" class="feat-panel showing grid md:grid-cols-2 gap-8 md:gap-12 xl:gap-16 items-center">
        <div>
          <h3 class="text-2xl md:text-3xl xl:text-4xl font-bold text-white mb-4 md:mb-5">Sensor Akurat, Data Instan</h3>
          <p class="text-g-text/60 leading-relaxed mb-6 md:mb-8 text-sm md:text-base">Pantau pH, suhu air, konduktivitas listrik (EC), kelembapan udara, dan intensitas cahaya secara real-time. Data diperbarui setiap 5 detik langsung dari perangkat IoT ESP32-S3.</p>
          <ul class="stagger-list space-y-3 md:space-y-4 text-sm text-g-text/65">
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Sensor pH ±0.02 presisi tinggi</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Sensor DS18B20 waterproof untuk suhu</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> EC meter untuk konsentrasi nutrisi</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Update data setiap 5 detik via WiFi</li>
          </ul>
        </div>
        <div class="grad-border rounded-2xl p-5 md:p-7 card-fade">
          <div class="space-y-4 md:space-y-5" id="live-sensors">
            <div class="flex items-center justify-between py-2.5 md:py-3.5 border-b border-g-lime/10">
              <span class="text-g-muted text-xs md:text-sm font-mono">pH_LEVEL</span>
              <div class="flex items-center gap-3 md:gap-4">
                <div class="w-20 md:w-28 h-2 bg-g-dark rounded-full overflow-hidden"><div class="h-full bg-g-lime rounded-full" style="width:62%"></div></div>
                <span class="text-g-lime font-mono font-bold text-xs md:text-sm w-10 md:w-12 text-right">6.2</span>
              </div>
            </div>
            <div class="flex items-center justify-between py-2.5 md:py-3.5 border-b border-g-lime/10">
              <span class="text-g-muted text-xs md:text-sm font-mono">TEMP_C</span>
              <div class="flex items-center gap-3 md:gap-4">
                <div class="w-20 md:w-28 h-2 bg-g-dark rounded-full overflow-hidden"><div class="h-full bg-g-teal rounded-full" style="width:48%"></div></div>
                <span class="text-g-lime font-mono font-bold text-xs md:text-sm w-10 md:w-12 text-right">24.3°</span>
              </div>
            </div>
            <div class="flex items-center justify-between py-2.5 md:py-3.5 border-b border-g-lime/10">
              <span class="text-g-muted text-xs md:text-sm font-mono">EC_MS</span>
              <div class="flex items-center gap-3 md:gap-4">
                <div class="w-20 md:w-28 h-2 bg-g-dark rounded-full overflow-hidden"><div class="h-full bg-g-lime/70 rounded-full" style="width:36%"></div></div>
                <span class="text-g-lime font-mono font-bold text-xs md:text-sm w-10 md:w-12 text-right">1.8</span>
              </div>
            </div>
            <div class="flex items-center justify-between py-2.5 md:py-3.5">
              <span class="text-g-muted text-xs md:text-sm font-mono">LUX_LIGHT</span>
              <div class="flex items-center gap-3 md:gap-4">
                <div class="w-20 md:w-28 h-2 bg-g-dark rounded-full overflow-hidden"><div class="h-full bg-g-teal/70 rounded-full" style="width:85%"></div></div>
                <span class="text-g-lime font-mono font-bold text-xs md:text-sm w-10 md:w-12 text-right">850</span>
              </div>
            </div>
          </div>
          <p class="text-[10px] text-g-muted font-mono mt-4 md:mt-5 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-g-lime blink"></span> Memperbarui setiap 5 detik…
          </p>
        </div>
      </div>

      <!-- Panel 1 -->
      <div data-panel="1" class="feat-panel hidden grid md:grid-cols-2 gap-8 md:gap-12 xl:gap-16 items-center">
        <div>
          <h3 class="text-2xl md:text-3xl xl:text-4xl font-bold text-white mb-4 md:mb-5">AI Chatbot Tanaman</h3>
          <p class="text-g-text/60 leading-relaxed mb-6 md:mb-8 text-sm md:text-base">Tanya apa saja soal kondisi tanamanmu. Chatbot AI Gdronic terintegrasi dengan API Gemini, Claude, dan ChatGPT untuk memberikan saran berbasis data sensor real-time dan pengetahuan agrikultur terkini.</p>
          <ul class="stagger-list space-y-3 md:space-y-4 text-sm text-g-text/65">
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Analisis kondisi tanaman otomatis</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Rekomendasi nutrisi &amp; penyesuaian pH</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Prediksi masalah sebelum terjadi</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Powered by Gemini, Claude, ChatGPT</li>
          </ul>
        </div>
        <div class="grad-border rounded-2xl p-5 md:p-6 h-72 md:h-80 flex flex-col card-fade">
          <div class="flex items-center gap-2.5 mb-4 md:mb-5">
            <div class="w-8 h-8 rounded-full bg-g-lime/20 flex items-center justify-center">
              <i class="fa-solid fa-robot text-g-lime text-xs"></i>
            </div>
            <span class="text-sm font-semibold text-white">Gdronic AI</span>
            <span class="ml-auto text-xs text-g-lime font-mono flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full bg-g-lime blink"></span> Online
            </span>
          </div>
          <div class="flex-1 overflow-y-auto space-y-3 text-sm pr-1">
            <div class="flex gap-2">
              <div class="bg-g-mid rounded-2xl rounded-tl-sm p-3 max-w-[80%] text-g-text/80 text-xs leading-relaxed">
                Halo! pH tanaman kamu saat ini <span class="text-g-lime font-bold">6.2</span>, kondisi optimal! Ada yang ingin ditanyakan?
              </div>
            </div>
            <div class="flex gap-2 justify-end">
              <div class="bg-g-lime/20 border border-g-lime/30 rounded-2xl rounded-tr-sm p-3 max-w-[80%] text-g-text text-xs">
                Apakah nutrisi saya perlu ditambah?
              </div>
            </div>
            <div class="flex gap-2">
              <div class="bg-g-mid rounded-2xl rounded-tl-sm p-3 max-w-[80%] text-g-text/80 text-xs leading-relaxed">
                EC kamu <span class="text-g-lime font-bold">1.8 mS/cm</span>, sedikit di bawah ideal (2.0–2.5). Tambahkan nutrisi AB mix ±5ml dalam 30 menit.
              </div>
            </div>
          </div>
          <div class="mt-4 flex gap-2">
            <input type="text" placeholder="Tanya sesuatu…" class="flex-1 bg-g-dark border border-g-lime/20 rounded-full px-4 py-2 text-xs text-g-text focus:outline-none focus:border-g-lime" />
            <button class="w-9 h-9 rounded-full bg-g-lime flex items-center justify-center hover:bg-g-lime2 transition-colors">
              <i class="fa-solid fa-paper-plane text-g-black text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Panel 2 -->
      <div data-panel="2" class="feat-panel hidden grid md:grid-cols-2 gap-8 md:gap-12 xl:gap-16 items-center">
        <div>
          <h3 class="text-2xl md:text-3xl xl:text-4xl font-bold text-white mb-4 md:mb-5">Kontrol Pompa Jarak Jauh</h3>
          <p class="text-g-text/60 leading-relaxed mb-6 md:mb-8 text-sm md:text-base">Kendalikan pompa air, lampu grow light, dan kipas sirkulasi langsung dari dashboard web atau smartphone. Atur jadwal otomatis atau aktifkan manual kapan saja menggunakan relay yang terhubung ke ESP32-S3.</p>
          <ul class="stagger-list space-y-3 md:space-y-4 text-sm text-g-text/65">
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Toggle on/off via relay IoT</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Jadwal otomatis berbasis waktu</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Trigger otomatis berbasis sensor (pH/EC)</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Log riwayat aktivasi perangkat</li>
          </ul>
        </div>
        <div class="grad-border rounded-2xl p-5 md:p-7 space-y-3 md:space-y-4 card-fade">
          <p class="text-xs text-g-muted font-mono uppercase tracking-widest mb-4">Kontrol Perangkat</p>
          <div id="device-controls" class="space-y-3"></div>
        </div>
      </div>

      <!-- Panel 3 -->
      <div data-panel="3" class="feat-panel hidden grid md:grid-cols-2 gap-8 md:gap-12 xl:gap-16 items-center">
        <div>
          <h3 class="text-2xl md:text-3xl xl:text-4xl font-bold text-white mb-4 md:mb-5">Validasi pH Berbasis AI</h3>
          <p class="text-g-text/60 leading-relaxed mb-6 md:mb-8 text-sm md:text-base">Algoritma sederhana namun efektif memvalidasi kadar pH secara kontinu dan memberikan status kesehatan tanaman berdasarkan range optimal setiap jenis tanaman hidroponik.</p>
          <ul class="stagger-list space-y-3 md:space-y-4 text-sm text-g-text/65">
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Range optimal per jenis tanaman</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Alert otomatis jika pH di luar batas</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Grafik historis 7/30/90 hari</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Rekomendasi koreksi pH</li>
          </ul>
        </div>
        <div class="grad-border rounded-2xl p-5 md:p-7 card-fade">
          <p class="text-xs text-g-muted font-mono uppercase tracking-widest mb-5">pH Validator – Coba Sekarang</p>
          <div class="mb-5">
            <label class="text-xs text-g-muted mb-2 block font-mono">Masukkan nilai pH:</label>
            <div class="flex gap-2">
              <input type="number" id="ph-input" step="0.1" min="0" max="14" placeholder="mis. 6.5" class="flex-1 bg-g-dark border border-g-lime/20 rounded-xl px-4 py-3 text-g-text font-mono text-sm focus:outline-none focus:border-g-lime" />
              <button onclick="validatePH()" class="bg-g-lime text-g-black font-bold px-4 md:px-5 py-3 rounded-xl hover:bg-g-lime2 transition-colors text-sm">Cek</button>
            </div>
          </div>
          <div id="ph-result" class="hidden bg-g-black/50 rounded-xl p-4 border border-g-lime/20 mb-5">
            <p id="ph-status" class="font-bold text-base md:text-lg mb-1.5"></p>
            <p id="ph-msg" class="text-xs text-g-text/60 leading-relaxed"></p>
          </div>
          <div class="space-y-2">
            <div class="flex justify-between text-[11px] text-g-muted font-mono">
              <span>0</span><span class="text-red-400">Asam</span><span class="text-g-lime">Optimal</span><span class="text-yellow-400">Basa</span><span>14</span>
            </div>
            <div class="h-3 rounded-full overflow-hidden flex">
              <div class="w-[40%] bg-gradient-to-r from-red-600 to-red-400"></div>
              <div class="w-[21%] bg-gradient-to-r from-g-teal to-g-lime"></div>
              <div class="flex-1 bg-gradient-to-r from-yellow-400/60 to-blue-500/60"></div>
            </div>
            <div class="flex justify-between text-[10px] text-g-muted font-mono">
              <span>0</span><span>5.5</span><span>7.0</span><span>14</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 4 -->
      <div data-panel="4" class="feat-panel hidden grid md:grid-cols-2 gap-8 md:gap-12 xl:gap-16 items-center">
        <div>
          <h3 class="text-2xl md:text-3xl xl:text-4xl font-bold text-white mb-4 md:mb-5">Notifikasi Cerdas</h3>
          <p class="text-g-text/60 leading-relaxed mb-6 md:mb-8 text-sm md:text-base">Dapatkan notifikasi push, email, atau WhatsApp saat kondisi tanaman membutuhkan perhatian. Tidak perlu terus memantau—Gdronic yang jaga tanamanmu.</p>
          <ul class="stagger-list space-y-3 md:space-y-4 text-sm text-g-text/65">
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Alert pH keluar range aman</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Notifikasi suhu ekstrem</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Reminder penggantian nutrisi</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-g-lime"></i> Laporan harian kondisi tanaman</li>
          </ul>
        </div>
        <div class="space-y-3 md:space-y-4">
          <div class="grad-border rounded-2xl p-4 md:p-5 flex items-start gap-3 md:gap-4 card-fade">
            <div class="icon-box bg-red-500/12 shrink-0">
              <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
            </div>
            <div><p class="text-sm font-semibold text-white mb-1">pH Alert!</p><p class="text-xs text-g-muted leading-relaxed">pH turun ke 4.8 – di bawah batas aman. Segera cek nutrisi.</p><p class="text-[10px] text-g-muted mt-2 font-mono">2 menit lalu</p></div>
          </div>
          <div class="grad-border rounded-2xl p-4 md:p-5 flex items-start gap-3 md:gap-4 card-fade" style="transition-delay: 0.1s;">
            <div class="icon-box bg-g-lime/12 shrink-0">
              <i class="fa-solid fa-circle-check text-g-lime"></i>
            </div>
            <div><p class="text-sm font-semibold text-white mb-1">Pompa Aktif</p><p class="text-xs text-g-muted leading-relaxed">Jadwal pagi 07:00 – pompa berhasil diaktifkan otomatis.</p><p class="text-[10px] text-g-muted mt-2 font-mono">1 jam lalu</p></div>
          </div>
          <div class="grad-border rounded-2xl p-4 md:p-5 flex items-start gap-3 md:gap-4 card-fade" style="transition-delay: 0.2s;">
            <div class="icon-box bg-g-teal/12 shrink-0">
              <i class="fa-solid fa-chart-bar text-g-teal"></i>
            </div>
            <div><p class="text-sm font-semibold text-white mb-1">Laporan Harian</p><p class="text-xs text-g-muted leading-relaxed">Kondisi tanaman: BAIK. pH rata-rata 6.3, suhu stabil 24°C.</p><p class="text-[10px] text-g-muted mt-2 font-mono">Hari ini, 06:00</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ CARA KERJA ═══════════════════════ -->
<section id="how" class="section-py relative overflow-hidden">
  <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-g-lime/4 blur-[140px] rounded-full pointer-events-none"></div>
  <div class="container-wide">

    <div class="reveal text-center mb-16 md:mb-20">
      <p class="font-mono text-g-lime text-xs uppercase tracking-[0.35em] mb-5">Cara Kerja</p>
      <h2 class="text-3xl md:text-5xl xl:text-6xl font-bold text-white">Dari Sensor ke Layar,<br/><span class="text-g-lime">Dalam 4 Langkah</span></h2>
    </div>

    <div class="reveal">
      <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-10 md:mb-12" id="step-tabs">
        <button data-step="0" class="step-btn active px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium bg-g-lime text-g-black border border-g-lime transition-all">1. Sensor</button>
        <button data-step="1" class="step-btn px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted transition-all hover:border-g-lime/50">2. Transmisi</button>
        <button data-step="2" class="step-btn px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted transition-all hover:border-g-lime/50">3. Analisis AI</button>
        <button data-step="3" class="step-btn px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-medium border border-g-lime/20 text-g-muted transition-all hover:border-g-lime/50">4. Aksi</button>
      </div>

      <div id="step-content" class="grad-border rounded-3xl p-6 md:p-12 xl:p-16 min-h-64 transition-all duration-500 active"></div>
    </div>

    <!-- Flow diagram -->
    <div class="reveal mt-12 md:mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
      <div class="text-center">
        <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-g-lime/12 border border-g-lime/25 flex items-center justify-center mx-auto mb-3 md:mb-4">
          <i class="fa-solid fa-microchip text-g-lime text-xl md:text-2xl"></i>
        </div>
        <p class="text-[10px] md:text-xs text-g-muted font-mono uppercase tracking-wider mb-1">Hardware</p>
        <p class="text-xs md:text-sm text-g-text">ESP32-S3 + Sensor</p>
      </div>
      <div class="text-center">
        <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-g-teal/12 border border-g-teal/25 flex items-center justify-center mx-auto mb-3 md:mb-4">
          <i class="fa-solid fa-wifi text-g-teal text-xl md:text-2xl"></i>
        </div>
        <p class="text-[10px] md:text-xs text-g-muted font-mono uppercase tracking-wider mb-1">Koneksi</p>
        <p class="text-xs md:text-sm text-g-text">WiFi / HTTP API</p>
      </div>
      <div class="text-center">
        <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-g-lime/12 border border-g-lime/25 flex items-center justify-center mx-auto mb-3 md:mb-4">
          <i class="fa-solid fa-server text-g-lime text-xl md:text-2xl"></i>
        </div>
        <p class="text-[10px] md:text-xs text-g-muted font-mono uppercase tracking-wider mb-1">Backend</p>
        <p class="text-xs md:text-sm text-g-text">Laravel API</p>
      </div>
      <div class="text-center">
        <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-g-teal/12 border border-g-teal/25 flex items-center justify-center mx-auto mb-3 md:mb-4">
          <i class="fa-solid fa-desktop text-g-teal text-xl md:text-2xl"></i>
        </div>
        <p class="text-[10px] md:text-xs text-g-muted font-mono uppercase tracking-wider mb-1">Dashboard</p>
        <p class="text-xs md:text-sm text-g-text">Web Application</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ TENTANG PEMBUAT ═══════════════════════ -->
<section id="about" class="section-py bg-g-dark relative overflow-hidden">
  <div class="absolute inset-0 opacity-[0.025]"
    style="background-image: linear-gradient(#16C47F 1px, transparent 1px), linear-gradient(90deg, #16C47F 1px, transparent 1px); background-size: 80px 80px;"></div>
  <div class="container-wide relative z-10">

    <div class="reveal text-center mb-12 md:mb-16">
      <p class="font-mono text-g-lime text-xs uppercase tracking-[0.35em] mb-5">Pembuat</p>
      <h2 class="text-3xl md:text-5xl xl:text-6xl font-bold text-white">Di Balik Gdronic</h2>
    </div>

    <div class="reveal max-w-3xl mx-auto">
      <div class="grad-border rounded-3xl p-6 md:p-12 xl:p-16 flex flex-col md:flex-row gap-8 md:gap-10 xl:gap-14 items-center">
        <div class="relative shrink-0">
          <div class="w-32 h-32 md:w-36 md:h-36 overflow-hidden xl:w-40 xl:h-40 rounded-3xl bg-gradient-to-br from-g-lime/30 to-g-teal/20 border-2 border-g-lime/40 flex items-center justify-center text-5xl font-bold text-g-lime">
            <img src="{{ asset('img/justin.png') }}" alt="">
          </div>
          <div class="absolute -bottom-2 -right-2 bg-g-lime text-g-black text-[10px] font-bold px-2.5 py-1 rounded-full font-mono">IoT Dev</div>
        </div>
        <div class="flex-1 text-center md:text-left">
          <h3 class="text-2xl md:text-3xl font-bold text-white mb-1">Justine</h3>
          <p class="text-g-lime font-mono text-sm mb-1.5">Software &amp; IoT Developer</p>
          <p class="text-g-muted text-sm mb-5 md:mb-6">SMKN 4 Bogor · Kelas 11 · 17 Tahun</p>
          <p class="text-g-text/55 leading-relaxed text-sm mb-5 md:mb-7">
            Seorang pelajar SMK yang passionate di bidang teknologi, khususnya software development dan IoT. Gdronic lahir dari keingintahuan: <em class="text-g-text">"Bagaimana kalau hidroponik bisa dipantau dari mana saja, otomatis, dan lebih cerdas?"</em>
          </p>
          <div class="flex flex-wrap gap-2 justify-center md:justify-start">
            <span class="bg-g-lime/10 border border-g-lime/20 text-g-lime text-xs px-3 py-1 rounded-full font-mono">Laravel</span>
            <span class="bg-g-lime/10 border border-g-lime/20 text-g-lime text-xs px-3 py-1 rounded-full font-mono">ESP32-S3</span>
            <span class="bg-g-lime/10 border border-g-lime/20 text-g-lime text-xs px-3 py-1 rounded-full font-mono">C/C++</span>
            <span class="bg-g-lime/10 border border-g-lime/20 text-g-lime text-xs px-3 py-1 rounded-full font-mono">IoT</span>
            <span class="bg-g-lime/10 border border-g-lime/20 text-g-lime text-xs px-3 py-1 rounded-full font-mono">API Integration</span>
            <span class="bg-g-lime/10 border border-g-lime/20 text-g-lime text-xs px-3 py-1 rounded-full font-mono">Electrical</span>
          </div>
        </div>
      </div>

      <div class="mt-8 md:mt-10 text-center px-4">
        <p class="text-lg md:text-2xl text-g-text/65 italic leading-relaxed">
          "Teknologi terbaik adalah yang membuat hidup lebih mudah bagi semua orang, bukan hanya para ahli."
        </p>
        <p class="text-g-lime font-mono text-sm mt-4">— Justine, Gdronic Creator</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ KONTAK ═══════════════════════ -->
<section id="contact" class="section-py relative overflow-hidden">
  <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-g-teal/6 blur-[120px] rounded-full pointer-events-none"></div>
  <div class="container-wide">

    <div class="reveal text-center mb-12 md:mb-16">
      <p class="font-mono text-g-lime text-xs uppercase tracking-[0.35em] mb-5">Hubungi Kami</p>
      <h2 class="text-3xl md:text-5xl xl:text-6xl font-bold text-white">Punya Pertanyaan?<br/><span class="text-g-lime">Kami Siap Membantu</span></h2>
    </div>

    <div class="reveal grid md:grid-cols-2 gap-10 md:gap-12 xl:gap-20 max-w-5xl mx-auto">
      <div class="grad-border rounded-3xl p-6 md:p-8 xl:p-10">
        <div class="space-y-4 md:space-y-5">
          <div>
            <label class="text-xs text-g-muted font-mono uppercase block mb-2.5 tracking-wider">Nama</label>
            <input type="text" id="wa-name" placeholder="Nama kamu" class="w-full bg-g-dark border border-g-lime/20 rounded-xl px-4 py-3.5 text-g-text text-sm focus:outline-none focus:border-g-lime transition-colors" />
          </div>
          <div>
            <label class="text-xs text-g-muted font-mono uppercase block mb-2.5 tracking-wider">Email</label>
            <input type="email" id="wa-email" placeholder="email@kamu.com" class="w-full bg-g-dark border border-g-lime/20 rounded-xl px-4 py-3.5 text-g-text text-sm focus:outline-none focus:border-g-lime transition-colors" />
          </div>
          <div>
            <label class="text-xs text-g-muted font-mono uppercase block mb-2.5 tracking-wider">Pesan</label>
            <textarea rows="4" id="wa-message" placeholder="Ceritakan kebutuhanmu…" class="w-full bg-g-dark border border-g-lime/20 rounded-xl px-4 py-3.5 text-g-text text-sm focus:outline-none focus:border-g-lime transition-colors resize-none"></textarea>
          </div>
          <button onclick="sendWA()" class="w-full bg-g-lime text-g-black font-bold py-4 rounded-xl hover:bg-g-lime2 transition-colors flex items-center justify-center gap-2">
            Kirim Pesan <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <div class="flex flex-col justify-center gap-5 md:gap-7">
        <div class="flex items-start gap-4 md:gap-5">
          <div class="icon-box bg-g-lime/12">
            <i class="fa-solid fa-location-dot text-g-lime"></i>
          </div>
          <div>
            <p class="text-sm font-semibold text-white mb-1">Lokasi</p>
            <p class="text-g-muted text-sm">JL Raya Cipaku RT 005/003</p>
          </div>
        </div>
        <div class="flex items-start gap-4 md:gap-5">
          <div class="icon-box bg-g-lime/12">
            <i class="fa-solid fa-envelope text-g-lime"></i>
          </div>
          <div>
            <p class="text-sm font-semibold text-white mb-1">Email</p>
            <p class="text-g-muted text-sm">justinebogor0609@gmail.com</p>
          </div>
        </div>
        <div class="flex items-start gap-4 md:gap-5">
          <div class="icon-box bg-g-lime/12">
            <i class="fa-brands fa-instagram text-g-lime"></i>
          </div>
          <div>
            <p class="text-sm font-semibold text-white mb-1">Instagram</p>
            <p class="text-g-muted text-sm">@gdronic.id</p>
          </div>
        </div>

        <div class="mt-2 md:mt-4 bg-g-lime/8 border border-g-lime/20 rounded-2xl p-6 md:p-7 text-center">
          <p class="text-white font-semibold mb-2">Siap mencoba Gdronic?</p>
          <p class="text-g-text/55 text-sm mb-5">Daftar gratis dan mulai monitoring tanamanmu hari ini.</p>
          <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="inline-flex items-center gap-2 bg-g-lime text-g-black font-bold px-7 py-3 rounded-full hover:bg-g-lime2 transition-colors text-sm">
            Mulai Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ FOOTER ═══════════════════════ -->
<footer class="bg-g-black border-t border-g-lime/10 py-12 md:py-16">
  <div class="container-wide">
    <div class="grid md:grid-cols-4 gap-8 md:gap-12 mb-10 md:mb-14">
      <div class="md:col-span-2">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-9 h-9 rounded-lg bg-g-lime/20 border border-g-lime/40 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><path d="M12 2C6 2 2 8 2 14c0 4 2.5 7 6 8l1-3c-2-.8-3-2.8-3-5 0-4 2.7-8 6-8s6 4 6 8c0 2.2-1 4.2-3 5l1 3c3.5-1 6-4 6-8 0-6-4-12-10-12z" fill="#16C47F"/><circle cx="12" cy="14" r="2.5" fill="#16C47F"/></svg>
          </div>
          <span class="text-xl font-bold text-white">Gdronic<span class="text-g-lime">.</span></span>
        </div>
        <p class="text-g-muted text-sm leading-relaxed mb-7 max-w-xs">Smart Hydroponic System — Membangun masa depan pertanian yang lebih cerdas dengan IoT dan AI.</p>
        <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="inline-flex items-center gap-2 bg-g-lime text-g-black font-bold px-5 py-2.5 rounded-full hover:bg-g-lime2 transition-colors text-sm">
          Mulai Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>
      <div>
        <p class="text-g-lime font-mono text-xs uppercase tracking-widest mb-5">Navigasi</p>
        <ul class="space-y-3.5 text-sm text-g-muted">
          <li><a href="#features" class="hover:text-g-lime transition-colors">Fitur</a></li>
          <li><a href="#how" class="hover:text-g-lime transition-colors">Cara Kerja</a></li>
          <li><a href="#about" class="hover:text-g-lime transition-colors">Tentang</a></li>
          <li><a href="#contact" class="hover:text-g-lime transition-colors">Kontak</a></li>
          <li><a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="hover:text-g-lime transition-colors">Login</a></li>
        </ul>
      </div>
      <div>
        <p class="text-g-lime font-mono text-xs uppercase tracking-widest mb-5">Teknologi</p>
        <ul class="space-y-3.5 text-sm text-g-muted">
          <li>ESP32-S3 DevKitC</li>
          <li>Laravel Framework</li>
          <li>C/C++ IoT Programming</li>
          <li>REST API</li>
          <li>AI API Integration</li>
        </ul>
      </div>
    </div>

    <div class="border-t border-g-lime/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
      <p class="text-g-muted text-xs font-mono">© 2026 Gdronic. Built by Justine · Zerrace</p>
      <p class="text-g-muted text-xs font-mono">Smart Hydroponic System · IoT + AI</p>
    </div>
  </div>
</footer>

<!-- Back to top button -->
<button id="backToTop"
  class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-full bg-g-lime text-g-black shadow-[0_0_24px_rgba(22,196,127,0.45)] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:scale-110"
  onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- ═══════════════════════ JAVASCRIPT ═══════════════════════ -->
<script>
// ── Mobile menu ──
document.getElementById('menuBtn').addEventListener('click', () => {
  document.getElementById('mobileMenu').classList.toggle('hidden');
});

// ── Reveal on scroll ──
const reveals = document.querySelectorAll('.reveal');
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }});
}, {threshold:.1});
reveals.forEach(r => io.observe(r));

// ── Navbar transparent di hero, bg setelah lewat ──
const navInner = document.getElementById('nav-inner');
const heroEl = document.getElementById('hero');
function updateNavBg() {
  const heroBottom = heroEl.offsetTop + heroEl.offsetHeight;
  const isPastHero = window.scrollY > heroBottom - 60;
  navInner.classList.toggle('navbar-blur', isPastHero);
  navInner.classList.toggle('bg-g-black/75', isPastHero);
  navInner.classList.toggle('border-g-lime/10', isPastHero);
  navInner.classList.toggle('border-transparent', !isPastHero);
}
updateNavBg();
window.addEventListener('scroll', updateNavBg);

// ── Back to top ──
const btn = document.getElementById('backToTop');
window.addEventListener('scroll', () => {
  if(window.scrollY > 400) { btn.style.opacity='1'; btn.style.pointerEvents='auto'; }
  else { btn.style.opacity='0'; btn.style.pointerEvents='none'; }
});

// ══════════════════════════════════════════════════════════════════
// ── SMOOTH FEATURE TAB TRANSITIONS ──
// ══════════════════════════════════════════════════════════════════
let currentPanel = 0;
const featureTabs = document.getElementById('feat-tabs');
const allPanels = document.querySelectorAll('.feat-panel');

function switchFeaturePanel(newIndex) {
  if (newIndex === currentPanel) return;
  
  const oldPanel = allPanels[currentPanel];
  const newPanel = allPanels[newIndex];
  
  // Hide old panel with animation
  oldPanel.classList.add('hiding');
  oldPanel.classList.remove('showing');
  
  setTimeout(() => {
    oldPanel.classList.add('hidden');
    oldPanel.classList.remove('hiding');
    
    // Show new panel with animation
    newPanel.classList.remove('hidden');
    
    // Trigger reflow
    void newPanel.offsetWidth;
    
    newPanel.classList.add('showing');
    newPanel.classList.remove('hiding');
    
    // Animate list items if present
    const staggerList = newPanel.querySelector('.stagger-list');
    if (staggerList) {
      setTimeout(() => {
        staggerList.classList.add('animate');
      }, 100);
    }
    
    // Animate cards if present
    const cards = newPanel.querySelectorAll('.card-fade');
    cards.forEach((card, idx) => {
      setTimeout(() => {
        card.classList.add('visible');
      }, 100 + (idx * 100));
    });
    
  }, 500);
  
  currentPanel = newIndex;
}

featureTabs.addEventListener('click', e => {
  const b = e.target.closest('[data-tab]');
  if(!b) return;
  const idx = parseInt(b.dataset.tab);
  
  // Update button states
  document.querySelectorAll('.feat-tab').forEach(x => {
    x.classList.remove('bg-g-lime','text-g-black','border-g-lime','active');
    x.classList.add('border-g-lime/20','text-g-muted');
  });
  b.classList.add('bg-g-lime','text-g-black','border-g-lime','active');
  b.classList.remove('border-g-lime/20','text-g-muted');
  
  // Switch panels
  switchFeaturePanel(idx);
});

// Initialize first panel
setTimeout(() => {
  const firstPanel = allPanels[0];
  const staggerList = firstPanel.querySelector('.stagger-list');
  if (staggerList) staggerList.classList.add('animate');
  
  const cards = firstPanel.querySelectorAll('.card-fade');
  cards.forEach((card, idx) => {
    setTimeout(() => card.classList.add('visible'), idx * 100);
  });
}, 100);

// ══════════════════════════════════════════════════════════════════
// ── SMOOTH STEP CONTENT TRANSITIONS ──
// ══════════════════════════════════════════════════════════════════
const steps = [
  {
    icon: 'fa-thermometer-half',
    title: 'Sensor Membaca Data',
    desc: 'Sensor pH, suhu DS18B20, EC meter, dan sensor cahaya terhubung ke mikrokontroler ESP32-S3 DevKitC Type-C. Data dibaca setiap 5 detik secara kontinu dari media tanam hidroponik menggunakan pemrograman C/C++.',
    detail: 'Hardware: ESP32-S3 DevKitC Type-C · Sensor pH · DS18B20 · EC Meter · BH1750/LDR',
  },
  {
    icon: 'fa-satellite-dish',
    title: 'Data Dikirim via WiFi',
    desc: 'ESP32-S3 mengirimkan data sensor melalui koneksi WiFi ke server backend Laravel menggunakan protokol HTTP/HTTPS. Laravel API menerima dan memproses data secara real-time dengan latency rendah.',
    detail: 'Protokol: HTTP/HTTPS REST API · WiFi IEEE 802.11 · JSON Data Format',
  },
  {
    icon: 'fa-brain',
    title: 'AI Menganalisis & Memvalidasi',
    desc: 'Laravel backend menerima data dan menjalankan algoritma validasi pH, mendeteksi anomali. AI chatbot menggunakan integrasi API eksternal (Gemini, Claude, ChatGPT) untuk memberikan rekomendasi berbasis kondisi terkini tanaman.',
    detail: 'Stack: Laravel Web App · Laravel API · AI Integration (Gemini, Claude, ChatGPT)',
  },
  {
    icon: 'fa-bolt',
    title: 'Aksi & Notifikasi',
    desc: 'Sistem secara otomatis mengaktifkan relay (pompa, lampu) melalui ESP32-S3 sesuai kondisi sensor, dan mengirimkan notifikasi push/WhatsApp ke pengguna. Dashboard web Laravel diperbarui live.',
    detail: 'Output: Relay Module via ESP32-S3 · Push Notification · WhatsApp API · Laravel Dashboard',
  },
];

const stepContent = document.getElementById('step-content');
const stepTabs = document.getElementById('step-tabs');
let currentStep = 0;

function renderStep(index) {
  const s = steps[index];
  return `
    <div class="flex flex-col md:flex-row gap-6 md:gap-8 xl:gap-12 items-center">
      <div class="w-16 h-16 md:w-20 md:h-20 xl:w-24 xl:h-24 rounded-2xl md:rounded-3xl bg-g-lime/12 border border-g-lime/25 flex items-center justify-center shrink-0">
        <i class="fa-solid ${s.icon} text-g-lime text-2xl md:text-3xl xl:text-4xl"></i>
      </div>
      <div>
        <p class="font-mono text-g-lime text-xs uppercase tracking-widest mb-2 md:mb-3">Langkah ${index+1} dari 4</p>
        <h3 class="text-xl md:text-2xl xl:text-3xl font-bold text-white mb-3 md:mb-4">${s.title}</h3>
        <p class="text-g-text/60 leading-relaxed mb-4 md:mb-5 text-sm md:text-base">${s.desc}</p>
        <div class="bg-g-black/50 rounded-xl px-3 md:px-4 py-2 md:py-2.5 font-mono text-[10px] md:text-xs text-g-lime border border-g-lime/20 inline-block">${s.detail}</div>
      </div>
    </div>`;
}

function switchStep(newIndex) {
  if (newIndex === currentStep) return;
  
  // Add transitioning class
  stepContent.classList.remove('active');
  stepContent.classList.add('transitioning');
  
  setTimeout(() => {
    stepContent.innerHTML = renderStep(newIndex);
    stepContent.classList.remove('transitioning');
    
    // Trigger reflow
    void stepContent.offsetWidth;
    
    stepContent.classList.add('active');
  }, 400);
  
  currentStep = newIndex;
}

// Initialize first step
stepContent.innerHTML = renderStep(0);

stepTabs.addEventListener('click', e => {
  const b = e.target.closest('[data-step]');
  if(!b) return;
  const idx = parseInt(b.dataset.step);
  
  // Update button states
  document.querySelectorAll('.step-btn').forEach(x => {
    x.classList.remove('bg-g-lime','text-g-black','border-g-lime','active');
    x.classList.add('border-g-lime/20','text-g-muted');
  });
  b.classList.add('bg-g-lime','text-g-black','border-g-lime','active');
  b.classList.remove('border-g-lime/20','text-g-muted');
  
  switchStep(idx);
});

// ── Device controls ──
const devices = [
  {name:'Pompa Air', icon:'fa-droplet', state:true},
  {name:'Grow Light', icon:'fa-lightbulb', state:false},
  {name:'Kipas Sirkulasi', icon:'fa-fan', state:true},
  {name:'Pompa Nutrisi', icon:'fa-flask', state:false},
];
const dc = document.getElementById('device-controls');
if(dc) {
  devices.forEach((d,i) => {
    dc.innerHTML += `
      <div class="flex items-center justify-between p-3 md:p-3.5 bg-g-black/40 rounded-xl border border-g-lime/10">
        <span class="text-xs md:text-sm text-g-text flex items-center gap-2 md:gap-2.5">
          <i class="fa-solid ${d.icon} text-g-muted w-3 md:w-4"></i> ${d.name}
        </span>
        <button onclick="toggleDevice(this,${i})"
          class="w-12 h-6 rounded-full transition-all duration-300 relative ${d.state?'bg-g-lime':'bg-g-mid border border-g-lime/20'}"
          data-on="${d.state}">
          <span class="absolute top-0.5 ${d.state?'left-6':'left-0.5'} w-5 h-5 rounded-full bg-white transition-all duration-300 shadow"></span>
        </button>
      </div>`;
  });
}
function toggleDevice(btn, i) {
  const on = btn.dataset.on === 'true';
  btn.dataset.on = !on;
  btn.className = `w-12 h-6 rounded-full transition-all duration-300 relative ${!on?'bg-g-lime':'bg-g-mid border border-g-lime/20'}`;
  btn.querySelector('span').className = `absolute top-0.5 ${!on?'left-6':'left-0.5'} w-5 h-5 rounded-full bg-white transition-all duration-300 shadow`;
}

// ── Compare bars ──
const bars = [
  {label:'Efisiensi Air', gdronic:90, konv:35},
  {label:'Kecepatan Tumbuh', gdronic:85, konv:30},
  {label:'Akurasi Nutrisi', gdronic:95, konv:50},
  {label:'Kemudahan Monitor', gdronic:98, konv:20},
];
const cb = document.getElementById('compare-bars');
if(cb) bars.forEach(b => {
  cb.innerHTML += `
    <div>
      <div class="flex justify-between text-xs text-g-muted mb-2 font-mono">
        <span>${b.label}</span><span class="text-g-lime">${b.gdronic}%</span>
      </div>
      <div class="relative h-3 bg-g-dark rounded-full overflow-hidden">
        <div class="absolute left-0 top-0 h-full rounded-full bg-gradient-to-r from-g-teal to-g-lime ph-bar" style="width:0%" data-target="${b.gdronic}%"></div>
        <div class="absolute left-0 top-0 h-full rounded-full bg-g-muted/25" style="width:${b.konv}%"></div>
      </div>
      <div class="flex justify-between text-[10px] mt-1.5 text-g-muted font-mono"><span>Konvensional ${b.konv}%</span><span class="text-g-lime">Gdronic</span></div>
    </div>`;
});
setTimeout(() => {
  document.querySelectorAll('.ph-bar[data-target]').forEach(el => el.style.width = el.dataset.target);
}, 600);

// ── pH Validator ──
function validatePH() {
  const val = parseFloat(document.getElementById('ph-input').value);
  const res = document.getElementById('ph-result');
  const st = document.getElementById('ph-status');
  const msg = document.getElementById('ph-msg');
  if(isNaN(val) || val < 0 || val > 14) {
    res.classList.remove('hidden');
    st.innerHTML = '<i class="fa-solid fa-xmark mr-2"></i>Nilai tidak valid';
    st.className = 'font-bold text-base md:text-lg text-red-400 mb-1';
    msg.textContent = 'Masukkan nilai antara 0–14';
    return;
  }
  res.classList.remove('hidden');
  if(val >= 5.5 && val <= 7.0) {
    st.innerHTML = `<i class="fa-solid fa-circle-check mr-2"></i>pH ${val} — OPTIMAL`;
    st.className = 'font-bold text-base md:text-lg text-g-lime mb-1';
    msg.textContent = 'Kadar pH dalam rentang ideal (5.5–7.0) untuk sebagian besar tanaman hidroponik. Kondisi nutrisi baik.';
  } else if(val < 5.5) {
    st.innerHTML = `<i class="fa-solid fa-triangle-exclamation mr-2"></i>pH ${val} — TERLALU ASAM`;
    st.className = 'font-bold text-base md:text-lg text-red-400 mb-1';
    msg.textContent = 'pH di bawah 5.5 menghambat penyerapan nutrisi. Tambahkan larutan pH Up (kalium hidroksida) secara bertahap.';
  } else {
    st.innerHTML = `<i class="fa-solid fa-triangle-exclamation mr-2"></i>pH ${val} — TERLALU BASA`;
    st.className = 'font-bold text-base md:text-lg text-yellow-400 mb-1';
    msg.textContent = 'pH di atas 7.0 menyebabkan defisiensi mikro-nutrisi. Tambahkan larutan pH Down (asam fosfat) secara bertahap.';
  }
}

// ── Live sensor sim ──
function randomize(base, range) {
  return (base + (Math.random() - 0.5) * range).toFixed(1);
}
setInterval(() => {
  const ph = parseFloat(randomize(6.2, 0.3));
  const temp = parseFloat(randomize(24.3, 1.0));
  const ec = parseFloat(randomize(1.8, 0.2));
  const lux = Math.round(parseFloat(randomize(850, 50)));
  const el = id => document.getElementById(id);
  if(el('ph-val')) el('ph-val').textContent = ph;
  if(el('temp-val')) el('temp-val').innerHTML = `${temp}<span class="text-g-lime text-xs md:text-sm">°C</span>`;
  if(el('ec-val')) el('ec-val').innerHTML = `${ec}<span class="text-g-lime text-xs md:text-sm">mS</span>`;
  if(el('lux-val')) el('lux-val').innerHTML = `${lux}<span class="text-g-lime text-xs md:text-sm">lx</span>`;
}, 5000);

// ── Particles ──
const canvas = document.getElementById('particles');
const ctx = canvas.getContext('2d');
let W, H, particles = [];
function resize() { W = canvas.width = canvas.offsetWidth; H = canvas.height = canvas.offsetHeight; }
window.addEventListener('resize', resize);
resize();
for(let i=0; i<70; i++) particles.push({
  x: Math.random()*1920, y: Math.random()*1080,
  vx: (Math.random()-.5)*.25, vy: (Math.random()-.5)*.25,
  r: Math.random()*1.5+.5, a: Math.random()*.4+.08
});
function drawParticles() {
  ctx.clearRect(0,0,W,H);
  particles.forEach(p => {
    p.x += p.vx; p.y += p.vy;
    if(p.x<0) p.x=W; if(p.x>W) p.x=0;
    if(p.y<0) p.y=H; if(p.y>H) p.y=0;
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
    ctx.fillStyle = `rgba(22,196,127,${p.a})`;
    ctx.fill();
  });
  requestAnimationFrame(drawParticles);
}
drawParticles();

// ── WhatsApp Contact Form ──
function sendWA() {
  const name = document.getElementById('wa-name').value.trim();
  const email = document.getElementById('wa-email').value.trim();
  const message = document.getElementById('wa-message').value.trim();
  if (!name && !email && !message) { alert('Silakan isi form terlebih dahulu.'); return; }
  let text = '';
  if (name)   text += `Halo, saya *${name}*.\n`;
  if (email)  text += `Email: ${email}\n`;
  if (message) text += `\n${message}`;
  const url = `https://wa.me/6287774487198?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');
}

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
  input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
});
</script>

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

</body>
</html>