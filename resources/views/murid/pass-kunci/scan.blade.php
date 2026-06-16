@extends('layouts.app')
@section('title', 'Ambil Kunci — Scan QR')
@section('page-title', 'Ambil Kunci')
@section('back-url', route('murid.dashboard'))

@push('styles')
<style>
/* ── Scan Page ── */
.scan-card {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
}
.scan-card .card-header {
    background: linear-gradient(135deg, #1E1B4B, #4338CA);
    color: #fff;
    font-weight: 700;
    font-size: .95rem;
    padding: 14px 20px;
    border-radius: 0;
    border: none;
}

/* Viewport */
.camera-viewport {
    position: relative;
    background: #0F0F1A;
    aspect-ratio: 1 / 1;
    overflow: hidden;
}
#video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Corner brackets overlay */
.scan-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}
.scan-frame {
    width: 55%;
    aspect-ratio: 1;
    position: relative;
}
.scan-frame::before,
.scan-frame::after,
.scan-frame .corner-br,
.scan-frame .corner-bl {
    content: '';
    position: absolute;
    width: 28px;
    height: 28px;
    border-color: #fff;
    border-style: solid;
    border-radius: 3px;
    opacity: .9;
}
.scan-frame::before  { top: 0;    left: 0;  border-width: 3px 0 0 3px; }
.scan-frame::after   { top: 0;    right: 0; border-width: 3px 3px 0 0; }
.scan-frame .corner-br { bottom: 0; right: 0; border-width: 0 3px 3px 0; }
.scan-frame .corner-bl { bottom: 0; left: 0;  border-width: 0 0 3px 3px; }

/* Animated scan line */
.scan-line {
    position: absolute;
    left: 4px; right: 4px;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(79,70,229,.9), rgba(99,102,241,1), rgba(79,70,229,.9), transparent);
    border-radius: 2px;
    animation: scan-move 2s ease-in-out infinite;
    box-shadow: 0 0 8px rgba(99,102,241,.7);
}
@keyframes scan-move {
    0%   { top: 8px;    opacity: 0; }
    5%   { opacity: 1; }
    50%  { top: calc(100% - 10px); }
    95%  { opacity: 1; }
    100% { top: 8px;    opacity: 0; }
}

/* Status panel */
.scan-status-panel {
    padding: 14px 20px;
    background: #fff;
    border-top: 1px solid #F1F5F9;
    min-height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: .875rem;
}
.scan-status-panel .dot-pulse {
    width: 10px; height: 10px;
    border-radius: 50%;
    background: #4F46E5;
    animation: dot-pulse 1.2s ease-in-out infinite;
    flex-shrink: 0;
}
@keyframes dot-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.5); opacity: .5; }
}

/* Success screen */
.success-screen {
    padding: 2.5rem 1.5rem;
    text-align: center;
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
}
.success-icon-ring {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: #10B981;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #fff;
    margin-bottom: 1rem;
    box-shadow: 0 8px 24px rgba(16,185,129,.35);
    animation: pop-in .4s cubic-bezier(.175,.885,.32,1.275);
}
@keyframes pop-in {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}

/* Error screen */
.error-screen {
    padding: 1.5rem;
    background: #FEF2F2;
    border-top: 1px solid #FECACA;
}

/* Steps */
.how-steps { display: flex; flex-direction: column; gap: 10px; }
.how-step  {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.how-step .num {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff;
    font-size: .7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.how-step .txt { font-size: .8rem; color: #475569; line-height: 1.45; }
.how-step .txt strong { color: #1E293B; }

/* Camera permission info box */
.camera-info-box {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: .8rem;
    color: #1D4ED8;
    margin-top: .75rem;
}
</style>
@endpush

@section('content')
{{--
    IMPORTANT: All scripts are inside @section('content') (inside #spa-content).
    This ensures SPA engine's executeScripts() re-runs camera init on every navigation.
    data-no-spa on the wrapper prevents SPA from intercepting links inside this page.
--}}

{{-- Load jsQR inside content so SPA executeScripts() re-loads it each visit --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<div class="row justify-content-center g-4" data-no-spa>

    {{-- ── Scanner Column ── --}}
    <div class="col-lg-6">

        <div class="card scan-card border-0 fade-in-up">
            {{-- Header --}}
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-camera-fill"></i>
                <span>Scan QR Code Peminjam</span>
            </div>

            {{-- Camera Viewport --}}
            <div class="camera-viewport" id="camera-viewport">
                <video id="video" autoplay playsinline muted></video>
                <canvas id="scan-canvas" style="display:none"></canvas>

                {{-- Corner-bracket overlay (hidden until camera active) --}}
                <div class="scan-overlay" id="scan-overlay" style="display:none">
                    <div class="scan-frame">
                        <div class="corner-br"></div>
                        <div class="corner-bl"></div>
                        <div class="scan-line"></div>
                    </div>
                </div>

                {{-- Placeholder before camera loads --}}
                <div id="camera-placeholder" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:rgba(255,255,255,.5);gap:8px">
                    <i class="bi bi-camera-video" style="font-size:2.5rem"></i>
                    <span style="font-size:.8rem">Memuat kamera…</span>
                </div>
            </div>

            {{-- Status bar --}}
            <div class="scan-status-panel" id="scan-status-bar">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <span class="text-muted">Menunggu akses kamera…</span>
            </div>

            {{-- Success screen (hidden by default) --}}
            <div id="success-screen" class="success-screen" style="display:none">
                <div class="success-icon-ring"><i class="bi bi-check-lg"></i></div>
                <div class="fw-bold fs-5 mb-1" id="success-title">Kunci Berhasil Diambil!</div>
                <div class="text-muted small mb-3" id="success-detail"></div>
                <a href="#" id="success-link" class="btn btn-success px-4">
                    <i class="bi bi-eye me-1"></i>Lihat Peminjaman Saya
                </a>
            </div>

            {{-- Error screen (hidden by default) --}}
            <div id="error-screen" class="error-screen" style="display:none">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-exclamation-circle-fill text-danger fs-5 mt-1"></i>
                    <div>
                        <div class="fw-semibold text-danger small">Gagal Memproses</div>
                        <div class="small text-muted" id="error-detail"></div>
                    </div>
                </div>
                <button class="btn btn-sm btn-danger w-100" id="btn-retry">
                    <i class="bi bi-arrow-clockwise me-1"></i>Scan Ulang
                </button>
            </div>

            {{-- Camera permission error --}}
            <div id="camera-error-screen" style="display:none;padding:1.5rem;text-align:center">
                <i class="bi bi-camera-video-off fs-1 text-muted d-block mb-2"></i>
                <div class="fw-semibold text-muted mb-1" id="camera-error-title">Kamera Tidak Tersedia</div>
                <div class="small text-muted mb-3" id="camera-error-msg">Berikan izin akses kamera di browser, lalu muat ulang halaman.</div>
                <button class="btn btn-sm btn-outline-primary" id="btn-retry-camera">
                    <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                </button>
                <div class="camera-info-box mt-3 text-start">
                    <strong><i class="bi bi-lightbulb me-1"></i>Tips:</strong>
                    Pastikan Anda menggunakan <strong>HTTPS</strong> atau <strong>localhost</strong>.
                    Browser memblokir akses kamera di halaman HTTP biasa.
                </div>
            </div>
        </div>

        <div class="mt-3 fade-in-up delay-1">
            <a href="{{ route('murid.dashboard') }}" class="btn btn-outline-secondary btn-sm" id="btn-back-dashboard">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- ── How-to Column ── --}}
    <div class="col-lg-4">
        <div class="card fade-in-up delay-1">
            <div class="card-header">
                <i class="bi bi-info-circle text-primary me-2"></i>Cara Ambil Kunci
            </div>
            <div class="card-body">
                <div class="how-steps">
                    <div class="how-step">
                        <div class="num">1</div>
                        <div class="txt">Izinkan akses <strong>kamera</strong> saat browser meminta</div>
                    </div>
                    <div class="how-step">
                        <div class="num">2</div>
                        <div class="txt">Minta peminjam sebelumnya membuka halaman <strong>Pass Kunci</strong></div>
                    </div>
                    <div class="how-step">
                        <div class="num">3</div>
                        <div class="txt">Arahkan kamera ke <strong>QR Code</strong> di layar mereka</div>
                    </div>
                    <div class="how-step">
                        <div class="num">4</div>
                        <div class="txt">Sistem otomatis memindahkan kunci ke akun Anda</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3 border-0 fade-in-up delay-2" style="background:linear-gradient(135deg,#FFF7ED,#FFEDD5);border:1px solid #FED7AA !important">
            <div class="card-body py-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-clock-history text-orange fs-5 mt-1" style="color:#EA580C"></i>
                    <div>
                        <div class="fw-bold small" style="color:#9A3412">QR Kedaluwarsa</div>
                        <div style="font-size:.775rem;color:#7C2D12;line-height:1.4">
                            Setiap QR hanya berlaku <strong>5 menit</strong>. Jika expired, minta peminjam generate ulang dengan klik tombol <em>Generate Ulang</em>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
(function () {
    /* ── Config ── */
    const redeemUrl = '{{ route("murid.pass-kunci.redeem") }}';
    const showBase  = '{{ url("murid/peminjaman") }}';

    /* Always read CSRF from the meta tag (stays in sync with SPA engine) */
    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    /* ── DOM refs ── */
    const video       = document.getElementById('video');
    const scanCanvas  = document.getElementById('scan-canvas');
    const overlay     = document.getElementById('scan-overlay');
    const placeholder = document.getElementById('camera-placeholder');
    const statusBar   = document.getElementById('scan-status-bar');
    const successEl   = document.getElementById('success-screen');
    const errorEl     = document.getElementById('error-screen');
    const cameraErrEl = document.getElementById('camera-error-screen');
    const cameraErrTitle = document.getElementById('camera-error-title');
    const cameraErrMsg   = document.getElementById('camera-error-msg');
    const errorDetail    = document.getElementById('error-detail');
    const successTitle   = document.getElementById('success-title');
    const successDetail  = document.getElementById('success-detail');
    const successLink    = document.getElementById('success-link');
    const retryBtn       = document.getElementById('btn-retry');
    const retryCameraBtn = document.getElementById('btn-retry-camera');
    const backBtn        = document.getElementById('btn-back-dashboard');

    /* ── State ── */
    let scanning    = false;
    let raf         = null;
    let activeStream = null;
    let scanCtx     = null;

    try {
        scanCtx = scanCanvas.getContext('2d', { willReadFrequently: true });
    } catch (e) {
        scanCtx = scanCanvas.getContext('2d');
    }

    /* ── Status bar templates ── */
    const BARS = {
        waiting:    `<div class="spinner-border spinner-border-sm text-primary" role="status"></div><span class="text-muted">Menunggu akses kamera…</span>`,
        scanning:   `<div class="dot-pulse"></div><span class="text-primary fw-semibold">Memindai… Arahkan ke QR Code</span>`,
        processing: `<div class="spinner-border spinner-border-sm text-success" role="status"></div><span class="text-success fw-semibold">Memproses transfer kunci…</span>`,
    };
    function setStatus(key) { statusBar.innerHTML = BARS[key] || ''; }

    function hideExtras() {
        successEl.style.display   = 'none';
        errorEl.style.display     = 'none';
        cameraErrEl.style.display = 'none';
        statusBar.style.display   = 'flex';
    }

    /* ── Stop camera stream cleanly ── */
    function stopCamera() {
        scanning = false;
        if (raf) { cancelAnimationFrame(raf); raf = null; }
        if (activeStream) {
            activeStream.getTracks().forEach(t => t.stop());
            activeStream = null;
        }
        if (video) video.srcObject = null;
    }

    /* ── Start camera with graceful fallback ── */
    async function startCamera() {
        hideExtras();
        setStatus('waiting');

        // Stop any previous stream first
        stopCamera();

        // Check if browser supports mediaDevices
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showCameraError(
                'Browser Tidak Didukung',
                'Browser Anda tidak mendukung akses kamera. Gunakan Chrome, Firefox, atau Safari versi terbaru di HTTPS/localhost.'
            );
            return;
        }

        // Wait for jsQR to load (it loads from CDN script above, may take a moment)
        let jsQRRetries = 0;
        while (typeof jsQR !== 'function' && jsQRRetries < 30) {
            await new Promise(r => setTimeout(r, 100));
            jsQRRetries++;
        }
        if (typeof jsQR !== 'function') {
            showCameraError('Library Scan Gagal', 'jsQR tidak dapat dimuat. Periksa koneksi internet dan muat ulang halaman.');
            return;
        }

        let stream = null;

        // Try rear camera first, fall back to any camera
        const constraints = [
            { video: { facingMode: { exact: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } } },
            { video: { facingMode: 'environment' } },
            { video: { facingMode: 'user' } },
            { video: true },
        ];

        for (const constraint of constraints) {
            try {
                stream = await navigator.mediaDevices.getUserMedia(constraint);
                break; // Got a stream, stop trying
            } catch (err) {
                // NotAllowedError = permission denied — don't try more constraints
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    showCameraError(
                        'Izin Kamera Ditolak',
                        'Akses kamera ditolak. Klik ikon kunci/kamera di address bar browser dan izinkan akses, lalu tekan "Coba Lagi".'
                    );
                    return;
                }
                // Other errors — try next constraint
                stream = null;
            }
        }

        if (!stream) {
            showCameraError(
                'Kamera Tidak Tersedia',
                'Tidak ada kamera yang dapat diakses. Pastikan perangkat memiliki kamera dan tidak sedang digunakan aplikasi lain.'
            );
            return;
        }

        activeStream = stream;
        video.srcObject = stream;

        try {
            await video.play();
        } catch (err) {
            stopCamera();
            showCameraError('Kamera Gagal Diputar', 'Terjadi kesalahan saat menampilkan kamera: ' + err.message);
            return;
        }

        placeholder.style.display = 'none';
        overlay.style.display     = 'flex';
        setStatus('scanning');
        scanning = true;
        tick();
    }

    function showCameraError(title, msg) {
        statusBar.style.display    = 'none';
        cameraErrTitle.textContent = title;
        cameraErrMsg.textContent   = msg;
        cameraErrEl.style.display  = 'block';
    }

    /* ── QR scanning loop ── */
    function tick() {
        if (!scanning) return;

        if (video.readyState >= video.HAVE_ENOUGH_DATA && video.videoWidth > 0) {
            scanCanvas.width  = video.videoWidth;
            scanCanvas.height = video.videoHeight;
            scanCtx.drawImage(video, 0, 0, scanCanvas.width, scanCanvas.height);

            let img;
            try {
                img = scanCtx.getImageData(0, 0, scanCanvas.width, scanCanvas.height);
            } catch (e) {
                // Security error (e.g. cross-origin) — skip frame
                raf = requestAnimationFrame(tick);
                return;
            }

            const code = jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
            if (code && code.data) {
                scanning = false;
                cancelAnimationFrame(raf);
                raf = null;
                redeem(code.data);
                return;
            }
        }

        raf = requestAnimationFrame(tick);
    }

    /* ── Redeem token via API ── */
    async function redeem(token) {
        setStatus('processing');
        try {
            const res = await fetch(redeemUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf(),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ token }),
                credentials: 'same-origin',
            });

            const data = await res.json();

            if (res.status === 419) {
                throw new Error('Sesi kedaluwarsa. Muat ulang halaman dan coba lagi.');
            }
            if (!res.ok) throw new Error(data.error || `Gagal memproses (HTTP ${res.status}).`);

            // SUCCESS
            stopCamera();
            statusBar.style.display = 'none';
            overlay.style.display   = 'none';

            successTitle.textContent  = data.message || 'Kunci Berhasil Diambil!';
            successDetail.textContent = data.lab_nama ? `Lab: ${data.lab_nama}` : '';
            successLink.href          = `${showBase}/${data.peminjaman_id}`;
            successEl.style.display   = 'block';

        } catch (err) {
            errorDetail.textContent = err.message || 'Terjadi kesalahan tidak diketahui.';
            statusBar.style.display = 'none';
            errorEl.style.display   = 'block';
        }
    }

    /* ── Retry scan (reuse existing stream if still active) ── */
    if (retryBtn) retryBtn.addEventListener('click', () => {
        hideExtras();
        errorEl.style.display = 'none';

        if (activeStream && activeStream.active) {
            // Stream still alive — just restart scanning loop
            setStatus('scanning');
            scanning = true;
            tick();
        } else {
            // Stream died — restart camera from scratch
            startCamera();
        }
    });

    /* ── Retry after camera error ── */
    if (retryCameraBtn) retryCameraBtn.addEventListener('click', () => {
        placeholder.style.display = 'flex';
        startCamera();
    });

    /* ── Back button — stop camera before leaving ── */
    if (backBtn) backBtn.addEventListener('click', () => {
        stopCamera();
    });

    /* ── Stop camera if user navigates away (SPA or normal) ── */
    window.addEventListener('beforeunload', stopCamera);
    window.addEventListener('sikunci:page-loaded', stopCamera);

    /* ── Init ── */
    startCamera();

})();
</script>
@endsection
