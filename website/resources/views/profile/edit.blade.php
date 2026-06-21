@extends('layouts.admin')

@section('styles')
<style>
    @media (max-width: 640px) {
        .profile-header {
            flex-direction: column !important;
            text-align: center !important;
            padding: 20px 16px !important;
        }
        .profile-header-badges {
            justify-content: center !important;
            width: 100% !important;
        }
        .profile-header-badges > div {
            flex: 1 !important;
            min-width: 0 !important;
        }
        .profile-section {
            padding: 16px !important;
        }
        .profile-section .form-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@section('content')
    <header id="header">
        <button onclick="toggleSidebar()" class="lg:hidden"
            style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
        <div style="flex:1;min-width:0;">
            <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Profil Saya</div>
            <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Kelola informasi akun kamu</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            @include('components.admin.notif-dropdown')

            <a href="{{ route('profile.edit') }}"
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;text-decoration:none;">
                {{ substr(auth()->user()->name, 0, 1) }}</a>
        </div>
    </header>

    <main style="flex:1;padding:24px;max-width:1000px;width:100%;margin:0 auto;" id="page-content">

        @if (session('status') === 'profile-updated')
            <div id="session-toast" data-type="success" data-msg="Profil berhasil diperbarui!"></div>
        @elseif (session('status') === 'password-updated')
            <div id="session-toast" data-type="success" data-msg="Password berhasil diganti!"></div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header"
            style="background:linear-gradient(135deg,#1A2A1A,#131F13);border:1px solid #2A3A2A;border-radius:16px;padding:28px 24px;margin-bottom:24px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;position:relative;overflow:hidden;">
            <div style="position:absolute;right:0;top:0;bottom:0;width:300px;background:radial-gradient(ellipse at right,#16C47F0A,transparent);pointer-events:none;"></div>
            <div
                style="width:64px;height:64px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#0D1A0D;flex-shrink:0;border:3px solid #16C47F44;">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div style="flex:1;min-width:0;text-align:left;">
                <div style="font-size:20px;font-weight:800;color:#E8F0E8;">Halo, {{ auth()->user()->name }}! 👋</div>
                <div style="font-size:13px;color:#5A7A5A;margin-top:4px;">{{ auth()->user()->email }}</div>
            </div>
            <div class="profile-header-badges" style="display:flex;gap:8px;flex-wrap:wrap;">
                <div style="text-align:center;background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:10px 16px;flex:1;">
                    <div style="font-size:13px;font-weight:700;color:#16C47F;">Admin</div>
                    <div style="font-size:10px;color:#4D5E4D;margin-top:2px;">Role</div>
                </div>
                <div style="text-align:center;background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:10px 16px;flex:1;">
                    <div style="font-size:13px;font-weight:700;color:#E8F0E8;">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y') : '-' }}</div>
                    <div style="font-size:10px;color:#4D5E4D;margin-top:2px;">Bergabung</div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr;gap:20px;">
            <!-- Profile Info -->
            <div class="stat-card profile-section" style="padding:24px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #1E2C1E;">
                    <div style="width:36px;height:36px;background:#16C47F18;border-radius:8px;display:flex;align-items:center;justify-content:center;border:1px solid #16C47F33;flex-shrink:0;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="#16C47F" stroke-width="1.8" stroke-linecap="round"/>
                            <circle cx="12" cy="7" r="4" stroke="#16C47F" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Informasi Profil</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Nama dan email kamu</div>
                    </div>
                </div>

                <form class="form-grid" method="POST" action="{{ route('profile.update') }}" style="display:grid;grid-template-columns:1fr;gap:16px;">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label style="font-size:11px;font-weight:600;color:#7A9A7A;letter-spacing:0.05em;text-transform:uppercase;display:block;margin-bottom:6px;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            style="width:100%;background:#0D0F0D;border:1px solid {{ $errors->has('name') ? '#F04A4A' : '#1E2C1E' }};border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;outline:none;box-sizing:border-box;">
                        @error('name')
                            <div style="font-size:11px;color:#F04A4A;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size:11px;font-weight:600;color:#7A9A7A;letter-spacing:0.05em;text-transform:uppercase;display:block;margin-bottom:6px;">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            style="width:100%;background:#0D0F0D;border:1px solid {{ $errors->has('email') ? '#F04A4A' : '#1E2C1E' }};border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;outline:none;box-sizing:border-box;">
                        @error('email')
                            <div style="font-size:11px;color:#F04A4A;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="text-align:right;padding-top:4px;">
                        <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16C47F,#12A86B);border:1px solid #16C47F;color:#0D1A0D;font-weight:700;font-size:13px;padding:10px 22px;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(22,196,127,0.25);transition:all 0.2s;">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2"/>
                                <path d="M17 21v-8H7v8M7 3v5h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password -->
            <div class="stat-card profile-section" style="padding:24px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #1E2C1E;">
                    <div style="width:36px;height:36px;background:#F0A84A18;border-radius:8px;display:flex;align-items:center;justify-content:center;border:1px solid #F0A84A33;flex-shrink:0;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" stroke="#F0A84A" stroke-width="1.8"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:#C4D4C4;">Ganti Password</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Pastoin password baru lo beda dari yang lama ya</div>
                    </div>
                </div>

                <form class="form-grid" method="POST" action="{{ route('password.update') }}" style="display:grid;grid-template-columns:1fr;gap:16px;">
                    @csrf
                    @method('PUT')

                    <div>
                        <label style="font-size:11px;font-weight:600;color:#7A9A7A;letter-spacing:0.05em;text-transform:uppercase;display:block;margin-bottom:6px;">Password Lama</label>
                        <input type="password" name="current_password" required
                            style="width:100%;background:#0D0F0D;border:1px solid {{ $errors->updatePassword->has('current_password') ? '#F04A4A' : '#1E2C1E' }};border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;outline:none;box-sizing:border-box;">
                        @error('current_password', 'updatePassword')
                            <div style="font-size:11px;color:#F04A4A;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size:11px;font-weight:600;color:#7A9A7A;letter-spacing:0.05em;text-transform:uppercase;display:block;margin-bottom:6px;">Password Baru</label>
                        <input type="password" name="password" required
                            style="width:100%;background:#0D0F0D;border:1px solid {{ $errors->updatePassword->has('password') ? '#F04A4A' : '#1E2C1E' }};border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;outline:none;box-sizing:border-box;">
                        @error('password', 'updatePassword')
                            <div style="font-size:11px;color:#F04A4A;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size:11px;font-weight:600;color:#7A9A7A;letter-spacing:0.05em;text-transform:uppercase;display:block;margin-bottom:6px;">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required
                            style="width:100%;background:#0D0F0D;border:1px solid #1E2C1E;border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;outline:none;box-sizing:border-box;">
                    </div>

                    <div style="text-align:right;padding-top:4px;">
                        <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;background:#F0A84A18;border:1px solid #F0A84A44;color:#F0A84A;font-weight:700;font-size:13px;padding:10px 22px;border-radius:10px;cursor:pointer;transition:all 0.2s;">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" stroke="#F0A84A" stroke-width="1.8"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
<script>
    const toastEl = document.getElementById('session-toast');
    if (toastEl) {
        const type = toastEl.dataset.type;
        const msg = toastEl.dataset.msg;
        const bg = type === 'success' ? '#16C47F' : type === 'danger' ? '#F04A4A' : '#4A8AF0';
        const existing = document.querySelector('.custom-toast');
        if (existing) existing.remove();
        const div = document.createElement('div');
        div.className = 'custom-toast';
        div.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:999;background:#181C18;border:1px solid ' + bg + '44;border-left:4px solid ' + bg + ';border-radius:12px;padding:14px 20px;color:#E8F0E8;font-size:13px;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,0.5);animation:slideUp 0.3s ease;max-width:360px;';
        div.textContent = msg;
        document.body.appendChild(div);
        setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; }, 3000);
        setTimeout(() => div.remove(), 3500);
    }
</script>
@endsection
