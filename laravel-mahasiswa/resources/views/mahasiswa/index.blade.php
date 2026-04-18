<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Mahasiswa — Sistem Informasi Akademik</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           CSS VARIABLES & RESET
        ============================================================ */
        :root {
            --bg:        #0a0a0f;
            --surface:   #111118;
            --card:      #16161f;
            --border:    #252535;
            --accent:    #5b6af0;
            --accent2:   #e8614d;
            --accent3:   #43c98e;
            --text:      #e8e8f0;
            --muted:     #7070a0;
            --glow:      rgba(91, 106, 240, 0.35);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============================================================
           BACKGROUND GRID
        ============================================================ */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: 0.35;
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse at center, rgba(91,106,240,0.18) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ============================================================
           LAYOUT
        ============================================================ */
        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 24px 80px;
        }

        /* ============================================================
           HEADER
        ============================================================ */
        .header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 56px;
            animation: fadeUp 0.7s ease both;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(91,106,240,0.12);
            border: 1px solid rgba(91,106,240,0.3);
            color: #8b98f5;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 5px 13px;
            border-radius: 100px;
            margin-bottom: 20px;
        }

        .header-badge::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--accent);
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 30%, #8b98f5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .header-sub {
            color: var(--muted);
            font-size: 16px;
            font-weight: 300;
            max-width: 480px;
        }

        /* ============================================================
           CONTROLS PANEL
        ============================================================ */
        .controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 32px;
            animation: fadeUp 0.7s 0.15s ease both;
        }

        /* ============================================================
           BUTTON
        ============================================================ */
        .btn-fetch {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--accent);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            cursor: pointer;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 0 24px rgba(91,106,240,0.4);
        }

        .btn-fetch::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            border-radius: inherit;
        }

        .btn-fetch:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 32px rgba(91,106,240,0.6);
        }

        .btn-fetch:active { transform: translateY(0); }

        .btn-fetch:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-icon {
            width: 18px; height: 18px;
            transition: transform 0.4s ease;
        }

        .btn-fetch:hover .btn-icon { transform: rotate(180deg); }

        /* Spinner state */
        .btn-fetch.loading .btn-icon { animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Counter badge */
        .counter-badge {
            display: none;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .counter-badge.visible { display: flex; }

        .counter-num {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--accent);
        }

        /* ============================================================
           RESULT AREA
        ============================================================ */
        #result-area {
            min-height: 180px;
            animation: fadeUp 0.7s 0.25s ease both;
        }

        /* Empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 220px;
            border: 1px dashed var(--border);
            border-radius: 16px;
            gap: 12px;
            color: var(--muted);
        }

        .empty-icon {
            width: 48px; height: 48px;
            opacity: 0.3;
        }

        .empty-state p { font-size: 14px; }

        /* Error state */
        .error-state {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(232,97,77,0.1);
            border: 1px solid rgba(232,97,77,0.3);
            color: #f08070;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 14px;
        }

        /* ============================================================
           CARDS GRID
        ============================================================ */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
            opacity: 0;
            transform: translateY(20px);
            animation: cardIn 0.45s ease forwards;
        }

        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(91,106,240,0.5);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(91,106,240,0.15);
        }

        /* Top accent bar */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), #a78bfa);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .card:hover::before { opacity: 1; }

        /* Card header */
        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .card-avatar {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent) 0%, #a78bfa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
        }

        .card-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(67,201,142,0.12);
            border: 1px solid rgba(67,201,142,0.25);
            color: var(--accent3);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 100px;
        }

        .card-status::before {
            content: '';
            width: 5px; height: 5px;
            background: var(--accent3);
            border-radius: 50%;
        }

        /* Card nama */
        .card-nama {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .card-nim {
            font-size: 12px;
            color: var(--muted);
            font-family: 'DM Mono', 'Courier New', monospace;
            letter-spacing: 0.06em;
            margin-bottom: 18px;
        }

        /* Card meta */
        .card-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-top: 1px solid var(--border);
            padding-top: 16px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .meta-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--muted);
            font-weight: 500;
        }

        .meta-value {
            font-size: 13px;
            color: var(--text);
            font-weight: 400;
            text-align: right;
        }

        .meta-value.kelas-badge {
            background: rgba(91,106,240,0.15);
            color: #8b98f5;
            padding: 2px 8px;
            border-radius: 5px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
        }

        /* ============================================================
           TABLE (alternative view, hidden by default)
        ============================================================ */
        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        th {
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            padding: 14px 18px;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 14px 18px;
            font-size: 13px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr {
            transition: background 0.15s;
        }

        tbody tr:hover {
            background: rgba(91,106,240,0.05);
        }

        .td-nim {
            font-family: 'DM Mono', 'Courier New', monospace;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.05em;
        }

        .td-kelas {
            display: inline-block;
            background: rgba(91,106,240,0.15);
            color: #8b98f5;
            padding: 2px 9px;
            border-radius: 5px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
        }

        .td-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--accent3);
            font-size: 12px;
        }

        .td-status::before {
            content: '';
            width: 5px; height: 5px;
            background: var(--accent3);
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ============================================================
           VIEW TOGGLE
        ============================================================ */
        .view-toggle {
            display: none;
            gap: 4px;
        }

        .view-toggle.visible { display: flex; }

        .toggle-btn {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 8px 10px;
            border-radius: 7px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: color 0.15s, border-color 0.15s, background 0.15s;
        }

        .toggle-btn.active {
            background: rgba(91,106,240,0.15);
            border-color: rgba(91,106,240,0.4);
            color: var(--accent);
        }

        .toggle-btn svg { width: 16px; height: 16px; }

        /* ============================================================
           FOOTER
        ============================================================ */
        .footer {
            margin-top: 64px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            color: var(--muted);
            font-size: 12px;
        }

        .footer strong { color: #7070a0; font-weight: 500; }

        /* ============================================================
           ANIMATIONS
        ============================================================ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 640px) {
            .wrapper { padding: 36px 16px 60px; }
            .cards-grid { grid-template-columns: 1fr; }
            .controls { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    {{-- ── HEADER ──────────────────────────────────────── --}}
    <header class="header">
        <span class="header-badge">Sistem Akademik</span>
        <h1>Data<br>Mahasiswa</h1>
        <p class="header-sub">Kelola dan tampilkan informasi mahasiswa aktif secara real-time tanpa reload halaman.</p>
    </header>

    {{-- ── CONTROLS ─────────────────────────────────────── --}}
    <div class="controls">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">

            {{-- Tombol Tampilkan Data --}}
            <button class="btn-fetch" id="btnFetch" onclick="fetchMahasiswa()">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                </svg>
                Tampilkan Data
            </button>

            {{-- Counter --}}
            <div class="counter-badge" id="counterBadge">
                <span class="counter-num" id="counterNum">0</span>
                <span>mahasiswa ditemukan</span>
            </div>
        </div>

        {{-- View toggle (cards / table) --}}
        <div class="view-toggle" id="viewToggle">
            <button class="toggle-btn active" id="btnCards" onclick="setView('cards')" title="Tampilan Kartu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            </button>
            <button class="toggle-btn" id="btnTable" onclick="setView('table')" title="Tampilan Tabel">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3h18v18H3z"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ── RESULT AREA ──────────────────────────────────── --}}
    <div id="result-area">
        {{-- Empty state awal --}}
        <div class="empty-state" id="emptyState">
            <svg class="empty-icon" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="8" y="12" width="48" height="40" rx="4"/>
                <path d="M8 24h48M20 12v12M44 12v12M20 40h24"/>
            </svg>
            <p>Klik tombol <strong>Tampilkan Data</strong> untuk memuat data mahasiswa.</p>
        </div>
    </div>

    {{-- ── FOOTER ───────────────────────────────────────── --}}
    <footer class="footer">
        <span>Sistem Informasi Akademik &mdash; <strong>Laravel + AJAX</strong></span>
        <span>Data bersumber dari file JSON lokal &middot; Tanpa database</span>
    </footer>

</div>

{{-- ============================================================
     JAVASCRIPT — AJAX + RENDER
============================================================ --}}
<script>
    // State
    let cachedData = null;
    let currentView = 'cards'; // 'cards' | 'table'

    /**
     * Ambil data via AJAX
     */
    function fetchMahasiswa() {
        const btn     = document.getElementById('btnFetch');
        const area    = document.getElementById('result-area');
        const empty   = document.getElementById('emptyState');
        const toggle  = document.getElementById('viewToggle');
        const counter = document.getElementById('counterBadge');

        // Loading state
        btn.classList.add('loading');
        btn.disabled = true;
        btn.querySelector('span') && (btn.querySelector('span').textContent = 'Memuat...');

        // Sembunyikan empty state
        if (empty) empty.style.display = 'none';

        // Tampilkan skeleton sementara
        area.innerHTML = `
            <div id="emptyState" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:180px;gap:14px;color:#7070a0;">
                <svg style="width:32px;height:32px;animation:spin 0.8s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                <span style="font-size:14px">Mengambil data dari server…</span>
            </div>
        `;

        // Buat AJAX Request dengan XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/api/mahasiswa', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== XMLHttpRequest.DONE) return;

            // Kembalikan button
            btn.classList.remove('loading');
            btn.disabled = false;

            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const res = JSON.parse(xhr.responseText);

                    if (res.success) {
                        cachedData = res.data;

                        // Update counter
                        document.getElementById('counterNum').textContent = res.total;
                        counter.classList.add('visible');
                        toggle.classList.add('visible');

                        // Render sesuai view aktif
                        renderData(cachedData, currentView);
                    } else {
                        renderError(res.message || 'Gagal mengambil data.');
                    }
                } catch (e) {
                    renderError('Respons tidak valid dari server.');
                }
            } else {
                renderError(`Error ${xhr.status}: Gagal terhubung ke server.`);
            }
        };

        xhr.onerror = function () {
            btn.classList.remove('loading');
            btn.disabled = false;
            renderError('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
        };

        xhr.send();
    }

    /**
     * Render data ke tampilan yang dipilih
     */
    function renderData(data, view) {
        const area = document.getElementById('result-area');

        if (view === 'cards') {
            renderCards(data, area);
        } else {
            renderTable(data, area);
        }
    }

    /**
     * Render tampilan Cards
     */
    function renderCards(data, area) {
        let html = '<div class="cards-grid">';

        data.forEach(function (mhs, i) {
            const initials = mhs.nama
                .split(' ')
                .slice(0, 2)
                .map(function (w) { return w[0]; })
                .join('');

            // Gradien warna avatar berdasarkan index
            const colors = [
                'linear-gradient(135deg,#5b6af0,#a78bfa)',
                'linear-gradient(135deg,#e8614d,#f59e0b)',
                'linear-gradient(135deg,#43c98e,#06b6d4)',
                'linear-gradient(135deg,#ec4899,#8b5cf6)',
                'linear-gradient(135deg,#f59e0b,#ef4444)',
                'linear-gradient(135deg,#06b6d4,#5b6af0)',
            ];
            const avatarGrad = colors[i % colors.length];

            html += `
                <div class="card" style="animation-delay:${i * 0.08}s">
                    <div class="card-header">
                        <div class="card-avatar" style="background:${avatarGrad}">${initials}</div>
                        <span class="card-status">${mhs.status || 'Aktif'}</span>
                    </div>
                    <div class="card-nama">${mhs.nama}</div>
                    <div class="card-nim">NIM&nbsp;&nbsp;${mhs.nim}</div>
                    <div class="card-meta">
                        <div class="meta-item">
                            <span class="meta-label">Kelas</span>
                            <span class="meta-value kelas-badge">${mhs.kelas}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Program Studi</span>
                            <span class="meta-value">${mhs.prodi}</span>
                        </div>
                        ${mhs.angkatan ? `
                        <div class="meta-item">
                            <span class="meta-label">Angkatan</span>
                            <span class="meta-value">${mhs.angkatan}</span>
                        </div>` : ''}
                    </div>
                </div>
            `;
        });

        html += '</div>';
        area.innerHTML = html;
    }

    /**
     * Render tampilan Table
     */
    function renderTable(data, area) {
        let html = `
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Kelas</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        data.forEach(function (mhs, i) {
            html += `
                <tr style="animation:cardIn 0.35s ${i * 0.06}s ease both;opacity:0">
                    <td style="color:var(--muted);font-size:12px">${i + 1}</td>
                    <td style="font-weight:500">${mhs.nama}</td>
                    <td class="td-nim">${mhs.nim}</td>
                    <td><span class="td-kelas">${mhs.kelas}</span></td>
                    <td>${mhs.prodi}</td>
                    <td style="color:var(--muted)">${mhs.angkatan || '—'}</td>
                    <td><span class="td-status">${mhs.status || 'Aktif'}</span></td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;
        area.innerHTML = html;
    }

    /**
     * Render pesan error
     */
    function renderError(msg) {
        document.getElementById('result-area').innerHTML = `
            <div class="error-state">
                <svg style="width:20px;height:20px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>${msg}</span>
            </div>
        `;
    }

    /**
     * Toggle tampilan Cards / Table
     */
    function setView(view) {
        if (!cachedData) return;
        currentView = view;

        document.getElementById('btnCards').classList.toggle('active', view === 'cards');
        document.getElementById('btnTable').classList.toggle('active', view === 'table');

        renderData(cachedData, view);
    }
</script>

</body>
</html>