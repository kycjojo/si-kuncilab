<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Peminjaman Kunci Laboratorium Sekolah">
    <title><?php echo $__env->yieldContent('title', 'SiKunci Lab'); ?> — Sistem Peminjaman Lab</title>

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
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
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
            <?php if(auth()->user()->role === 'admin'): ?> bg-danger
            <?php elseif(auth()->user()->role === 'pengawas'): ?> bg-warning text-dark
            <?php else: ?> bg-success
            <?php endif; ?>">
            <?php echo e(ucfirst(auth()->user()->role)); ?>

        </span>
    </div>

    <nav class="sidebar-nav">
        <?php if(auth()->user()->role === 'admin'): ?>
            
            <div class="nav-section-title">Utama</div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section-title">Manajemen</div>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                <i class="bi bi-people-fill"></i> Kelola User
            </a>
            <a href="<?php echo e(route('admin.labs.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.labs.*') ? 'active' : ''); ?>">
                <i class="bi bi-building"></i> Kelola Lab
            </a>

            <div class="nav-section-title">Laporan</div>
            <a href="<?php echo e(route('admin.laporan.peminjaman')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.laporan.peminjaman') ? 'active' : ''); ?>">
                <i class="bi bi-clipboard-data-fill"></i> Lap. Peminjaman
            </a>
            <a href="<?php echo e(route('admin.laporan.keterlambatan')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.laporan.keterlambatan') ? 'active' : ''); ?>">
                <i class="bi bi-clock-history"></i> Lap. Keterlambatan
            </a>
            <a href="<?php echo e(route('admin.laporan.pelanggaran')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.laporan.pelanggaran') ? 'active' : ''); ?>">
                <i class="bi bi-exclamation-triangle-fill"></i> Lap. Pelanggaran
            </a>

        <?php elseif(auth()->user()->role === 'pengawas'): ?>
            
            <div class="nav-section-title">Utama</div>
            <a href="<?php echo e(route('pengawas.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('pengawas.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section-title">Kunci Lab</div>
            <a href="<?php echo e(route('pengawas.kunci.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pengawas.kunci.*') ? 'active' : ''); ?>">
                <i class="bi bi-key-fill"></i> Kelola Kunci
            </a>

            <div class="nav-section-title">Pelanggaran</div>
            <a href="<?php echo e(route('pengawas.pelanggaran.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pengawas.pelanggaran.index') ? 'active' : ''); ?>">
                <i class="bi bi-exclamation-triangle-fill"></i> Daftar Pelanggaran
            </a>
            <a href="<?php echo e(route('pengawas.pelanggaran.create')); ?>" class="nav-link <?php echo e(request()->routeIs('pengawas.pelanggaran.create') ? 'active' : ''); ?>">
                <i class="bi bi-plus-circle-fill"></i> Catat Pelanggaran
            </a>

        <?php else: ?>
            
            <div class="nav-section-title">Utama</div>
            <a href="<?php echo e(route('murid.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('murid.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section-title">Peminjaman</div>
            <a href="<?php echo e(route('murid.peminjaman.create')); ?>" class="nav-link <?php echo e(request()->routeIs('murid.peminjaman.create') ? 'active' : ''); ?>">
                <i class="bi bi-plus-circle-fill"></i> Ajukan Peminjaman
            </a>
            <a href="<?php echo e(route('murid.peminjaman.index')); ?>" class="nav-link <?php echo e(request()->routeIs('murid.peminjaman.*') ? 'active' : ''); ?>">
                <i class="bi bi-clock-history"></i> Riwayat Peminjaman
            </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info mb-2">
            <div class="user-name"><?php echo e(auth()->user()->name); ?></div>
            <div><?php echo e(auth()->user()->email); ?></div>
            <?php if(auth()->user()->isMurid()): ?>
                <div class="mt-1">
                    <span class="badge <?php echo e(auth()->user()->poin_pelanggaran >= 4 ? 'bg-danger' : 'bg-secondary'); ?>">
                        <i class="bi bi-exclamation-circle"></i>
                        Poin: <?php echo e(auth()->user()->poin_pelanggaran); ?>/5
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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
        <button class="btn btn-sm btn-light sidebar-toggle" onclick="toggleSidebar()" id="sidebar-toggle-btn">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-inline">
                <i class="bi bi-calendar3"></i>
                <?php echo e(now()->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

            </span>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        
        <?php if(session('success')): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-x-circle-fill"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('info')): ?>
            <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-info-circle-fill"></i>
                <?php echo e(session('info')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
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
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\jojo\Desktop\lab-key-system\resources\views/layouts/app.blade.php ENDPATH**/ ?>