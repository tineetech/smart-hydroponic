@php
    use App\Models\Notifikasi;
    $notifications = Notifikasi::latest()->limit(5)->get();
    $notifUnreadCount = Notifikasi::belumDibaca()->count();
@endphp
<div id="notif-wrapper" style="position:relative;">
    <button id="notif-btn"
        style="background:#131613;border:1px solid #1E2C1E;border-radius:10px;padding:8px;cursor:pointer;position:relative;color:#7A9A7A;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @if ($notifUnreadCount > 0)
            <span id="notif-count-badge"
                style="position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;background:#F04A4A;border-radius:99px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;border:2px solid #131613;line-height:1;padding:0 4px;">{{ $notifUnreadCount > 99 ? '99+' : $notifUnreadCount }}</span>
        @endif
    </button>

    <!-- Dropdown -->
    <div id="notif-dropdown"
        style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:360px;max-width:90vw;background:#181C18;border:1px solid #252C25;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,0.5);z-index:100;overflow:hidden;">
        <!-- Header -->
        <div style="padding:16px;border-bottom:1px solid #252C25;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:14px;font-weight:700;color:#E8F0E8;">Notifikasi</span>
            @if ($notifUnreadCount > 0)
                <span style="font-size:11px;color:#5A7A5A;">{{ $notifUnreadCount }} belum dibaca</span>
            @endif
        </div>

        <!-- List -->
        <div style="max-height:400px;overflow-y:auto;">
            @forelse ($notifications as $notif)
                <div style="padding:14px 16px;border-bottom:1px solid #1E2C1E;{{ !$notif->sudah_dibaca ? 'background:#1A231A;' : '' }}">
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0;background:{{ $notif->tipe === 'alert' || $notif->tipe === 'error' ? '#F04A4A' : ($notif->tipe === 'ai_insight' ? '#A84AF0' : '#16C47F') }};"></div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:13px;font-weight:600;color:#E8F0E8;margin-bottom:2px;">{{ $notif->judul }}</div>
                            <div style="font-size:12px;color:#5A6B5A;margin-bottom:4px;line-height:1.4;">{{ $notif->pesan }}</div>
                            <div style="font-size:10px;color:#3D4E3D;">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:40px 20px;">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="#3D4E3D" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div style="font-size:13px;color:#4D5E4D;">Belum ada notifikasi</div>
                </div>
            @endforelse
        </div>

        @if ($notifications->isNotEmpty())
            <a href="#" id="notif-mark-read"
                style="display:block;padding:12px 16px;border-top:1px solid #252C25;text-align:center;font-size:12px;color:#16C47F;font-weight:600;text-decoration:none;transition:opacity 0.2s;"
                onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'"
                onclick="event.preventDefault();fetch('{{ route('admin.notifications.readAll') }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=\'csrf-token\']').content,'Accept':'application/json'}}).then(()=>{location.reload()})">
                Tandai semua sudah dibaca
            </a>
        @endif
    </div>
</div>
