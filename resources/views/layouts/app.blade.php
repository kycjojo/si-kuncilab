<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Peminjaman Kunci Laboratorium Sekolah">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiKunci Lab') — Sistem Peminjaman Lab</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- SiKunci Premium CSS -->
    <link href="{{ asset('css/sikunci.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

<!-- SPA Loading Bar -->
<div class="spa-progress-bar" id="spaProgressBar"></div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

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
        <form action="{{ route('logout') }}" method="POST" data-no-spa="true">
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
    <header class="topbar" id="topbar">
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
        <div class="topbar-title" id="topbarTitle">@yield('page-title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-inline">
                <i class="bi bi-calendar3"></i>
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        <div id="spa-content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4 no-print flash-msg" role="alert" data-flash="success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 no-print flash-msg" role="alert" data-flash="error">
                    <i class="bi bi-x-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info d-flex align-items-center gap-2 mb-4 no-print flash-msg" role="alert" data-flash="info">
                    <i class="bi bi-info-circle-fill"></i>
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ═══════════════════════════════════════════════════════
   SIDEBAR TOGGLE
   ═══════════════════════════════════════════════════════ */
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show');
    document.getElementById('sidebarOverlay').classList.remove('show');
}

/* ═══════════════════════════════════════════════════════
   TOPBAR SCROLL SHADOW
   ═══════════════════════════════════════════════════════ */
(function() {
    const topbar = document.getElementById('topbar');
    if (!topbar) return;
    window.addEventListener('scroll', function() {
        topbar.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
})();

/* ═══════════════════════════════════════════════════════
   TOAST SYSTEM
   ═══════════════════════════════════════════════════════ */
const SiKunciToast = {
    container: null,
    init() {
        this.container = document.getElementById('toastContainer');
    },
    show(message, type = 'success', duration = 4000) {
        if (!this.container) this.init();
        const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', info: 'bi-info-circle-fill' };
        const toast = document.createElement('div');
        toast.className = `sikunci-toast toast-${type}`;
        toast.innerHTML = `<i class="bi ${icons[type] || icons.info}"></i><span>${message}</span>`;
        toast.onclick = () => this.dismiss(toast);
        this.container.appendChild(toast);
        setTimeout(() => this.dismiss(toast), duration);
    },
    dismiss(el) {
        el.classList.add('toast-out');
        setTimeout(() => el.remove(), 300);
    }
};
SiKunciToast.init();

// Convert initial flash messages to toasts
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flash-msg').forEach(el => {
        const type = el.dataset.flash || 'info';
        const msg = el.textContent.trim();
        if (msg) SiKunciToast.show(msg, type);
        el.remove();
    });
});

/* ═══════════════════════════════════════════════════════
   SPA NAVIGATION ENGINE
   ═══════════════════════════════════════════════════════ */
const SiKunciSPA = {
    progressBar: null,
    contentEl: null,
    isNavigating: false,
    currentUrl: window.location.href,

    init() {
        this.progressBar = document.getElementById('spaProgressBar');
        this.contentEl = document.getElementById('spa-content');
        if (!this.contentEl) return;

        // Intercept link clicks
        document.addEventListener('click', (e) => this.handleClick(e));

        // Intercept form submissions
        document.addEventListener('submit', (e) => this.handleFormSubmit(e));

        // Handle browser back/forward
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.spaUrl) {
                this.navigate(e.state.spaUrl, false);
            } else {
                this.navigate(window.location.href, false);
            }
        });

        // Set initial state
        history.replaceState({ spaUrl: window.location.href }, '', window.location.href);
    },

    shouldIntercept(el) {
        // Don't intercept if:
        if (!el) return false;
        if (el.hasAttribute('data-no-spa')) return false;
        if (el.closest('[data-no-spa]')) return false;
        if (el.target === '_blank') return false;
        if (el.hasAttribute('download')) return false;

        const href = el.getAttribute('href');
        if (!href) return false;
        if (href.startsWith('#') || href.startsWith('javascript:')) return false;
        if (href.startsWith('mailto:') || href.startsWith('tel:')) return false;

        // Only same-origin
        try {
            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return false;
        } catch { return false; }

        // Don't intercept logout
        if (href.includes('/logout')) return false;

        return true;
    },

    handleClick(e) {
        const link = e.target.closest('a');
        if (!link || !this.shouldIntercept(link)) return;

        // Don't intercept if modifier key pressed
        if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

        e.preventDefault();
        const href = link.getAttribute('href');
        const url = new URL(href, window.location.origin);
        this.navigate(url.href, true);
    },

    handleFormSubmit(e) {
        const form = e.target;
        if (!form || form.hasAttribute('data-no-spa')) return;
        if (form.closest('[data-no-spa]')) return;

        // Don't intercept logout forms
        const action = form.getAttribute('action') || '';
        if (action.includes('/logout')) return;

        const method = (form.querySelector('input[name="_method"]')?.value || form.method || 'GET').toUpperCase();

        // For GET forms (filters, search), navigate
        if (method === 'GET') {
            e.preventDefault();
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = new URL(form.action || window.location.href);
            url.search = params.toString();
            this.navigate(url.href, true);
            return;
        }

        // For POST/PUT/DELETE forms, submit via AJAX
        e.preventDefault();
        this.submitForm(form, method);
    },

    async submitForm(form, method) {
        if (this.isNavigating) return;
        this.isNavigating = true;
        this.showProgress();

        // Disable submit button
        const submitBtn = form.querySelector('[type="submit"]');
        let originalBtnHtml = '';
        if (submitBtn) {
            originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
        }

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action || window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                credentials: 'same-origin',
                redirect: 'follow',
            });

            // Handle the response
            const html = await response.text();

            if (response.redirected) {
                // Follow the redirect via SPA
                this.processResponse(html, response.url, true);
            } else if (response.ok) {
                this.processResponse(html, response.url || window.location.href, true);
            } else {
                // On error, re-render content
                this.processResponse(html, window.location.href, false);
            }
        } catch (error) {
            console.error('SPA form submit error:', error);
            // Fallback: submit normally
            form.removeEventListener('submit', () => {});
            form.submit();
        } finally {
            // Re-enable button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
            this.isNavigating = false;
        }
    },

    async navigate(url, pushState = true) {
        if (this.isNavigating) return;
        if (url === this.currentUrl && pushState) return;

        this.isNavigating = true;
        this.showProgress();

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                credentials: 'same-origin',
            });

            // If redirected to login, do full redirect
            if (response.url.includes('/login')) {
                window.location.href = response.url;
                return;
            }

            const html = await response.text();
            this.processResponse(html, response.url || url, pushState);

        } catch (error) {
            console.error('SPA navigation error:', error);
            window.location.href = url;
        } finally {
            this.isNavigating = false;
        }
    },

    processResponse(html, url, pushState) {
        const doc = new DOMParser().parseFromString(html, 'text/html');

        // Extract content from #spa-content
        const newContent = doc.querySelector('#spa-content');
        if (!newContent) {
            // Not a SPA-compatible page, do full navigation
            window.location.href = url;
            return;
        }

        // Extract page title
        const newTitle = doc.querySelector('.topbar-title');
        const pageTitle = doc.querySelector('title');

        // Extract flash messages and show as toasts
        const flashes = newContent.querySelectorAll('.flash-msg');
        flashes.forEach(flash => {
            const type = flash.dataset.flash || 'info';
            const msg = flash.textContent.trim();
            if (msg) SiKunciToast.show(msg, type);
            flash.remove();
        });

        // Animate out
        this.contentEl.classList.add('spa-loading');

        setTimeout(() => {
            // Replace content
            this.contentEl.innerHTML = newContent.innerHTML;

            // Update title
            if (newTitle) {
                document.getElementById('topbarTitle').textContent = newTitle.textContent;
            }
            if (pageTitle) {
                document.title = pageTitle.textContent;
            }

            // Update topbar back button
            const newTopbar = doc.querySelector('.topbar');
            const currentTopbarBtns = document.querySelector('.topbar .d-flex');
            const newTopbarBtns = newTopbar?.querySelector('.d-flex');
            if (currentTopbarBtns && newTopbarBtns) {
                currentTopbarBtns.innerHTML = newTopbarBtns.innerHTML;
            }

            // Update sidebar active state
            this.updateSidebarActive(url);

            // Update URL
            if (pushState) {
                history.pushState({ spaUrl: url }, '', url);
            }
            this.currentUrl = url;

            // Execute inline scripts
            this.executeScripts(this.contentEl);

            // Re-init Bootstrap components
            this.reinitBootstrap();

            // Animate in
            requestAnimationFrame(() => {
                this.contentEl.classList.remove('spa-loading');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            this.hideProgress();

            // Close mobile sidebar
            closeSidebar();

            // Fire custom event
            window.dispatchEvent(new CustomEvent('sikunci:page-loaded', { detail: { url } }));

        }, 150); // Wait for fade-out
    },

    updateSidebarActive(url) {
        const links = document.querySelectorAll('.sidebar .nav-link');
        const urlPath = new URL(url).pathname;

        links.forEach(link => {
            const linkPath = new URL(link.href).pathname;
            link.classList.remove('active');

            // Exact match or prefix match
            if (linkPath === urlPath || (linkPath !== '/' && urlPath.startsWith(linkPath))) {
                link.classList.add('active');
            }
        });
    },

    executeScripts(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            if (oldScript.src) {
                newScript.src = oldScript.src;
            } else {
                newScript.textContent = oldScript.textContent;
            }
            oldScript.replaceWith(newScript);
        });
    },

    reinitBootstrap() {
        // Re-init tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
        // Re-init modals
        document.querySelectorAll('.modal').forEach(el => {
            if (!bootstrap.Modal.getInstance(el)) {
                new bootstrap.Modal(el);
            }
        });
    },

    showProgress() {
        this.progressBar.className = 'spa-progress-bar active';
    },
    hideProgress() {
        this.progressBar.className = 'spa-progress-bar done';
        setTimeout(() => {
            this.progressBar.className = 'spa-progress-bar';
        }, 600);
    }
};

document.addEventListener('DOMContentLoaded', () => SiKunciSPA.init());

/* ═══════════════════════════════════════════════════════
   REALTIME POLLING (preserved from original)
   ═══════════════════════════════════════════════════════ */
function initRealtimePolling() {
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
            if (!response.ok || !contentType.includes('text/html')) return;
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
        } catch (error) { /* silent */ } finally { inFlight = false; }
    }

    window.addEventListener('sikunci:refresh-now', refreshRealtimeRegions);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refreshRealtimeRegions();
    });
    const timerId = setInterval(refreshRealtimeRegions, interval);

    // Store timer ID so we can clear on SPA navigation
    page.dataset.realtimeTimerId = timerId;
}

// Init realtime on load
initRealtimePolling();

// Re-init realtime after SPA navigation
window.addEventListener('sikunci:page-loaded', () => {
    // Clear old timers
    document.querySelectorAll('[data-realtime-timer-id]').forEach(el => {
        clearInterval(Number(el.dataset.realtimeTimerId));
    });
    initRealtimePolling();
});
</script>
@stack('scripts')
</body>
</html>
