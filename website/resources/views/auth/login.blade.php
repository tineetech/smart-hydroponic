<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gdronic</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * { font-family: 'Inter', sans-serif; }
        
        body {
            background-color: #0f1a0f;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .input-dark {
            background: #0f1a0f !important;
            border: 1px solid #2d4a2d !important;
            color: #e8f5e8 !important;
            transition: all 0.2s ease;
        }

        .input-dark:focus {
            border-color: #A8F04A !important;
            box-shadow: 0 0 0 3px rgba(168, 240, 74, 0.12) !important;
            outline: none !important;
        }

        .input-dark::placeholder {
            color: #4a6b4a !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7ab82e, #5f9023);
            border: 1px solid #A8F04A;
            color: #0f1a0f;
            font-weight: 700;
            letter-spacing: 0.025em;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(168, 240, 74, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #8BD435, #6DB82A);
            box-shadow: 0 6px 20px rgba(168, 240, 74, 0.35);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .logo-ring {
            background: linear-gradient(135deg, #7ab82e, #5f9023);
            box-shadow: 0 0 20px rgba(168, 240, 74, 0.35);
        }

        .status-dot {
            background: #A8F04A !important;
            animation: pulse-lime 2s infinite;
        }

        @keyframes pulse-lime {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(168, 240, 74, 0.4); }
            50% { opacity: 0.8; box-shadow: 0 0 0 6px rgba(168, 240, 74, 0); }
        }

        .checkbox-custom {
            accent-color: #A8F04A;
        }

        .divider {
            border-color: #2d4a2d;
        }

        .accent-text {
            color: #A8F04A;
        }

        .online-badge {
            background: rgba(168, 240, 74, 0.15);
            color: #A8F04A;
            border: 1px solid rgba(168, 240, 74, 0.3);
        }

        .card-dark {
            background: #1a2e1a;
            border: 1px solid #2d4a2d;
        }

        /* Image panel */
        .img-panel {
            position: relative;
            overflow: hidden;
        }

        .img-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        /* Overlay gelap di atas gambar supaya ada kontras */
        .img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(10, 20, 10, 0.55) 0%,
                rgba(15, 30, 15, 0.35) 50%,
                rgba(10, 20, 10, 0.60) 100%
            );
            z-index: 1;
        }

        /* Konten di atas overlay */
        .img-content {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
        }

        /* Badge LIVE di pojok kanan atas gambar */
        .live-badge {
            background: rgba(168, 240, 74, 0.15);
            border: 1px solid rgba(168, 240, 74, 0.4);
            backdrop-filter: blur(8px);
            color: #A8F04A;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 6px 14px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Stats card di pojok kiri bawah gambar */
        .img-stats-card {
            background: rgba(15, 26, 15, 0.75);
            border: 1px solid rgba(168, 240, 74, 0.2);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 20px 24px;
            max-width: 380px;
        }

        /* Right panel */
        .right-panel {
            background: #152215;
            border-left: 1px solid #2d4a2d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0f1a0f; }
        ::-webkit-scrollbar-thumb { background: #2d4a2d; border-radius: 2px; }

        /* Toggle password hover */
        .eye-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .eye-btn svg { stroke: #4a6b4a; transition: stroke 0.2s; }
        .eye-btn:hover svg { stroke: #A8F04A; }
    </style>
</head>
<body>

<div class="min-h-screen flex flex-col lg:flex-row">

    <!-- ══════════════════════════════════════════════════
         LEFT PANEL — Full Image
    ══════════════════════════════════════════════════ -->
    <div class="img-panel
                hidden lg:block
                lg:w-1/2 xl:w-3/5
                relative">

        <!-- Gambar Utama — hidroponik / pertanian -->
        <img
            src="{{ asset('img/bg-smarthydroponic1.png') }}"
            alt="Smart Hydroponic Farm"
            loading="eager"
        />

        <!-- Overlay gelap -->
        <div class="img-overlay"></div>

        <!-- Konten di atas gambar -->
        <div class="img-content">

            <!-- TOP: Logo + LIVE badge -->
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="logo-ring w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <div class="relative w-9 h-9">
          <div class="absolute inset-0 rounded-lg bg-g-lime/20 border border-g-lime/40"></div>
          <svg class="absolute inset-0 m-auto w-5 h-5" fill="none" viewBox="0 0 24 24">
            <path d="M12 2C6 2 2 8 2 14c0 4 2.5 7 6 8l1-3c-2-.8-3-2.8-3-5 0-4 2.7-8 6-8s6 4 6 8c0 2.2-1 4.2-3 5l1 3c3.5-1 6-4 6-8 0-6-4-12-10-12z" fill="#a8f04a"/>
            <circle cx="12" cy="14" r="2.5" fill="#a8f04a"/>
          </svg>
        </div>
                    </div>
                    <div>
                        <p class="text-white font-bold text-lg leading-none">Gdronic.</p>
                        <p class="text-xs mt-0.5" style="color: rgba(168,240,74,0.7);">Smart Hydroponic System</p>
                    </div>
                </div>

                <!-- LIVE badge -->
                <div class="live-badge">
                    <div class="status-dot w-2 h-2 rounded-full"></div>
                    LIVE
                </div>
            </div>


        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         RIGHT PANEL — Login Form
    ══════════════════════════════════════════════════ -->
    <div class="right-panel
                w-full
                lg:w-1/2 xl:w-2/5
                min-h-screen lg:min-h-0">

        <div class="w-full max-w-md">

            <!-- ── Mobile: Logo (hanya tampil di mobile) ── -->
            <div class="flex items-center justify-center gap-3 mb-8 lg:hidden">
                <div class="logo-ring w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg leading-none">Gdronic.</p>
                    <p class="text-xs" style="color: #A8F04A;">Smart Hydroponic System</p>
                </div>
            </div>

            <!-- ── Header ── -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-3">
                    <div class="status-dot w-2 h-2 rounded-full flex-shrink-0"></div>
                    <span class="text-xs font-bold tracking-widest uppercase accent-text">
                        Admin Panel
                    </span>
                </div>
                <h2 class="text-3xl font-extrabold mb-1" style="color: #e8f5e8;">
                    Selamat Datang 👋
                </h2>
                <p class="text-sm" style="color: #4a6b4a;">
                    Masuk untuk memantau sistem hidroponik Anda
                </p>
            </div>

            <!-- ── Session Status ── -->
            @if (session('status'))
                <div class="rounded-xl p-4 text-sm font-medium online-badge flex items-center gap-2 mb-6">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- ── Login Form ── -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold" style="color: #a3c4a3;">
                        Email Address
                    </label>
                    <div class="relative">
                        <!-- Icon -->
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4" style="color:#4a6b4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@gdronic.com"
                            class="input-dark w-full rounded-xl pl-10 pr-4 py-3 text-sm"
                        />
                    </div>
                    @error('email')
                        <p class="flex items-center gap-1 text-xs" style="color:#f87171;">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-semibold" style="color: #a3c4a3;">
                        Password
                    </label>
                    <div class="relative">
                        <!-- Icon kiri -->
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4" style="color:#4a6b4a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-dark w-full rounded-xl pl-10 pr-12 py-3 text-sm"
                        />
                        <!-- Toggle show/hide -->
                        <button type="button" onclick="togglePassword()"
                                class="eye-btn absolute inset-y-0 right-0 pr-3.5">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="flex items-center gap-1 text-xs" style="color:#f87171;">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="checkbox-custom w-4 h-4 rounded"
                        />
                        <span class="text-sm" style="color: #5a8a5a;">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm font-semibold transition-colors duration-200"
                           style="color: #A8F04A;"
                           onmouseover="this.style.color='#C4F57A'"
                           onmouseout="this.style.color='#A8F04A'">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="btn-primary w-full py-3 px-6 rounded-xl text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk ke Dashboard
                </button>

                <!-- Divider -->
                <div class="flex items-center gap-4 py-1">
                    <div class="flex-1 border-t divider"></div>
                    <span class="text-xs tracking-widest" style="color: #2d4a2d;">SISTEM INFO</span>
                    <div class="flex-1 border-t divider"></div>
                </div>


                <!-- Footer -->
                <p class="text-center text-xs pt-1" style="color: #2d4a2d;">
                    © 2026 Gdronic. Smart Hydroponic IoT Platform
                </p>

            </form>
        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        icon.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                       a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878
                       9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3
                       3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543
                       7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                       7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
</script>

</body>
</html>