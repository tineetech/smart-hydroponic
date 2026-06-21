@extends('layouts.admin')

@section('content')

    <!-- HEADER -->
    <header id="header">
        <button onclick="toggleSidebar()" class="lg:hidden"
            style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
        <div style="flex:1;min-width:0;">
            <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Kelola Notifikasi</div>
            <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Manajemen notifikasi sistem hidroponik</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            @include('components.admin.notif-dropdown')
            <a href="{{ route('profile.edit') }}"
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;text-decoration:none;">
                {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'G' }}</a>
        </div>
    </header>

    <main style="flex:1;padding:24px;max-width:1400px;width:100%;margin:0 auto;" id="page-content">
        <div id="page-kelola-notifikasi">

            @if (session('toast_success'))
                <div id="php-toast" data-type="success" data-msg="{{ session('toast_success') }}"></div>
            @elseif(session('toast_info'))
                <div id="php-toast" data-type="info" data-msg="{{ session('toast_info') }}"></div>
            @elseif(session('toast_danger'))
                <div id="php-toast" data-type="danger" data-msg="{{ session('toast_danger') }}"></div>
            @endif

            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
                <div>
                    <div style="font-size:20px;font-weight:800;color:#E8F0E8;">Notifikasi Sistem</div>
                    <div style="font-size:12px;color:#4D5E4D;margin-top:2px;">Buat dan kelola notifikasi untuk pengguna sistem</div>
                </div>
                <button onclick="openModal('modal-tambah')"
                    style="display:flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16C47F,#12A86B);border:1px solid #16C47F;color:#0D1A0D;font-weight:700;font-size:13px;padding:10px 18px;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(22,196,127,0.25);transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                    Tambah Notifikasi
                </button>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:24px;">
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">TOTAL</div>
                    <div style="font-size:28px;font-weight:800;color:#E8F0E8;">{{ $stats['total'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">ALERT</div>
                    <div style="font-size:28px;font-weight:800;color:#F04A4A;">{{ $stats['alert'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">INFO</div>
                    <div style="font-size:28px;font-weight:800;color:#4A8AF0;">{{ $stats['info'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">BELUM DIBACA</div>
                    <div style="font-size:28px;font-weight:800;color:#F0A84A;">{{ $stats['unread'] }}</div>
                </div>
            </div>

            <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:14px;overflow:hidden;">
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;min-width:700px;">
                        <thead>
                            <tr style="background:#131613;border-bottom:1px solid #1E2C1E;">
                                <th style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">#</th>
                                <th style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">JUDUL</th>
                                <th style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">TIPE</th>
                                <th style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">LEVEL</th>
                                <th style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">CHANNEL</th>
                                <th style="padding:12px 16px;text-align:center;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">STATUS</th>
                                <th style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">TANGGAL</th>
                                <th style="padding:12px 16px;text-align:center;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $index => $notif)
                                <tr class="tbl-row" style="border-bottom:1px solid #131613;transition:background 0.15s;">
                                    <td style="padding:14px 16px;font-size:12px;color:#3D4E3D;font-weight:600;">{{ $index + 1 }}</td>
                                    <td style="padding:14px 16px;">
                                        <div style="font-size:13px;font-weight:700;color:#C4D4C4;">{{ $notif->judul }}</div>
                                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $notif->pesan }}</div>
                                    </td>
                                    <td style="padding:14px 16px;">
                                        @php
                                            $tipeWarna = match($notif->tipe) {
                                                'alert' => '#F04A4A',
                                                'error' => '#F04A4A',
                                                'ai_insight' => '#A84AF0',
                                                'jadwal' => '#4A8AF0',
                                                default => '#16C47F',
                                            };
                                        @endphp
                                        <span style="background:{{ $tipeWarna }}18;border:1px solid {{ $tipeWarna }}33;color:{{ $tipeWarna }};border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;text-transform:capitalize;">{{ str_replace('_', ' ', $notif->tipe) }}</span>
                                    </td>
                                    <td style="padding:14px 16px;">
                                        @php
                                            $levelWarna = match($notif->level) {
                                                'critical' => '#F04A4A',
                                                'high' => '#F0A84A',
                                                'medium' => '#4A8AF0',
                                                default => '#16C47F',
                                            };
                                        @endphp
                                        <span style="background:{{ $levelWarna }}18;border:1px solid {{ $levelWarna }}33;color:{{ $levelWarna }};border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;text-transform:capitalize;">{{ $notif->level }}</span>
                                    </td>
                                    <td style="padding:14px 16px;font-size:12px;color:#7A9A7A;font-weight:600;text-transform:capitalize;">{{ $notif->channel }}</td>
                                    <td style="padding:14px 16px;text-align:center;">
                                        @if ($notif->sudah_dibaca)
                                            <span class="badge-off">Dibaca</span>
                                        @else
                                            <span class="badge-ok">Baru</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 16px;font-size:11px;color:#4D5E4D;white-space:nowrap;">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                                    <td style="padding:14px 16px;">
                                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                            <button onclick="editNotif({{ $notif->id }})" title="Edit"
                                                style="background:#1A2A1A;border:1px solid #252C25;border-radius:7px;padding:6px 8px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;transition:all 0.2s;"
                                                onmouseover="this.style.borderColor='#F0A84A44';this.style.color='#F0A84A'"
                                                onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
                                                <i class="fa fa-pen" style="font-size:13px;"></i>
                                            </button>
                                            <button onclick="hapusNotif({{ $notif->id }}, '{{ addslashes($notif->judul) }}')" title="Hapus"
                                                style="background:#F04A4A10;border:1px solid #F04A4A22;border-radius:7px;padding:6px 8px;cursor:pointer;color:#F04A4A;display:flex;align-items:center;transition:all 0.2s;"
                                                onmouseover="this.style.background='#F04A4A20';this.style.borderColor='#F04A4A55'"
                                                onmouseout="this.style.background='#F04A4A10';this.style.borderColor='#F04A4A22'">
                                                <i class="fa fa-trash" style="font-size:13px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding:60px 20px;text-align:center;">
                                        <div style="font-size:40px;margin-bottom:12px;"><i class="fa fa-bell"></i></div>
                                        <div style="font-size:15px;font-weight:700;color:#4D5E4D;margin-bottom:4px;">Belum ada notifikasi</div>
                                        <div style="font-size:12px;color:#3D4E3D;">Klik "Tambah Notifikasi" untuk membuat notifikasi baru</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-top:1px solid #1A2A1A;flex-wrap:wrap;gap:8px;">
                    <div style="font-size:12px;color:#4D5E4D;">Menampilkan {{ $notifications->count() }} notifikasi</div>
                </div>
            </div>

        </div>

        <style>
            @keyframes slideUp {
                from { transform: translateY(20px); opacity: 0; }
                to   { transform: translateY(0); opacity: 1; }
            }
            .tbl-row:hover td {
                background: #1A2A1A !important;
            }
            select option {
                background: #0D0F0D;
                color: #C4D4C4;
            }
        </style>

        {{-- MODAL TAMBAH / EDIT --}}
        <div id="modal-tambah"
            style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:16px;">
            <div onclick="closeModal('modal-tambah')"
                style="position:absolute;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);"></div>
            <div style="position:relative;z-index:101;background:#181C18;border:1px solid #2A3A2A;border-radius:18px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.6);">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #1E2C1E;position:sticky;top:0;background:#181C18;z-index:10;border-radius:18px 18px 0 0;">
                    <div>
                        <div id="modal-title" style="font-size:16px;font-weight:800;color:#E8F0E8;">Tambah Notifikasi</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Buat notifikasi baru untuk sistem</div>
                    </div>
                    <button onclick="closeModal('modal-tambah')"
                        style="background:#1A2A1A;border:1px solid #252C25;border-radius:8px;padding:6px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;transition:all 0.2s;"
                        onmouseover="this.style.borderColor='#16C47F33';this.style.color='#16C47F'"
                        onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <form id="form-notif" method="POST" action="{{ route('kelola-notifikasi.store') }}" style="padding:24px;display:flex;flex-direction:column;gap:18px;">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST" />
                    <input type="hidden" name="id" id="f-id" value="" />

                    <div>
                        <label style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">JUDUL <span style="color:#F04A4A;">*</span></label>
                        <input type="text" name="judul" id="f-judul" required placeholder="contoh: pH Alert!"
                            style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'" />
                    </div>

                    <div>
                        <label style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">PESAN <span style="color:#F04A4A;">*</span></label>
                        <textarea name="pesan" id="f-pesan" required rows="3" placeholder="Deskripsi notifikasi..."
                            style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;resize:vertical;box-sizing:border-box;transition:border-color 0.2s;line-height:1.5;"
                            onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'"></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">TIPE <span style="color:#F04A4A;">*</span></label>
                            <select name="tipe" id="f-tipe" required
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">
                                <option value="info">Info</option>
                                <option value="alert">Alert</option>
                                <option value="error">Error</option>
                                <option value="ai_insight">AI Insight</option>
                                <option value="jadwal">Jadwal</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">LEVEL <span style="color:#F04A4A;">*</span></label>
                            <select name="level" id="f-level" required
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">CHANNEL <span style="color:#F04A4A;">*</span></label>
                            <select name="channel" id="f-channel" required
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">
                                <option value="database">Database</option>
                                <option value="telegram">Telegram</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #1E2C1E;margin-top:4px;">
                        <button type="button" onclick="closeModal('modal-tambah')"
                            style="background:#1A2A1A;border:1px solid #252C25;border-radius:10px;padding:10px 20px;font-size:13px;color:#5A7A5A;cursor:pointer;font-weight:600;transition:all 0.2s;">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit"
                            style="display:flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16C47F,#12A86B);border:1px solid #16C47F;color:#0D1A0D;font-weight:700;font-size:13px;padding:10px 24px;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(22,196,127,0.25);transition:all 0.2s;">
                            <span id="btn-submit-text">Simpan Notifikasi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
     MODAL — KONFIRMASI HAPUS
╔═════════════════════════════════════════════════════════════ --}}
        <div id="modal-hapus"
            style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:16px;">
            <div onclick="closeModal('modal-hapus')"
                style="position:absolute;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);"></div>
            <div style="position:relative;z-index:101;background:#181C18;border:1px solid #F04A4A33;border-radius:18px;width:100%;max-width:400px;padding:28px;box-shadow:0 25px 60px rgba(0,0,0,0.6);text-align:center;">
                <div style="width:56px;height:56px;background:#F04A4A18;border:1px solid #F04A4A33;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="#F04A4A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 11v6M14 11v6" stroke="#F04A4A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
                <div style="font-size:17px;font-weight:800;color:#E8F0E8;margin-bottom:8px;">Hapus Notifikasi</div>
                <div style="font-size:13px;color:#5A7A5A;margin-bottom:6px;">Anda akan menghapus:</div>
                <div id="hapus-judul" style="font-size:14px;font-weight:700;color:#F04A4A;margin-bottom:16px;padding:8px 16px;background:#F04A4A10;border-radius:8px;">—</div>
                <div style="font-size:12px;color:#4D5E4D;margin-bottom:24px;">Data notifikasi akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</div>
                <form id="form-hapus" method="POST" style="display:flex;gap:10px;">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeModal('modal-hapus')"
                        style="flex:1;background:#1A2A1A;border:1px solid #252C25;border-radius:10px;padding:11px;font-size:13px;color:#5A7A5A;cursor:pointer;font-weight:600;">
                        Batal
                    </button>
                    <button type="submit"
                        style="flex:1;background:linear-gradient(135deg,#c0392b,#96281b);border:1px solid #F04A4A;border-radius:10px;padding:11px;font-size:13px;color:#fff;cursor:pointer;font-weight:700;">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
     TOAST NOTIFICATION
╔═════════════════════════════════════════════════════════════ --}}
        <div id="toast" style="display:none;position:fixed;bottom:24px;right:24px;z-index:200;background:#181C18;border:1px solid #2A3A2A;border-radius:12px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,0.5);max-width:320px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div id="toast-icon" style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;font-weight:700;"></div>
                <div>
                    <div id="toast-title" style="font-size:13px;font-weight:700;color:#E8F0E8;"></div>
                    <div id="toast-msg" style="font-size:11px;color:#5A7A5A;margin-top:2px;"></div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
<script>
    const notifData = {!! $notifJson !!};

    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = '';
        if (id === 'modal-tambah') resetFormToAdd();
    }

    function resetFormToAdd() {
        const form = document.getElementById('form-notif');
        form.reset();
        form.action = "{{ route('kelola-notifikasi.store') }}";
        document.getElementById('form-method').value = 'POST';
        document.getElementById('modal-title').textContent = 'Tambah Notifikasi';
        document.getElementById('btn-submit-text').textContent = 'Simpan Notifikasi';
        document.getElementById('f-id').value = '';
    }

    function editNotif(id) {
        const d = notifData.find(n => n.id === id);
        if (!d) return;
        document.getElementById('modal-title').textContent = 'Edit Notifikasi';
        document.getElementById('btn-submit-text').textContent = 'Perbarui Notifikasi';
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('form-notif').action = '{{ url('admin/kelola-notifikasi') }}/' + id;
        document.getElementById('f-id').value = id;
        document.getElementById('f-judul').value = d.judul;
        document.getElementById('f-pesan').value = d.pesan;
        document.getElementById('f-tipe').value = d.tipe;
        document.getElementById('f-level').value = d.level;
        document.getElementById('f-channel').value = d.channel;
        openModal('modal-tambah');
    }

    function hapusNotif(id, judul) {
        document.getElementById('hapus-judul').textContent = judul;
        document.getElementById('form-hapus').action = '{{ url('admin/kelola-notifikasi') }}/' + id;
        openModal('modal-hapus');
    }

    // ═══════════════════════════════════════════
    // TOAST
    // ═══════════════════════════════════════════
    let toastTimer = null;

    function showToast(type, title, msg) {
        const toast = document.getElementById('toast');
        const iconWrap = document.getElementById('toast-icon');
        const titleEl = document.getElementById('toast-title');
        const msgEl = document.getElementById('toast-msg');

        const cfg = {
            success: { bg: '#16C47F18', border: '#16C47F', icon: '✓', color: '#16C47F' },
            info:    { bg: '#4A8AF018', border: '#4A8AF0', icon: 'ℹ', color: '#4A8AF0' },
            danger:  { bg: '#F04A4A18', border: '#F04A4A', icon: '✕', color: '#F04A4A' },
        };
        const c = cfg[type] ?? cfg.info;

        toast.style.borderColor = c.border + '55';
        toast.style.animation = 'none';
        toast.offsetHeight;
        toast.style.animation = 'slideUp 0.3s ease';
        iconWrap.style.background = c.bg;
        iconWrap.style.border = `1px solid ${c.border}44`;
        iconWrap.style.color = c.color;
        iconWrap.textContent = c.icon;
        titleEl.textContent = title;
        msgEl.textContent = msg;

        toast.style.display = 'block';
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { toast.style.display = 'none'; }, 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const phpToast = document.getElementById('php-toast');
        if (phpToast) {
            const type = phpToast.dataset.type;
            const msg = phpToast.dataset.msg;
            const titles = { success: 'Berhasil', info: 'Info', danger: 'Dihapus' };
            showToast(type, titles[type] ?? 'Info', msg);
        }
    });
</script>
@endsection
