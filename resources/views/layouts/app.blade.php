<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Peminjaman Kunci Laboratorium Sekolah">
    <title>@yield('title', 'SiKunci Lab') — Sistem Peminjaman Lab</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --primary-light: #818CF8;
            --secondary: #06B6D4;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --sidebar-width: 260px;
            --sidebar-bg: #1E1B4B;
            --sidebar-text: #C7D2FE;
            --sidebar-active: #4F46E5;
            --topbar-height: 64px;
            --body-bg: #F1F5F9;
            --card-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: #1E293B;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
        }
        .sidebar-brand .badge-role {
            font-size: .65rem;
            padding: 3px 8px;
            border-radius: 20px;
            margin-top: 4px;
            display: inline-block;
        }

        .sidebar-nav { flex: 1; padding: 12px 0; }
        .nav-section-title {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            padding: 12px 24px 4px;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            border-radius: 0;
            transition: all .2s;
            position: relative;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }
        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--primary-light);
            border-radius: 0 2px 2px 0;
        }
        .sidebar .nav-link i { font-size: 1.1rem; width: 20px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer .user-info { color: rgba(255,255,255,.8); font-size: .8rem; }
        .sidebar-footer .user-name { font-weight: 600; color: #fff; }

        /* ── Main Content ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 999;
            gap: 16px;
        }
        .topbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1E293B;
            flex: 1;
        }

        /* ── Page Content ── */
        .page-content { padding: 24px; flex: 1; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #F1F5F9;
            padding: 16px 20px;
            font-weight: 600;
            font-size: .9rem;
        }

        /* ── Stat Cards ── */
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px; top: -20px;
            width: 100px; height: 100px;
            background: rgba(255,255,255,.1);
            border-radius: 50%;
        }
        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: .8;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: .8rem;
            opacity: .85;
        }

        /* ── Badge overrides ── */
        .badge { font-weight: 500; }

        /* ── Tables ── */
        .table { font-size: .875rem; }
        .table th {
            font-weight: 600;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748B;
            border-top: none;
        }

        /* ── Buttons ── */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* ── Alert flash ── */
        .alert { border: none; border-radius: 10px; font-size: .875rem; }

        /* ── Mobile sidebar toggle ── */
        .sidebar-toggle { display: none; }
        .print-only { display: none !important; }

        /* ── Topbar back button ── */
        .topbar-back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            border-radius: 8px;
            color: #64748B;
            font-size: 1.5rem;
            cursor: pointer;
            transition: background .15s, color .15s, transform .15s;
            padding: 0;
            text-decoration: none;
            flex-shrink: 0;
        }
        .topbar-back-btn:hover {
            background: #F1F5F9;
            color: var(--primary);
        }
        .topbar-back-btn:active {
            background: #E2E8F0;
            transform: scale(.93);
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.5);
                z-index: 999;
            }
            .sidebar-overlay.show { display: block; }
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            html,
            body {
                background: #fff !important;
                color: #111827 !important;
                font-size: 11px;
            }

            .sidebar,
            .sidebar-overlay,
            .topbar,
            .no-print,
            .btn,
            form,
            .card-footer,
            .pagination,
            .print-hidden {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .main-wrapper {
                display: block !important;
                margin-left: 0 !important;
                min-height: auto !important;
            }

            .page-content {
                padding: 0 !important;
            }

            .card {
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                break-inside: auto;
            }

            .card-header {
                background: #fff !important;
                border-bottom: 1px solid #111827 !important;
                color: #111827 !important;
                padding: 0 0 8px !important;
                margin-bottom: 8px;
            }

            .card-body {
                padding: 0 !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                border-collapse: collapse !important;
                width: 100% !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .table th,
            .table td {
                background: #fff !important;
                border: 1px solid #cbd5e1 !important;
                color: #111827 !important;
                padding: 4px 6px !important;
            }

            .badge {
                background: #fff !important;
                border: 1px solid #111827 !important;
                color: #111827 !important;
            }

            .print-report-heading {
                border-bottom: 2px solid #111827;
                margin-bottom: 12px;
                padding-bottom: 10px;
            }

            .print-report-heading h1 {
                font-size: 18px;
                margin: 0 0 4px;
                text-transform: uppercase;
            }

            .print-report-heading p {
                margin: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ═══ SIDEBAR ═══ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-key-fill text-warning fs-4"></i>
            <h5 class="mb-0">SiKunci Lab</h5>
        </div>
        <span class="badge-role
            @if(auth()->user()->role === 'admin') bg-danger
            @elseif(auth()->user()->role === 'pengawas') bg-warning text-dark
            @else bg-success
            @endif">
            {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>

    <nav class="sidebar-nav">
        @if(auth()->user()->role === 'admin')
            {{-- ADMIN MENU --}}
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section-title">Manajemen</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Kelola User
            </a>
            <a href="{{ route('admin.labs.index') }}" class="nav-link {{ request()->routeIs('admin.labs.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Kelola Lab
            </a>

            <div class="nav-section-title">Laporan</div>
            <a href="{{ route('admin.laporan.peminjaman') }}" class="nav-link {{ request()->routeIs('admin.laporan.peminjaman') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data-fill"></i> Lap. Peminjaman
            </a>
            <a href="{{ route('admin.laporan.keterlambatan') }}" class="nav-link {{ request()->routeIs('admin.laporan.keterlambatan') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Lap. Keterlambatan
            </a>
            <a href="{{ route('admin.laporan.pelanggaran') }}" class="nav-link {{ request()->routeIs('admin.laporan.pelanggaran') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Lap. Pelanggaran
            </a>

        @elseif(auth()->user()->role === 'pengawas')
            {{-- PENGAWAS MENU --}}
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('pengawas.dashboard') }}" class="nav-link {{ request()->routeIs('pengawas.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section-title">Kunci Lab</div>
            <a href="{{ route('pengawas.kunci.index') }}" class="nav-link {{ request()->routeIs('pengawas.kunci.*') ? 'active' : '' }}">
                <i class="bi bi-key-fill"></i> Kelola Kunci
            </a>

            <div class="nav-section-title">Pelanggaran</div>
            <a href="{{ route('pengawas.pelanggaran.index') }}" class="nav-link {{ request()->routeIs('pengawas.pelanggaran.index') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Daftar Pelanggaran
            </a>
            <a href="{{ route('pengawas.pelanggaran.create') }}" class="nav-link {{ request()->routeIs('pengawas.pelanggaran.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i> Catat Pelanggaran
            </a>

        @else
            {{-- MURID MENU --}}
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('murid.dashboard') }}" class="nav-link {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section-title">Peminjaman</div>
            <a href="{{ route('murid.peminjaman.create') }}" class="nav-link {{ request()->routeIs('murid.peminjaman.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i> Ajukan Peminjaman
            </a>
            <a href="{{ route('murid.peminjaman.index') }}" class="nav-link {{ request()->routeIs('murid.peminjaman.index') || request()->routeIs('murid.peminjaman.show') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Peminjaman
            </a>
            <a href="{{ route('murid.pass-kunci.scan') }}" class="nav-link {{ request()->routeIs('murid.pass-kunci.scan') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Ambil Kunci
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info mb-2">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div>{{ auth()->user()->email }}</div>
            @if(auth()->user()->isMurid())
                <div class="mt-1">
                    <span class="badge {{ auth()->user()->poin_pelanggaran >= 4 ? 'bg-danger' : 'bg-secondary' }}">
                        <i class="bi bi-exclamation-circle"></i>
                        Poin: {{ auth()->user()->poin_pelanggaran }}/5
                    </span>
                </div>
            @endif
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100" id="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- ═══ MAIN WRAPPER ═══ -->
<div class="main-wrapper">

    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-1">
            <button class="btn btn-sm btn-light sidebar-toggle" onclick="toggleSidebar()" id="sidebar-toggle-btn">
                <i class="bi bi-list fs-5"></i>
            </button>
            @hasSection('back-url')
            <a href="@yield('back-url')" class="topbar-back-btn" title="Kembali" aria-label="Kembali">
                <i class="bi bi-arrow-left-short"></i>
            </a>
            @endif
        </div>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-inline">
                <i class="bi bi-calendar3"></i>
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 no-print" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 no-print" role="alert">
                <i class="bi bi-x-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info d-flex align-items-center gap-2 mb-4 no-print" role="alert">
                <i class="bi bi-info-circle-fill"></i>
                {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // ── Back button: handled server-side via @section('back-url') ──

    (function () {
        const page = document.querySelector('[data-realtime-page]');
        if (!page) return;

        const interval = Number(page.dataset.realtimeInterval || 5000);
        let inFlight = false;

        function isUserInteracting() {
            if (document.hidden || document.querySelector('.modal.show')) return true;

            const active = document.activeElement;
            if (!active || active === document.body) return false;

            return ['INPUT', 'TEXTAREA', 'SELECT'].includes(active.tagName)
                && Boolean(active.closest('[data-realtime-page]'));
        }

        async function refreshRealtimeRegions() {
            if (inFlight || isUserInteracting()) return;

            inFlight = true;
            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-SiKunci-Realtime': '1',
                    },
                    credentials: 'same-origin',
                    cache: 'no-store',
                });

                const contentType = response.headers.get('content-type') || '';
                if (!response.ok || !contentType.includes('text/html')) return; // finally will still reset inFlight

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const changedRegions = [];

                doc.querySelectorAll('[data-realtime-region]').forEach((incoming) => {
                    const key = incoming.dataset.realtimeRegion;
                    const current = document.querySelector(`[data-realtime-region="${key}"]`);

                    if (!current) return;

                    const currentHtml = current.innerHTML.trim();
                    const incomingHtml = incoming.innerHTML.trim();

                    if (currentHtml !== incomingHtml) {
                        current.replaceWith(document.importNode(incoming, true));
                        changedRegions.push(key);
                    }
                });

                if (changedRegions.length > 0) {
                    window.dispatchEvent(new CustomEvent('sikunci:content-refreshed', {
                        detail: { regions: changedRegions },
                    }));
                }
            } catch (error) {
                // Realtime refresh is optional; the next poll can try again.
            } finally {
                inFlight = false;
            }
        }

        window.addEventListener('sikunci:refresh-now', refreshRealtimeRegions);
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) refreshRealtimeRegions();
        });
        setInterval(refreshRealtimeRegions, interval);
    })();
</script>
@stack('scripts')
</body>
</html>
