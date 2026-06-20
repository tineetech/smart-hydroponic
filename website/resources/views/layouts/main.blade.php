<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gdronic – Smart Hydroponic System</title>
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
            'g-lime':     '#a8f04a',
            'g-lime2':    '#c5ff6b',
            'g-teal':     '#2ecc8a',
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
    ::-webkit-scrollbar-thumb { background: #a8f04a; border-radius: 99px; }

    .glow { text-shadow: 0 0 40px rgba(168,240,74,0.5), 0 0 80px rgba(168,240,74,0.2); }

    @keyframes scan {
      0%   { transform: translateY(-100%); opacity: 0; }
      10%  { opacity: 0.05; }
      90%  { opacity: 0.05; }
      100% { transform: translateY(200vh); opacity: 0; }
    }
    .scan-line {
      position: absolute; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent, #a8f04a, transparent);
      animation: scan 7s linear infinite;
      pointer-events: none;
    }

    @keyframes blink { 0%,100%{opacity:1}50%{opacity:0.25} }
    .blink { animation: blink 2.2s ease-in-out infinite; }

    .feat-card { transition: transform .3s, box-shadow .3s; }
    .feat-card:hover { transform: translateY(-6px); box-shadow: 0 20px 60px rgba(168,240,74,0.1); }

    @keyframes orbit {
      from { transform: rotate(0deg) translateX(90px) rotate(0deg); }
      to   { transform: rotate(360deg) translateX(90px) rotate(-360deg); }
    }
    .orbit-dot {
      animation: orbit 10s linear infinite;
      position: absolute;
      width: 8px; height: 8px;
      background: #a8f04a; border-radius: 50%;
      box-shadow: 0 0 12px rgba(168,240,74,0.8);
    }

    .navbar-blur { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }

    @keyframes cursor-blink { 0%,100%{opacity:1}50%{opacity:0} }
    .cursor { animation: cursor-blink 1s step-end infinite; }

    .reveal { opacity: 0; transform: translateY(36px); transition: opacity .75s ease, transform .75s ease; }
    .reveal.visible { opacity: 1; transform: none; }

    .grad-border {
      background: linear-gradient(135deg, #132016, #0d1a10);
      border: 1px solid rgba(168,240,74,0.12);
      transition: border-color .3s;
    }
    .grad-border:hover { border-color: rgba(168,240,74,0.35); }

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
      background: linear-gradient(135deg, rgba(168,240,74,0.1), rgba(46,204,138,0.1));
      opacity: 0;
      transition: opacity 0.35s ease;
      border-radius: inherit;
    }

    .feat-tab:hover::before, .step-btn:hover::before {
      opacity: 1;
    }

    .feat-tab.active, .step-btn.active {
      transform: scale(1.05);
      box-shadow: 0 4px 16px rgba(168,240,74,0.3);
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
  </style>
</head>
<body>

<!-- ═══════════════════════ NAVBAR ═══════════════════════ -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
  <div class="navbar-blur bg-g-black/75 border-b border-g-lime/10">
    <div class="container-wide py-4 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <div class="relative w-9 h-9">
          <div class="absolute inset-0 rounded-lg bg-g-lime/20 border border-g-lime/40"></div>
          <svg class="absolute inset-0 m-auto w-5 h-5" fill="none" viewBox="0 0 24 24">
            <path d="M12 2C6 2 2 8 2 14c0 4 2.5 7 6 8l1-3c-2-.8-3-2.8-3-5 0-4 2.7-8 6-8s6 4 6 8c0 2.2-1 4.2-3 5l1 3c3.5-1 6-4 6-8 0-6-4-12-10-12z" fill="#a8f04a"/>
            <circle cx="12" cy="14" r="2.5" fill="#a8f04a"/>
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
    <div id="mobileMenu" class="hidden md:hidden px-6 pb-6 flex flex-col gap-4 text-sm text-g-text border-t border-g-lime/10 pt-4 mt-1">
      <a href="#features" class="hover:text-g-lime py-1">Fitur</a>
      <a href="#how" class="hover:text-g-lime py-1">Cara Kerja</a>
      <a href="#about" class="hover:text-g-lime py-1">Tentang</a>
      <a href="#contact" class="hover:text-g-lime py-1">Kontak</a>
      <a href="{{ Auth::user() ? route('admin.dashboard.index') : route('login') }}" class="bg-g-lime text-g-black font-semibold px-5 py-2.5 rounded-full w-fit mt-1">Mulai Sekarang <i class="fa-solid fa-arrow-right text-xs ml-1"></i></a>
    </div>
  </div>
</nav>

@yield('content')

<!-- ═══════════════════════ FOOTER ═══════════════════════ -->
<footer class="bg-g-black border-t border-g-lime/10 py-12 md:py-16">
  <div class="container-wide">
    <div class="grid md:grid-cols-4 gap-8 md:gap-12 mb-10 md:mb-14">
      <div class="md:col-span-2">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-9 h-9 rounded-lg bg-g-lime/20 border border-g-lime/40 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><path d="M12 2C6 2 2 8 2 14c0 4 2.5 7 6 8l1-3c-2-.8-3-2.8-3-5 0-4 2.7-8 6-8s6 4 6 8c0 2.2-1 4.2-3 5l1 3c3.5-1 6-4 6-8 0-6-4-12-10-12z" fill="#a8f04a"/><circle cx="12" cy="14" r="2.5" fill="#a8f04a"/></svg>
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
  class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-full bg-g-lime text-g-black shadow-[0_0_24px_rgba(168,240,74,0.45)] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:scale-110"
  onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="fa-solid fa-chevron-up"></i>
</button>

</body>
</html>