<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Gdronic</title>
    
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

        .divider { border-color: #2d4a2d; }
        .accent-text { color: #A8F04A; }

        .online-badge {
            background: rgba(168, 240, 74, 0.15);
            color: #A8F04A;
            border: 1px solid rgba(168, 240, 74, 0.3);
        }

        .card-dark {
            background: #1a2e1a;
            border: 1px solid #2d4a2d;
        }

        /* Info box */
        .info-box {
            background: rgba(168, 240, 74, 0.06);
            border: 1px solid rgba(168, 240, 74, 0.2);
            border-radius: 14px;
            padding: 16px;
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

        .img-content {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
        }

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

        /* Right panel */
        .right-panel {
            background: #152215;
            border-left: 1px solid #2d4a2d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        /* Back link */
        .back-link {
            color: #4a6b4a;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .back-link:hover { color: #A8F04A; }
        .back-link svg { stroke: currentColor; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0f1a0f; }
        ::-webkit-scrollbar-thumb { background: #2d4a2d; border-radius: 2px; }
    </style>
</head>
<body>

<div class="min-h-screen flex flex-col lg:flex-row">

    <!-- ══════════════════════════════════════════════════
         LEFT PANEL — Full Image
    ══════════════════════════════════════════════════ -->
    <div class="img-panel hidden lg:block lg:w-1/2 xl:w-3/5 relative">

        <img
            src="{{ asset('img/bg-smarthydroponic1.png') }}"
            alt="Smart Hydroponic Farm"
            loading="eager"
        />

        <div class="img-overlay"></div>

        <div class="img-content">

            <!-- TOP: Logo + LIVE badge -->
            <div class="flex items-center justify-between">
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

                <div class="live-badge">
                    <div class="status-dot w-2 h-2 rounded-full"></div>
                    LIVE
                </div>
            </div>

            <!-- BOTTOM: Ilustrasi teks di atas gambar -->
            <div style="
                background: rgba(15,26,15,0.75);
                border: 1px solid rgba(168,240,74,0.2);
                backdrop-filter: blur(12px);
                border-radius: 16px;
                padding: 20px 24px;
                max-width: 380px;
            ">
                <div class="flex items-center gap-2 mb-3">
                    <div class="status-dot w-2 h-2 rounded-full flex-shrink-0"></div>
                    <span class="text-xs font-bold tracking-widest uppercase accent-text">
                        Keamanan Akun
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-white leading-snug mb-1">
                    Reset Password<br>
                    <span class="accent-text">Dengan Mudah & Aman</span>
                </h2>
                <p class="text-sm mb-4" style="color: #6a9a6a;">
                    Kami akan mengirimkan link reset password ke email Anda. Pastikan email yang dimasukkan sudah benar.
                </p>
                <!-- Steps -->
                <div class="space-y-2">
                    @foreach([
                        ['01', 'Masukkan email akun Anda'],
                        ['02', 'Cek inbox / folder spam'],
                        ['03', 'Klik link & buat password baru'],
                    ] as $step)
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-extrabold accent-text w-6 flex-shrink-0">
                            {{ $step[0] }}
                        </span>
                        <span class="text-xs" style="color: #6a9a6a;">{{ $step[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         RIGHT PANEL — Forgot Password Form
    ══════════════════════════════════════════════════ -->
    <div class="right-panel w-full lg:w-1/2 xl:w-2/5 min-h-screen lg:min-h-0">

        <div class="w-full max-w-md">

            <!-- Mobile Logo -->
            <div class="flex items-center justify-center gap-3 mb-8 lg:hidden">
                <div class="logo-ring w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <path d="M12 2C6 2 2 8 2 14c0 4 2.5 7 6 8l1-3c-2-.8-3-2.8-3-5 0-4 2.7-8 6-8s6 4 6 8c0 2.2-1 4.2-3 5l1 3c3.5-1 6-4 6-8 0-6-4-12-10-12z" fill="#a8f04a"/>
                        <circle cx="12" cy="14" r="2.5" fill="#a8f04a"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg leading-none">Gdronic.</p>
                    <p class="text-xs" style="color: #A8F04A;">Smart Hydroponic System</p>
                </div>
            </div>

            <!-- Back to login -->
            <div class="mb-6">
                <a href="{{ route('login') }}" class="back-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>

            <!-- Header -->
            <div class="mb-7">
                <div class="flex items-center gap-2 mb-3">
                    <div class="status-dot w-2 h-2 rounded-full flex-shrink-0"></div>
                    <span class="text-xs font-bold tracking-widest uppercase accent-text">
                        Reset Password
                    </span>
                </div>
                <h2 class="text-3xl font-extrabold mb-1" style="color: #e8f5e8;">
                    Lupa Password? 🔑
                </h2>
                <p class="text-sm" style="color: #4a6b4a;">
                    Tenang! Masukkan email Anda dan kami akan kirimkan link untuk membuat password baru.
                </p>
            </div>

            <!-- Session Status (sukses kirim email) -->
            @if (session('status'))
                <div class="online-badge rounded-xl p-4 text-sm font-medium flex items-start gap-3 mb-6">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold mb-0.5">Email Terkirim!</p>
                        <p class="text-xs" style="color: rgba(168,240,74,0.75);">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Info box -->
                <div class="info-box flex items-start gap-3">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5 accent-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs leading-relaxed" style="color: #6a9a6a;">
                        Link reset password akan dikirim ke email yang terdaftar. 
                        Link hanya berlaku selama <span class="accent-text font-semibold">60 menit</span>.
                    </p>
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold" style="color: #a3c4a3;">
                        Email Address
                    </label>
                    <div class="relative">
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

                <!-- Submit -->
                <button type="submit"
                        class="btn-primary w-full py-3 px-6 rounded-xl text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Kirim Link Reset Password
                </button>

                <!-- Divider -->
                <div class="flex items-center gap-4 py-1">
                    <div class="flex-1 border-t divider"></div>
                    <span class="text-xs tracking-widest" style="color: #2d4a2d;">ATAU</span>
                    <div class="flex-1 border-t divider"></div>
                </div>

                <!-- Kembali login -->
                <div class="text-center">
                    <span class="text-sm" style="color: #4a6b4a;">Ingat password Anda?</span>
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold ml-1 transition-colors duration-200"
                       style="color: #A8F04A;"
                       onmouseover="this.style.color='#C4F57A'"
                       onmouseout="this.style.color='#A8F04A'">
                        Masuk Sekarang
                    </a>
                </div>

                <!-- Footer -->
                <p class="text-center text-xs pt-1" style="color: #2d4a2d;">
                    © 2026 Gdronic. Smart Hydroponic IoT Platform
                </p>

            </form>
        </div>
    </div>

</div>

</body>
</html>