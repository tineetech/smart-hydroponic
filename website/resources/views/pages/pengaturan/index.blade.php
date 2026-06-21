@extends('layouts.admin')

@php
    $kategoriIkon = [
        'ai_config' => '<svg width="18" height="18" fill="none" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="#A84AF0" stroke-width="1.5"/>
            <path d="M8 12h8M12 8v8" stroke="#A84AF0" stroke-width="2" stroke-linecap="round"/>
        </svg>',
        'lokasi' => '<svg width="18" height="18" fill="none" viewBox="0 0 24 24">
            <path d="M12 2C8 9 6 13 6 16a6 6 0 0 0 12 0c0-3-2-7-6-14z" stroke="#4AF0C8" stroke-width="1.8" fill="#4AF0C822"/>
            <circle cx="12" cy="16" r="2" fill="#4AF0C8"/>
        </svg>',
        'sistem' => '<svg width="18" height="18" fill="none" viewBox="0 0 24 24">
            <rect x="2" y="2" width="20" height="8" rx="2" stroke="#4A8AF0" stroke-width="1.8"/>
            <rect x="2" y="14" width="20" height="8" rx="2" stroke="#4A8AF0" stroke-width="1.8"/>
            <circle cx="6" cy="6" r="1" fill="#4A8AF0"/>
            <circle cx="6" cy="18" r="1" fill="#4A8AF0"/>
        </svg>',
        'notifikasi' => '<svg width="18" height="18" fill="none" viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="#F0A84A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
    ];

    $kategoriWarna = [
        'ai_config' => '#A84AF0',
        'lokasi' => '#4AF0C8',
        'sistem' => '#4A8AF0',
        'notifikasi' => '#F0A84A',
    ];
@endphp

@section('content')
    <header id="header">
        <button onclick="toggleSidebar()" class="lg:hidden"
            style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
        <div style="flex:1;min-width:0;">
            <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Pengaturan Sistem</div>
            <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Konfigurasi sistem hidroponik</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            @include('components.admin.notif-dropdown')

            <a href="{{ route('profile.edit') }}"
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;text-decoration:none;">
                {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'G' }}</a>
        </div>
    </header>

    <main style="flex:1;padding:24px;max-width:1400px;width:100%;margin:0 auto;" id="page-content">

        @if (session('toast_success'))
            <div id="session-toast" data-type="success" data-msg="{{ session('toast_success') }}"></div>
        @endif

        <form method="POST" action="{{ route('admin.pengaturan.update') }}">
            @csrf
            @method('PATCH')

            @foreach ($settings as $kategori => $items)
            @php $warna = $kategoriWarna[$kategori] ?? '#16C47F'; @endphp
            <div class="stat-card" style="padding:24px;margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #1E2C1E;">
                    <div style="width:40px;height:40px;background:{{ $warna }}18;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid {{ $warna }}33;flex-shrink:0;">
                        {!! $kategoriIkon[$kategori] ?? '' !!}
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:#C4D4C4;">{{ $kategoriLabels[$kategori] ?? $kategori }}</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">{{ count($items) }} pengaturan</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;">
                    @foreach ($items as $setting)
                    @php
                        $nilai = $setting->nilai_decoded;
                        $inputId = 'setting-' . $setting->kunci;
                        $isEncrypted = $setting->is_encrypted;
                        $tipe = $setting->tipe_data;
                        $showValue = $isEncrypted ? ($nilai ? '••••••••' : '') : $nilai;
                    @endphp
                    <div style="background:#131613;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <label for="{{ $inputId }}" style="font-size:12px;font-weight:600;color:#A8BEA8;text-transform:uppercase;letter-spacing:0.04em;">
                                {{ str_replace('_', ' ', $setting->kunci) }}
                            </label>
                            @if ($isEncrypted)
                            <span style="font-size:9px;background:#A84AF018;border:1px solid #A84AF033;border-radius:4px;padding:2px 6px;color:#A84AF0;font-weight:600;letter-spacing:0.05em;">ENKRIPSI</span>
                            @endif
                        </div>

                        @if ($tipe === 'boolean')
                            <div style="display:flex;align-items:center;gap:12px;">
                                <label class="toggle-wrap">
                                    <input type="hidden" name="settings[{{ $setting->kunci }}]" value="false">
                                    <input type="checkbox" id="{{ $inputId }}" name="settings[{{ $setting->kunci }}]" value="true"
                                        @if ($nilai) checked @endif>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span style="font-size:12px;color:{{ $nilai ? '#16C47F' : '#5A7A5A' }};font-weight:600;">
                                    {{ $nilai ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        @elseif ($tipe === 'integer' || $tipe === 'float')
                            <input type="number" step="{{ $tipe === 'float' ? '0.01' : '1' }}"
                                id="{{ $inputId }}" name="settings[{{ $setting->kunci }}]"
                                value="{{ $nilai }}"
                                style="width:100%;background:#0D0F0D;border:1px solid #1E2C1E;border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;font-weight:600;outline:none;">
                        @else
                            <div style="position:relative;">
                                <input type="{{ $isEncrypted ? 'password' : 'text' }}"
                                    id="{{ $inputId }}" name="settings[{{ $setting->kunci }}]"
                                    value="{{ $isEncrypted ? '' : ($nilai ?? '') }}"
                                    placeholder="{{ $isEncrypted ? ($nilai ? '•••••••• (biarkan kosong jika tidak diubah)' : 'Masukkan API Key') : '' }}"
                                    style="width:100%;background:#0D0F0D;border:1px solid #1E2C1E;border-radius:8px;padding:10px 12px;color:#E8F0E8;font-size:14px;font-weight:600;outline:none;{{ $isEncrypted && $nilai ? 'padding-right:44px;' : '' }}">
                                @if ($isEncrypted && $nilai)
                                <button type="button" onclick="togglePassword('{{ $inputId }}')"
                                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#5A7A5A;padding:6px;font-size:14px;">
                                    &#128065;
                                </button>
                                @endif
                            </div>
                        @endif

                        @if ($setting->deskripsi)
                        <div style="font-size:11px;color:#4D5E4D;margin-top:8px;line-height:1.4;">{{ $setting->deskripsi }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div style="text-align:right;padding:8px 0 24px;">
                <button type="submit"
                    style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16C47F,#12A86B);border:1px solid #16C47F;color:#0D1A0D;font-weight:700;font-size:14px;padding:12px 28px;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(22,196,127,0.25);transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2"/>
                        <path d="M17 21v-8H7v8M7 3v5h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </main>
@endsection

@section('scripts')
<script>
    function togglePassword(id) {
        const el = document.getElementById(id);
        if (el.type === 'password') {
            el.type = 'text';
            setTimeout(() => el.type = 'password', 3000);
        } else {
            el.type = 'password';
        }
    }

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

    document.querySelectorAll('.toggle-wrap input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const label = this.closest('div').querySelector('span');
            if (label) {
                label.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                label.style.color = this.checked ? '#16C47F' : '#5A7A5A';
            }
        });
    });
</script>
@endsection
