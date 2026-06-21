@extends('layouts.admin')

@section('content')

    <!-- HEADER -->
    <header id="header">
        <!-- Hamburger (mobile & tablet) -->
        <button onclick="toggleSidebar()" class="lg:hidden"
            style="background:none;border:none;cursor:pointer;color:#7A9A7A;padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>

        <!-- Page Title -->
        <div style="flex:1;min-width:0;">
            <div id="page-title" style="font-size:16px;font-weight:700;color:#E8F0E8;">Kelola Sensor</div>
            <div id="page-breadcrumb" style="font-size:11px;color:#4D5E4D;margin-top:1px;">Konfigurasi dan manajemen sensor</div>
        </div>

        <!-- Header Right -->
        <div style="display:flex;align-items:center;gap:8px;">
            <!-- Last update -->
            <div style="display:none;" class="md:flex" id="last-update-badge"
                style="display:flex;align-items:center;gap:6px;background:#131613;border:1px solid #1E2C1E;border-radius:8px;padding:6px 12px;">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="#5A7A5A" stroke-width="1.5" />
                    <path d="M12 6v6l4 2" stroke="#5A7A5A" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <span style="font-size:11px;color:#5A7A5A;">Update: <span id="last-update"
                        style="color:#16C47F;">--:--:--</span></span>
            </div>

            @include('components.admin.notif-dropdown')

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
                style="width:36px;height:36px;background:linear-gradient(135deg,#16C47F,#12A86B);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#0D1A0D;cursor:pointer;flex-shrink:0;text-decoration:none;">
                {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'G' }}</a>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main style="flex:1;padding:24px;max-width:1400px;width:100%;margin:0 auto;" id="page-content">
        <div id="page-kelola-sensor" style="">

            {{-- ── Toast dari session ── --}}
            @if (session('toast_success'))
                <div id="php-toast" data-type="success" data-msg="{{ session('toast_success') }}"></div>
            @elseif(session('toast_info'))
                <div id="php-toast" data-type="info" data-msg="{{ session('toast_info') }}"></div>
            @elseif(session('toast_danger'))
                <div id="php-toast" data-type="danger" data-msg="{{ session('toast_danger') }}"></div>
            @endif

            {{-- ── Header Page ── --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
                <div>
                    <div style="font-size:20px;font-weight:800;color:#E8F0E8;">Kelola Komponen</div>
                    <div style="font-size:12px;color:#4D5E4D;margin-top:2px;">Manajemen sensor & aktuator sistem
                        hidroponik</div>
                </div>
                <button onclick="openModal('modal-tambah')"
                    style="display:flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16C47F,#12A86B);border:1px solid #16C47F;color:#0D1A0D;font-weight:700;font-size:13px;padding:10px 18px;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(22,196,127,0.25);transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                    Tambah Komponen
                </button>
            </div>

            {{-- ── Stats Summary ── --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:24px;">
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">
                        TOTAL KOMPONEN</div>
                    <div style="font-size:28px;font-weight:800;color:#E8F0E8;">{{ $stats['total'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">
                        SENSOR</div>
                    <div style="font-size:28px;font-weight:800;color:#16C47F;">{{ $stats['sensor'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">
                        AKTUATOR</div>
                    <div style="font-size:28px;font-weight:800;color:#4A8AF0;">{{ $stats['aktuator'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">
                        AKTIF</div>
                    <div style="font-size:28px;font-weight:800;color:#16C47F;">{{ $stats['aktif'] }}</div>
                </div>
                <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:12px;padding:16px;">
                    <div style="font-size:10px;color:#4D5E4D;font-weight:700;letter-spacing:0.08em;margin-bottom:6px;">
                        NON-AKTIF</div>
                    <div style="font-size:28px;font-weight:800;color:#5A6B5A;">{{ $stats['non_aktif'] }}</div>
                </div>
            </div>

            {{-- ── Filter & Search Bar ── --}}
            <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:14px;padding:16px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:10px;">
                    <div style="position:relative;flex:1;min-width:200px;">
                        <div style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" stroke="#4D5E4D" stroke-width="2" />
                                <path d="M21 21l-4.35-4.35" stroke="#4D5E4D" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <input id="search-komponen" type="text" placeholder="Cari nama, tipe, lokasi..."
                            oninput="filterTable()"
                            style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:9px 12px 9px 36px;font-size:13px;color:#C4D4C4;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'" />
                    </div>
                    <select id="filter-jenis" onchange="filterTable()"
                        style="background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:9px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;min-width:130px;">
                        <option value="">Semua Jenis</option>
                        <option value="sensor">Sensor</option>
                        <option value="aktuator">Aktuator</option>
                    </select>
                    <select id="filter-status" onchange="filterTable()"
                        style="background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:9px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;min-width:130px;">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="non-active">Non-Aktif</option>
                    </select>
                    <select id="filter-protokol" onchange="filterTable()"
                        style="background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:9px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;min-width:130px;">
                        <option value="">Semua Protokol</option>
                        <option value="I2C">I2C</option>
                        <option value="OneWire">OneWire</option>
                        <option value="Analog">Analog</option>
                        <option value="Digital">Digital</option>
                        <option value="PWM">PWM</option>
                    </select>
                    <button onclick="resetFilter()"
                        style="background:#1A2A1A;border:1px solid #252C25;border-radius:10px;padding:9px 14px;font-size:12px;color:#5A7A5A;cursor:pointer;font-weight:600;white-space:nowrap;transition:all 0.2s;"
                        onmouseover="this.style.borderColor='#16C47F33';this.style.color='#16C47F'"
                        onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
                        ↺ Reset
                    </button>
                </div>
            </div>

            {{-- ── Table ── --}}
            <div style="background:#181C18;border:1px solid #1E2C1E;border-radius:14px;overflow:hidden;">
                <div style="overflow-x:auto;">
                    <table id="tbl-komponen" style="width:100%;border-collapse:collapse;min-width:900px;">
                        <thead>
                            <tr style="background:#131613;border-bottom:1px solid #1E2C1E;">
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    #</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    NAMA KOMPONEN</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    JENIS</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    TIPE / MODEL</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    PIN DATA</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    SATUAN</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    BATAS NILAI</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    LOKASI</th>
                                <th
                                    style="padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    PROTOKOL</th>
                                <th
                                    style="padding:12px 16px;text-align:center;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    STATUS</th>
                                <th
                                    style="padding:12px 16px;text-align:center;font-size:10px;font-weight:700;color:#3D4E3D;letter-spacing:0.1em;white-space:nowrap;">
                                    AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-body">
                            @forelse($komponen as $index => $row)
                                <tr class="tbl-row" data-nama="{{ strtolower($row->nama_komponen) }}"
                                    data-jenis="{{ $row->jenis_komponen }}" data-status="{{ $row->status }}"
                                    data-protokol="{{ $row->protokol }}"
                                    data-tipe="{{ strtolower($row->tipe_komponen ?? '') }}"
                                    data-lokasi="{{ strtolower($row->lokasi ?? '') }}"
                                    data-deskripsi="{{ strtolower($row->deskripsi ?? '') }}"
                                    style="border-bottom:1px solid #131613;transition:background 0.15s;">

                                    {{-- ID --}}
                                    <td style="padding:14px 16px;font-size:12px;color:#3D4E3D;font-weight:600;">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- Nama + Deskripsi --}}
                                    <td style="padding:14px 16px;">
                                        <div style="font-size:13px;font-weight:700;color:#C4D4C4;">
                                            {{ $row->nama_komponen }}</div>
                                        @if ($row->deskripsi)
                                            <div
                                                style="font-size:11px;color:#3D4E3D;margin-top:2px;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {{ $row->deskripsi }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Jenis Badge --}}
                                    <td style="padding:14px 16px;">
                                        @if ($row->jenis_komponen === 'sensor')
                                            <span
                                                style="background:#16C47F18;border:1px solid #16C47F33;color:#16C47F;border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;">
                                                Sensor
                                            </span>
                                        @else
                                            <span
                                                style="background:#4A8AF018;border:1px solid #4A8AF033;color:#4A8AF0;border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;">
                                                Aktuator
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tipe --}}
                                    <td style="padding:14px 16px;font-size:12px;color:#7A9A7A;font-weight:600;">
                                        {{ $row->tipe_komponen ?? '—' }}
                                    </td>

                                    {{-- Pin Data --}}
                                    <td style="padding:14px 16px;">
                                        @if ($row->pin_data)
                                            @php
                                                $pins = is_array($row->pin_data)
                                                    ? $row->pin_data
                                                    : json_decode($row->pin_data, true);
                                            @endphp
                                            @if (is_array($pins))
                                                @foreach ($pins as $k => $v)
                                                    <span
                                                        style="background:#131613;border:1px solid #1E2C1E;border-radius:4px;padding:1px 5px;font-size:10px;font-family:monospace;color:#7A9A7A;margin-right:2px;">
                                                        {{ $k }}:
                                                        {{ is_bool($v) ? ($v ? 'true' : 'false') : $v }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span
                                                    style="font-size:11px;color:#5A7A5A;font-family:monospace;">{{ $row->pin_data }}</span>
                                            @endif
                                        @else
                                            <span style="color:#3D4E3D;">—</span>
                                        @endif
                                    </td>

                                    {{-- Satuan --}}
                                    <td style="padding:14px 16px;font-size:13px;color:#C4D4C4;font-weight:600;">
                                        {{ $row->satuan ?? '—' }}
                                    </td>

                                    {{-- Batas Nilai --}}
                                    <td style="padding:14px 16px;">
                                        @if ($row->batas_nilai)
                                            @php
                                                $batas = is_array($row->batas_nilai)
                                                    ? $row->batas_nilai
                                                    : json_decode($row->batas_nilai, true);
                                            @endphp
                                            @if (is_array($batas))
                                                <span style="font-size:10px;color:#4D5E4D;">
                                                    {{ $batas['optimal_min'] ?? ($batas['min'] ?? '?') }}
                                                    –
                                                    {{ $batas['optimal_max'] ?? ($batas['max'] ?? '?') }}
                                                    <span style="color:#3D4E3D;">{{ $row->satuan }}</span>
                                                </span>
                                            @else
                                                <span style="font-size:10px;color:#4D5E4D;">{{ $row->batas_nilai }}</span>
                                            @endif
                                        @else
                                            <span style="color:#3D4E3D;">—</span>
                                        @endif
                                    </td>

                                    {{-- Lokasi --}}
                                    <td style="padding:14px 16px;font-size:12px;color:#6A8A6A;">
                                        {{ $row->lokasi ?? '—' }}
                                    </td>

                                    {{-- Protokol --}}
                                    <td style="padding:14px 16px;">
                                        @if ($row->protokol)
                                            <span
                                                style="background:#1E2C1E;border-radius:5px;padding:2px 8px;font-size:10px;color:#6A8A6A;font-weight:600;">
                                                {{ $row->protokol }}
                                            </span>
                                        @else
                                            <span style="color:#3D4E3D;">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td style="padding:14px 16px;text-align:center;text-wrap:nowrap">
                                        @if ($row->status === 'active')
                                            <span class="badge-ok">Aktif</span>
                                        @else
                                            <span class="badge-off">Non-Aktif</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td style="padding:14px 16px;">
                                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
{{-- Detail --}}
<button onclick="showDetail({{ $row->id }})" title="Lihat Detail"
    style="background:#1A2A1A;border:1px solid #252C25;border-radius:7px;padding:6px 8px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;transition:all 0.2s;"
    onmouseover="this.style.borderColor='#16C47F44';this.style.color='#16C47F'"
    onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
    <i class="fa fa-eye" style="font-size:13px;"></i>
</button>

{{-- Edit --}}
<button onclick="editKomponen({{ $row->id }})" title="Edit"
    style="background:#1A2A1A;border:1px solid #252C25;border-radius:7px;padding:6px 8px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;transition:all 0.2s;"
    onmouseover="this.style.borderColor='#F0A84A44';this.style.color='#F0A84A'"
    onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
    <i class="fa fa-pen" style="font-size:13px;"></i>
</button>

{{-- Toggle Status --}}
<form action="{{ route('kelola-sensor.toggle-status', $row->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('PATCH')
    <button type="submit" title="Toggle Status"
        style="background:#1A2A1A;border:1px solid #252C25;border-radius:7px;padding:6px 8px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;transition:all 0.2s;"
        onmouseover="this.style.borderColor='#4A8AF044';this.style.color='#4A8AF0'"
        onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
        <i class="fa fa-power-off" style="font-size:13px;"></i>
    </button>
</form>

{{-- Hapus --}}
<button onclick="hapusKomponen({{ $row->id }}, '{{ addslashes($row->nama_komponen) }}')" title="Hapus"
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
                                    <td colspan="11" style="padding:60px 20px;text-align:center;">
                                        <div style="font-size:40px;margin-bottom:12px;">📭</div>
                                        <div style="font-size:15px;font-weight:700;color:#4D5E4D;margin-bottom:4px;">
                                            Belum ada komponen</div>
                                        <div style="font-size:12px;color:#3D4E3D;">Klik "Tambah Komponen" untuk
                                            menambahkan sensor atau aktuator</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Empty state saat filter kosong --}}
                <div id="tbl-empty" style="display:none;text-align:center;padding:60px 20px;">
                    <div style="font-size:40px;margin-bottom:12px;"><i class="fa fa-search"></i></div>
                    <div style="font-size:15px;font-weight:700;color:#4D5E4D;margin-bottom:4px;">Tidak ada data
                        ditemukan</div>
                    <div style="font-size:12px;color:#3D4E3D;">Coba ubah filter atau kata kunci pencarian</div>
                </div>

                {{-- Table Footer --}}
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-top:1px solid #1A2A1A;flex-wrap:wrap;gap:8px;">
                    <div style="font-size:12px;color:#4D5E4D;" id="tbl-info">
                        Menampilkan {{ $komponen->count() }} komponen
                    </div>
                </div>
            </div>

        </div>{{-- end page-kelola-sensor --}}


        {{-- ══════════════════════════════════════════════════════════════
     MODAL — TAMBAH KOMPONEN
══════════════════════════════════════════════════════════════ --}}
        <div id="modal-tambah"
            style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:16px;">
            <div onclick="closeModal('modal-tambah')"
                style="position:absolute;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);"></div>

            <div
                style="position:relative;z-index:101;background:#181C18;border:1px solid #2A3A2A;border-radius:18px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.6);">

                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #1E2C1E;position:sticky;top:0;background:#181C18;z-index:10;border-radius:18px 18px 0 0;">
                    <div>
                        <div id="modal-tambah-title" style="font-size:16px;font-weight:800;color:#E8F0E8;">Tambah
                            Komponen Baru</div>
                        <div style="font-size:11px;color:#4D5E4D;margin-top:2px;">Isi form di bawah untuk
                            menambahkan sensor atau aktuator</div>
                    </div>
                    <button onclick="closeModal('modal-tambah')"
                        style="background:#1A2A1A;border:1px solid #252C25;border-radius:8px;padding:6px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;transition:all 0.2s;"
                        onmouseover="this.style.borderColor='#16C47F33';this.style.color='#16C47F'"
                        onmouseout="this.style.borderColor='#252C25';this.style.color='#5A7A5A'">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                <form id="form-komponen" method="POST" action="{{ route('kelola-sensor.store') }}"
                    style="padding:24px;display:flex;flex-direction:column;gap:18px;">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST" />

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div style="background:#F04A4A10;border:1px solid #F04A4A33;border-radius:10px;padding:14px 16px;">
                            @foreach ($errors->all() as $error)
                                <div style="font-size:12px;color:#F04A4A;margin-bottom:4px;">⚠ {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Row 1: Nama + Jenis --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">
                                NAMA KOMPONEN <span style="color:#F04A4A;">*</span>
                            </label>
                            <input type="text" name="nama_komponen" id="f-nama"
                                value="{{ old('nama_komponen') }}" required placeholder="contoh: Sensor pH Larutan"
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'" />
                        </div>
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">
                                JENIS KOMPONEN <span style="color:#F04A4A;">*</span>
                            </label>
                            <select name="jenis_komponen" id="f-jenis" required
                                onchange="toggleSatuanField(this.value)"
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="sensor" {{ old('jenis_komponen') === 'sensor' ? 'selected' : '' }}>
                                    🌡️ Sensor
                                </option>
                                <option value="aktuator" {{ old('jenis_komponen') === 'aktuator' ? 'selected' : '' }}>⚙️
                                    Aktuator
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: Tipe + Protokol --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">TIPE
                                / MODEL</label>
                            <input type="text" name="tipe_komponen" id="f-tipe"
                                value="{{ old('tipe_komponen') }}" placeholder="contoh: DHT22, DS18B20, L298N"
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'" />
                        </div>
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">PROTOKOL</label>
                            <select name="protokol" id="f-protokol"
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;cursor:pointer;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">
                                <option value="">-- Pilih Protokol --</option>
                                @foreach (['I2C', 'OneWire', 'Analog', 'Digital', 'PWM', 'UART'] as $proto)
                                    <option value="{{ $proto }}"
                                        {{ old('protokol') === $proto ? 'selected' : '' }}>{{ $proto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Pin Data --}}
                    <div>
                        <label
                            style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">
                            PIN DATA <span style="font-weight:400;color:#3D4E3D;font-size:10px;margin-left:6px;">Format
                                JSON</span>
                        </label>
                        <textarea name="pin_data" id="f-pin" rows="2" placeholder='{"sda": 21, "scl": 22}  atau  {"pin": 4}'
                            style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:12px;color:#C4D4C4;outline:none;resize:vertical;font-family:monospace;box-sizing:border-box;transition:border-color 0.2s;line-height:1.5;"
                            onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">{{ old('pin_data') }}</textarea>
                        <div style="display:flex;gap:6px;margin-top:6px;flex-wrap:wrap;">
                            @foreach (['i2c' => 'I2C Template', 'onewire' => 'OneWire Template', 'analog' => 'Analog Template', 'relay' => 'Relay Template'] as $k => $label)
                                <button type="button" onclick="setPinTemplate('{{ $k }}')"
                                    style="font-size:10px;color:#4D5E4D;background:#131613;border:1px solid #1E2C1E;border-radius:5px;padding:3px 8px;cursor:pointer;">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Row 3: Satuan + Lokasi --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div id="field-satuan">
                            <label
                                style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">
                                SATUAN <span style="font-weight:400;color:#3D4E3D;font-size:10px;margin-left:4px;">khusus
                                    sensor</span>
                            </label>
                            <input type="text" name="satuan" id="f-satuan" value="{{ old('satuan') }}"
                                placeholder="contoh: °C, %, ppm, pH, lux"
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'" />
                        </div>
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">LOKASI</label>
                            <input type="text" name="lokasi" id="f-lokasi" value="{{ old('lokasi') }}"
                                placeholder="contoh: Tangki Nutrisi A"
                                style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'" />
                        </div>
                    </div>

                    {{-- Batas Nilai --}}
                    <div id="field-batas">
                        <label
                            style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">
                            BATAS NILAI <span style="font-weight:400;color:#3D4E3D;font-size:10px;margin-left:6px;">Format
                                JSON ·
                                khusus sensor</span>
                        </label>
                        <textarea name="batas_nilai" id="f-batas" rows="2"
                            placeholder='{"min": 5.5, "max": 7.0, "optimal_min": 5.8, "optimal_max": 6.5}'
                            style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:12px;color:#C4D4C4;outline:none;resize:vertical;font-family:monospace;box-sizing:border-box;transition:border-color 0.2s;line-height:1.5;"
                            onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">{{ old('batas_nilai') }}</textarea>
                        <div style="display:flex;gap:6px;margin-top:6px;flex-wrap:wrap;">
                            @foreach (['ph' => 'pH', 'tds' => 'TDS', 'suhu' => 'Suhu Air', 'level' => 'Level Air'] as $k => $label)
                                <button type="button" onclick="setBatasTemplate('{{ $k }}')"
                                    style="font-size:10px;color:#4D5E4D;background:#131613;border:1px solid #1E2C1E;border-radius:5px;padding:3px 8px;cursor:pointer;">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label
                            style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">DESKRIPSI</label>
                        <textarea name="deskripsi" id="f-deskripsi" rows="2" placeholder="Keterangan tambahan tentang komponen ini..."
                            style="width:100%;background:#0D0F0D;border:1px solid #252C25;border-radius:10px;padding:10px 14px;font-size:13px;color:#C4D4C4;outline:none;resize:vertical;box-sizing:border-box;transition:border-color 0.2s;line-height:1.5;"
                            onfocus="this.style.borderColor='#16C47F'" onblur="this.style.borderColor='#252C25'">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label
                            style="display:block;font-size:11px;font-weight:700;color:#7A9A7A;margin-bottom:6px;letter-spacing:0.05em;">STATUS</label>
                        <div style="display:flex;gap:10px;">
                            <label id="lbl-active"
                                style="display:flex;align-items:center;gap:8px;cursor:pointer;background:#0D0F0D;border:1px solid {{ old('status', 'active') === 'active' ? '#16C47F55' : '#252C25' }};border-radius:10px;padding:10px 16px;flex:1;transition:border-color 0.2s;">
                                <input type="radio" name="status" id="f-status-active" value="active"
                                    {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                                    onchange="highlightStatus()" style="accent-color:#16C47F;width:15px;height:15px;" />
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:#16C47F;">Aktif</div>
                                    <div style="font-size:10px;color:#4D5E4D;">Komponen siap digunakan</div>
                                </div>
                            </label>
                            <label id="lbl-nonactive"
                                style="display:flex;align-items:center;gap:8px;cursor:pointer;background:#0D0F0D;border:1px solid {{ old('status') === 'non-active' ? '#16C47F55' : '#252C25' }};border-radius:10px;padding:10px 16px;flex:1;transition:border-color 0.2s;">
                                <input type="radio" name="status" id="f-status-nonactive" value="non-active"
                                    {{ old('status') === 'non-active' ? 'checked' : '' }} onchange="highlightStatus()"
                                    style="accent-color:#16C47F;width:15px;height:15px;" />
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:#5A7A5A;">Non-Aktif</div>
                                    <div style="font-size:10px;color:#3D4E3D;">Komponen dinonaktifkan</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div
                        style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #1E2C1E;margin-top:4px;">
                        <button type="button" onclick="closeModal('modal-tambah')"
                            style="background:#1A2A1A;border:1px solid #252C25;border-radius:10px;padding:10px 20px;font-size:13px;color:#5A7A5A;cursor:pointer;font-weight:600;transition:all 0.2s;">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-komponen"
                            style="display:flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16C47F,#12A86B);border:1px solid #16C47F;color:#0D1A0D;font-weight:700;font-size:13px;padding:10px 24px;border-radius:10px;cursor:pointer;box-shadow:0 4px 14px rgba(22,196,127,0.25);transition:all 0.2s;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24">
                                <path
                                    d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2zM17 21v-8H7v8M7 3v5h8"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span id="btn-submit-text">Simpan Komponen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ══════════════════════════════════════════════════════════════
     MODAL — DETAIL
══════════════════════════════════════════════════════════════ --}}
        <div id="modal-detail"
            style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:16px;">
            <div onclick="closeModal('modal-detail')"
                style="position:absolute;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);"></div>
            <div
                style="position:relative;z-index:101;background:#181C18;border:1px solid #2A3A2A;border-radius:18px;width:100%;max-width:520px;max-height:88vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.6);">
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #1E2C1E;position:sticky;top:0;background:#181C18;z-index:10;border-radius:18px 18px 0 0;">
                    <div style="font-size:16px;font-weight:800;color:#E8F0E8;">Detail Komponen</div>
                    <button onclick="closeModal('modal-detail')"
                        style="background:#1A2A1A;border:1px solid #252C25;border-radius:8px;padding:6px;cursor:pointer;color:#5A7A5A;display:flex;align-items:center;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div id="modal-detail-body" style="padding:24px;"></div>
            </div>
        </div>


        {{-- ══════════════════════════════════════════════════════════════
     MODAL — KONFIRMASI HAPUS
══════════════════════════════════════════════════════════════ --}}
        <div id="modal-hapus"
            style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:16px;">
            <div onclick="closeModal('modal-hapus')"
                style="position:absolute;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);"></div>
            <div
                style="position:relative;z-index:101;background:#181C18;border:1px solid #F04A4A33;border-radius:18px;width:100%;max-width:400px;padding:28px;box-shadow:0 25px 60px rgba(0,0,0,0.6);text-align:center;">
                <div
                    style="width:56px;height:56px;background:#F04A4A18;border:1px solid #F04A4A33;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="#F04A4A" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 11v6M14 11v6" stroke="#F04A4A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
                <div style="font-size:17px;font-weight:800;color:#E8F0E8;margin-bottom:8px;">Hapus Komponen?</div>
                <div style="font-size:13px;color:#5A7A5A;margin-bottom:6px;">Anda akan menghapus:</div>
                <div id="hapus-nama"
                    style="font-size:14px;font-weight:700;color:#F04A4A;margin-bottom:16px;padding:8px 16px;background:#F04A4A10;border-radius:8px;">
                    —</div>
                <div style="font-size:12px;color:#4D5E4D;margin-bottom:24px;">
                    Semua data log terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.
                </div>
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
══════════════════════════════════════════════════════════════ --}}
        <div id="toast"
            style="display:none;position:fixed;bottom:24px;right:24px;z-index:200;background:#181C18;border:1px solid #2A3A2A;border-radius:12px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,0.5);max-width:320px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div id="toast-icon"
                    style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;font-weight:700;">
                </div>
                <div>
                    <div id="toast-title" style="font-size:13px;font-weight:700;color:#E8F0E8;"></div>
                    <div id="toast-msg" style="font-size:11px;color:#5A7A5A;margin-top:2px;"></div>
                </div>
            </div>
        </div>

        <style>
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
        </style>


    </main>

@endsection

@section('scripts')
    <script>
        const komponenData = {!! $komponenJson !!};
        const routeUpdate = "{{ url('admin/kelola-sensor') }}/";
        const routeDestroy = "{{ url('admin/kelola-sensor') }}/";


        // ══════════════════════════════════════════════════
        // FILTER & SEARCH (client-side — data sudah ada di DOM)
        // ══════════════════════════════════════════════════
        function filterTable() {
            const q = document.getElementById('search-komponen').value.toLowerCase().trim();
            const jenis = document.getElementById('filter-jenis').value;
            const status = document.getElementById('filter-status').value;
            const protokol = document.getElementById('filter-protokol').value;

            const rows = document.querySelectorAll('.tbl-row');
            const empty = document.getElementById('tbl-empty');
            const info = document.getElementById('tbl-info');
            let visible = 0;

            rows.forEach(tr => {
                const nama = tr.dataset.nama ?? '';
                const tipe = tr.dataset.tipe ?? '';
                const lokasi = tr.dataset.lokasi ?? '';
                const deskripsi = tr.dataset.deskripsi ?? '';

                const matchQ = !q ||
                    nama.includes(q) ||
                    tipe.includes(q) ||
                    lokasi.includes(q) ||
                    deskripsi.includes(q);

                const matchJenis = !jenis || tr.dataset.jenis === jenis;
                const matchStatus = !status || tr.dataset.status === status;
                const matchProtokal = !protokol || tr.dataset.protokol === protokol;

                const show = matchQ && matchJenis && matchStatus && matchProtokal;
                tr.classList.toggle('tbl-row-hidden', !show);
                if (show) visible++;
            });

            empty.style.display = visible === 0 ? 'block' : 'none';
            info.textContent = `Menampilkan ${visible} dari ${rows.length} komponen`;
        }

        function resetFilter() {
            document.getElementById('search-komponen').value = '';
            document.getElementById('filter-jenis').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-protokol').value = '';
            filterTable();
        }

        // ══════════════════════════════════════════════════
        // MODAL HELPERS
        // ══════════════════════════════════════════════════
        function openModal(id) {
            const m = document.getElementById(id);
            m.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
            document.body.style.overflow = '';
            // Reset form ke mode tambah saat ditutup
            if (id === 'modal-tambah') resetFormToAdd();
        }

        // ══════════════════════════════════════════════════
        // FORM HELPERS
        // ══════════════════════════════════════════════════
        function toggleSatuanField(jenis) {
            const satuan = document.getElementById('field-satuan');
            const batas = document.getElementById('field-batas');
            const isAktuator = jenis === 'aktuator';
            satuan.style.opacity = isAktuator ? '0.4' : '1';
            batas.style.opacity = isAktuator ? '0.4' : '1';
        }

        const pinTemplates = {
            i2c: '{"sda": 21, "scl": 22}',
            onewire: '{"pin": 4}',
            analog: '{"pin": 34}',
            relay: '{"pin": 26, "active_low": false}'
        };

        function setPinTemplate(type) {
            document.getElementById('f-pin').value = pinTemplates[type] ?? '';
        }

        const batasTemplates = {
            ph: '{"min": 4.0, "max": 9.0, "optimal_min": 5.5, "optimal_max": 7.0}',
            tds: '{"min": 400, "max": 2500, "optimal_min": 800, "optimal_max": 1600}',
            suhu: '{"min": 15, "max": 35, "optimal_min": 20, "optimal_max": 28}',
            level: '{"min": 20, "max": 100, "optimal_min": 35, "optimal_max": 90}'
        };

        function setBatasTemplate(type) {
            document.getElementById('f-batas').value = batasTemplates[type] ?? '';
        }

        function highlightStatus() {
            document.getElementById('lbl-active').style.borderColor =
                document.getElementById('f-status-active').checked ? '#16C47F55' : '#252C25';
            document.getElementById('lbl-nonactive').style.borderColor =
                document.getElementById('f-status-nonactive').checked ? '#16C47F55' : '#252C25';
        }

        // ══════════════════════════════════════════════════
        // RESET FORM ke MODE TAMBAH
        // ══════════════════════════════════════════════════
        function resetFormToAdd() {
            const form = document.getElementById('form-komponen');
            form.reset();
            form.action = "{{ route('kelola-sensor.store') }}";
            document.getElementById('form-method').value = 'POST';
            document.getElementById('modal-tambah-title').textContent = 'Tambah Komponen Baru';
            document.getElementById('btn-submit-text').textContent = 'Simpan Komponen';
            document.getElementById('f-status-active').checked = true;
            highlightStatus();
            toggleSatuanField('');
        }

        // ══════════════════════════════════════════════════
        // SHOW DETAIL
        // ══════════════════════════════════════════════════
        function showDetail(id) {
            const row = komponenData.find(r => r.id === id);
            if (!row) return;

            const isSensor = row.jenis_komponen === 'sensor';
            const accentBg = isSensor ? '#16C47F0A' : '#4A8AF00A';
            const accentBorder = isSensor ? '#16C47F22' : '#4A8AF022';

            const fields = [
                ['Jenis', isSensor ? '🌡️ Sensor' : '⚙️ Aktuator'],
                ['Tipe/Model', row.tipe_komponen ?? '—'],
                ['Protokol', row.protokol ?? '—'],
                ['Lokasi', row.lokasi ?? '—'],
                ['Satuan', row.satuan ?? '—'],
                ['Pin Data', row.pin_data ?? '—'],
                ['Batas Nilai', row.batas_nilai ?? '—'],
                ['Deskripsi', row.deskripsi ?? '—'],
                ['Status', row.status === 'active' ? '✅ Aktif' : '⭕ Non-Aktif'],
            ];

            document.getElementById('modal-detail-body').innerHTML = `
    <div style="margin-bottom:16px;padding:14px;background:${accentBg};border-radius:10px;border:1px solid ${accentBorder};">
      <div style="font-size:17px;font-weight:800;color:#E8F0E8;">${row.nama_komponen}</div>
      <div style="font-size:12px;color:#5A7A5A;margin-top:4px;">
        ${row.tipe_komponen ?? ''} ${row.protokol ? '· '+row.protokol : ''} ${row.lokasi ? '· '+row.lokasi : ''}
      </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:0;">
      ${fields.map(([k,v]) => `
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid #131613;">
                      <span style="font-size:11px;font-weight:700;color:#3D4E3D;letter-spacing:0.05em;white-space:nowrap;min-width:100px;">
                        ${k.toUpperCase()}
                      </span>
                      <span style="font-size:12px;color:#A8C4A8;text-align:right;font-family:${['Pin Data','Batas Nilai'].includes(k)?'monospace':'inherit'};word-break:break-all;">
                        ${v}
                      </span>
                    </div>
                  `).join('')}
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;">
      <button onclick="closeModal('modal-detail');editKomponen(${row.id})"
              style="flex:1;background:#F0A84A18;border:1px solid #F0A84A33;border-radius:10px;padding:10px;font-size:12px;color:#F0A84A;cursor:pointer;font-weight:700;">
        Edit
      </button>
      <button onclick="closeModal('modal-detail');hapusKomponen(${row.id},'${(row.nama_komponen).replace(/'/g,"\\'")}');"
              style="flex:1;background:#F04A4A10;border:1px solid #F04A4A22;border-radius:10px;padding:10px;font-size:12px;color:#F04A4A;cursor:pointer;font-weight:700;">
        Hapus
      </button>
    </div>
  `;
            openModal('modal-detail');
        }

        // ══════════════════════════════════════════════════
        // EDIT — Pre-fill form lalu ubah action ke PUT
        // ══════════════════════════════════════════════════
        function editKomponen(id) {
            const row = komponenData.find(r => r.id === id);
            if (!row) return;

            // Set nilai form
            document.getElementById('f-nama').value = row.nama_komponen ?? '';
            document.getElementById('f-jenis').value = row.jenis_komponen ?? '';
            document.getElementById('f-tipe').value = row.tipe_komponen ?? '';
            document.getElementById('f-pin').value = row.pin_data ?? '';
            document.getElementById('f-satuan').value = row.satuan ?? '';
            document.getElementById('f-batas').value = row.batas_nilai ?? '';
            document.getElementById('f-lokasi').value = row.lokasi ?? '';
            document.getElementById('f-protokol').value = row.protokol ?? '';
            document.getElementById('f-deskripsi').value = row.deskripsi ?? '';

            // Radio status
            document.getElementById('f-status-active').checked = row.status === 'active';
            document.getElementById('f-status-nonactive').checked = row.status === 'non-active';
            highlightStatus();

            // Opacity satuan/batas sesuai jenis
            toggleSatuanField(row.jenis_komponen ?? '');

            // Ubah form action & method → PUT (update)
            const form = document.getElementById('form-komponen');
            form.action = routeUpdate + row.id;
            document.getElementById('form-method').value = 'PUT';

            // Ubah judul & tombol
            document.getElementById('modal-tambah-title').textContent = 'Edit Komponen';
            document.getElementById('btn-submit-text').textContent = 'Perbarui Komponen';

            openModal('modal-tambah');
        }

        // ══════════════════════════════════════════════════
        // HAPUS — Set action form hapus
        // ══════════════════════════════════════════════════
        function hapusKomponen(id, nama) {
            document.getElementById('hapus-nama').textContent = nama;
            document.getElementById('form-hapus').action = routeDestroy + id;
            openModal('modal-hapus');
        }

        // ══════════════════════════════════════════════════
        // TOAST — dari session PHP
        // ══════════════════════════════════════════════════
        let toastTimer = null;

        function showToast(type, title, msg) {
            const toast = document.getElementById('toast');
            const iconWrap = document.getElementById('toast-icon');
            const titleEl = document.getElementById('toast-title');
            const msgEl = document.getElementById('toast-msg');

            const cfg = {
                success: {
                    bg: '#16C47F18',
                    border: '#16C47F',
                    icon: '✓',
                    color: '#16C47F'
                },
                info: {
                    bg: '#4A8AF018',
                    border: '#4A8AF0',
                    icon: 'ℹ',
                    color: '#4A8AF0'
                },
                danger: {
                    bg: '#F04A4A18',
                    border: '#F04A4A',
                    icon: '✕',
                    color: '#F04A4A'
                },
            };
            const c = cfg[type] ?? cfg.info;

            toast.style.borderColor = c.border + '55';
            toast.style.animation = 'none';
            toast.offsetHeight; // reflow
            toast.style.animation = 'slideUp 0.3s ease';
            iconWrap.style.background = c.bg;
            iconWrap.style.border = `1px solid ${c.border}44`;
            iconWrap.style.color = c.color;
            iconWrap.textContent = c.icon;
            titleEl.textContent = title;
            msgEl.textContent = msg;

            toast.style.display = 'block';
            if (toastTimer) clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.style.display = 'none';
            }, 4000);
        }

        // Auto-show toast dari session PHP
        document.addEventListener('DOMContentLoaded', () => {
            const phpToast = document.getElementById('php-toast');
            if (phpToast) {
                const type = phpToast.dataset.type;
                const msg = phpToast.dataset.msg;
                const titles = {
                    success: 'Berhasil',
                    info: 'Info',
                    danger: 'Dihapus'
                };
                showToast(type, titles[type] ?? 'Info', msg);
            }

            // Jika ada error validasi, buka modal langsung
            @if ($errors->any())
                openModal('modal-tambah');
            @endif

            // Jika ada old input → kemungkinan edit gagal, buka modal edit
            @if (old('nama_komponen') && !$errors->any())
                // tidak ada aksi khusus
            @endif
        });
    </script>
@endsection
