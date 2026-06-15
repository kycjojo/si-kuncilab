@extends('layouts.app')
@section('title', 'Pass Kunci — QR Code')
@section('page-title', 'Pass Kunci Langsung')

@push('styles')
<style>
/* ── QR Page Styles ── */
.qr-hero {
    background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
    border-radius: 20px;
    padding: 2rem;
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.qr-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.qr-hero::after {
    content: '';
    position: absolute;
    bottom: -40px; left: -40px;
    width: 150px; height: 150px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.qr-title {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: .02em;
    margin-bottom: .25rem;
}
.qr-subtitle {
    font-size: .8rem;
    opacity: .65;
    margin-bottom: 1.5rem;
}

/* Canvas wrapper */
.qr-canvas-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,.35);
    position: relative;
    z-index: 1;
}
#qr-canvas { display: block; border-radius: 4px; }

/* Countdown badge */
.countdown-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50px;
    padding: 6px 16px;
    font-size: .85rem;
    font-weight: 600;
    margin-top: 1.25rem;
    backdrop-filter: blur(4px);
    transition: background .3s;
}
.countdown-badge.urgent {
    background: rgba(239,68,68,.25);
    border-color: rgba(239,68,68,.4);
    animation: pulse-urgent 1s ease-in-out infinite;
}
@keyframes pulse-urgent {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.03); }
}

/* Loading state */
.qr-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 2rem 0;
}
.qr-loading .spinner-ring {
    width: 56px; height: 56px;
    border: 4px solid rgba(255,255,255,.2);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Error state */
.qr-error-box {
    background: rgba(239,68,68,.15);
    border: 1px solid rgba(239,68,68,.35);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-top: 1rem;
    font-size: .85rem;
    color: #FCA5A5;
}
.qr-used-box {
    background: rgba(16,185,129,.16);
    border: 1px solid rgba(16,185,129,.35);
    border-radius: 12px;
    padding: 1.25rem;
    margin-top: 1rem;
    color: #D1FAE5;
}
.qr-used-box .used-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #10B981;
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: .75rem;
}

/* Info strip */
.info-strip {
    display: flex;
    gap: 1rem;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 1rem 1.25rem;
}
.info-strip .strip-item {
    flex: 1;
    min-width: 0;
}
.info-strip .strip-label {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #94A3B8;
    margin-bottom: 2px;
}
.info-strip .strip-val {
    font-size: .85rem;
    font-weight: 600;
    color: #1E293B;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Steps */
.steps {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.step-item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    position: relative;
}
.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 36px;
    bottom: -6px;
    width: 2px;
    background: #E2E8F0;
}
.step-num {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: .75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.step-text { font-size: .825rem; color: #475569; padding-top: 6px; line-height: 1.4; }
.step-text strong { color: #1E293B; }
</style>
@endpush

@section('content')
<div class="row justify-content-center g-4">

    {{-- ── Left / Main Column ── --}}
    <div class="col-lg-6">

        {{-- QR Hero Card --}}
        <div class="qr-hero mb-4">
            <div class="qr-title"><i class="bi bi-qr-code me-2"></i>QR Code Pass Kunci</div>
            <div class="qr-subtitle">Tunjukkan layar ini ke penerima kunci</div>

            {{-- Loading state --}}
            <div id="qr-loading" class="qr-loading">
                <div class="spinner-ring"></div>
                <div style="font-size:.8rem;opacity:.7">Membuat QR Code…</div>
            </div>

            {{-- QR Canvas --}}
            <div id="qr-ready" style="display:none">
                <div class="qr-canvas-wrap">
                    <canvas id="qr-canvas" width="260" height="260"></canvas>
                </div>
                <div class="countdown-badge" id="countdown-badge">
                    <i class="bi bi-clock"></i>
                    <span>Berlaku <strong id="countdown">5:00</strong></span>
                </div>
                <div style="font-size:.7rem;opacity:.5;margin-top:.5rem">Auto-refresh saat habis</div>
            </div>

            {{-- Error state --}}
            <div id="qr-error-box" class="qr-error-box" style="display:none">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <span id="qr-error-msg">Gagal generate QR.</span>
                <br>
                <button class="btn btn-sm btn-outline-light mt-2" id="btn-retry-error">
                    <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                </button>
            </div>

            {{-- Used state --}}
            <div id="qr-used-box" class="qr-used-box" style="display:none">
                <div class="used-icon"><i class="bi bi-check-lg"></i></div>
                <div class="fw-bold mb-1">QR sudah discan</div>
                <div id="qr-used-msg" style="font-size:.85rem">Kunci berhasil dipass ke murid penerima.</div>
                <div class="mt-2" style="font-size:.72rem;opacity:.75">Halaman ini akan tertutup otomatis.</div>
            </div>
        </div>

        {{-- Info Strip --}}
        <div class="info-strip mb-4">
            <div class="strip-item">
                <div class="strip-label">Lab</div>
                <div class="strip-val">{{ $peminjaman->lab->nama }}</div>
            </div>
            <div class="strip-item">
                <div class="strip-label">Tanggal</div>
                <div class="strip-val">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</div>
            </div>
            <div class="strip-item">
                <div class="strip-label">Jam</div>
                <div class="strip-val">{{ substr($peminjaman->jam_mulai,0,5) }}–{{ substr($peminjaman->jam_selesai_rencana,0,5) }}</div>
            </div>
            <div class="strip-item">
                <div class="strip-label">Checklist</div>
                <div class="strip-val"><span class="badge bg-success">Lengkap</span></div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-outline-primary btn-sm flex-fill" id="btn-refresh-qr">
                <i class="bi bi-arrow-clockwise me-1"></i>Generate Ulang
            </button>
            <a href="{{ route('murid.dashboard') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                <i class="bi bi-arrow-left me-1"></i>Dashboard
            </a>
        </div>

    </div>

    {{-- ── Right / Steps Column ── --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ol text-primary me-2"></i>Cara Pass Kunci
            </div>
            <div class="card-body">
                <div class="steps">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-text">QR Code tampil di layar Anda — tunjukkan ke penerima kunci</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-text">Penerima buka <strong>menu Ambil Kunci</strong> di sidebar atau dashboard</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-text">Penerima arahkan kamera HP ke <strong>QR Code ini</strong></div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">4</div>
                        <div class="step-text">Kunci <strong>otomatis berpindah</strong> — QR langsung hangus setelah scan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3 border-warning border-2">
            <div class="card-body py-3 px-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-shield-exclamation text-warning fs-5 mt-1"></i>
                    <div>
                        <div class="fw-bold small">Keamanan QR</div>
                        <div class="text-muted" style="font-size:.775rem;line-height:1.4">
                            QR berlaku <strong>5 menit</strong> dan hanya bisa di-scan <strong>1 kali</strong>.
                            Setiap generate baru akan membatalkan QR lama.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
(function () {
    const generateUrl = '{{ route("murid.pass-kunci.generate", $peminjaman) }}';
    const statusUrl   = '{{ route("murid.pass-kunci.status", $peminjaman) }}';
    const historyUrl  = '{{ route("murid.peminjaman.index") }}';
    const csrfToken   = '{{ csrf_token() }}';

    const loadingEl   = document.getElementById('qr-loading');
    const readyEl     = document.getElementById('qr-ready');
    const errorEl     = document.getElementById('qr-error-box');
    const usedEl      = document.getElementById('qr-used-box');
    const errorMsg    = document.getElementById('qr-error-msg');
    const usedMsg     = document.getElementById('qr-used-msg');
    const canvas      = document.getElementById('qr-canvas');
    const countdownEl = document.getElementById('countdown');
    const badge       = document.getElementById('countdown-badge');
    const refreshBtn  = document.getElementById('btn-refresh-qr');
    const retryBtn    = document.getElementById('btn-retry-error');

    let ticker = null;
    let statusPoller = null;
    let currentToken = null;
    let closing = false;
    let checkingStatus = false;

    function setState(state) {
        loadingEl.style.display = state === 'loading' ? 'block' : 'none';
        readyEl.style.display   = state === 'ready'   ? 'block' : 'none';
        errorEl.style.display   = state === 'error'   ? 'block' : 'none';
        usedEl.style.display    = state === 'used'    ? 'block' : 'none';
        refreshBtn.disabled = state === 'loading' || state === 'used';
    }

    function stopTicker() {
        if (ticker) clearInterval(ticker);
        ticker = null;
    }

    function stopStatusPolling() {
        if (statusPoller) clearInterval(statusPoller);
        statusPoller = null;
    }

    async function generate() {
        if (closing) return;

        setState('loading');
        stopTicker();
        stopStatusPolling();
        currentToken = null;

        try {
            if (!window.QRCode || typeof window.QRCode.toCanvas !== 'function') {
                throw new Error('Library QR gagal dimuat. Refresh halaman atau cek koneksi internet.');
            }

            const res  = await fetch(generateUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (!res.ok) throw new Error(data.error || 'Gagal generate QR.');

            // Render QR onto canvas
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            await window.QRCode.toCanvas(canvas, data.token, {
                width: 260,
                margin: 1,
                color: { dark: '#1E1B4B', light: '#FFFFFF' },
            });

            setState('ready');
            startCountdown(new Date(data.expired_at));
            startStatusPolling(data.token);

        } catch (err) {
            errorMsg.textContent = err.message;
            setState('error');
        }
    }

    function startCountdown(expiredAt) {
        function tick() {
            const diff = Math.max(0, Math.floor((expiredAt - Date.now()) / 1000));
            const m = Math.floor(diff / 60);
            const s = diff % 60;
            countdownEl.textContent = `${m}:${s.toString().padStart(2, '0')}`;

            // Turn red + pulse in last 60 s
            badge.classList.toggle('urgent', diff <= 60);

            if (diff === 0 && !closing) {
                stopTicker();
                setTimeout(generate, 400);
            }
        }
        tick();
        ticker = setInterval(tick, 1000);
    }

    function startStatusPolling(token) {
        currentToken = token;
        stopStatusPolling();
        checkTokenStatus();
        statusPoller = setInterval(checkTokenStatus, 2000);
    }

    async function checkTokenStatus() {
        if (!currentToken || closing || checkingStatus) return;

        checkingStatus = true;
        try {
            const res = await fetch(statusUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ token: currentToken }),
            });
            const data = await res.json();

            if (!res.ok) return;

            if (data.status === 'used') {
                handleUsedToken(data);
            } else if (data.status === 'expired') {
                stopStatusPolling();
                generate();
            }
        } catch {
            // Polling may fail briefly on mobile networks; the next tick will retry.
        } finally {
            checkingStatus = false;
        }
    }

    function handleUsedToken(data) {
        if (closing) return;

        closing = true;
        stopTicker();
        stopStatusPolling();
        badge.classList.remove('urgent');

        const namaPenerima = data.used_by_name || 'murid penerima';
        usedMsg.textContent = `Kunci berhasil diserahkan ke ${namaPenerima}.`;
        setState('used');

        setTimeout(() => {
            window.close();
            setTimeout(() => {
                window.location.href = data.redirect_url || historyUrl;
            }, 300);
        }, 1500);
    }

    refreshBtn.addEventListener('click', generate);
    retryBtn.addEventListener('click', generate);
    window.addEventListener('beforeunload', () => {
        stopTicker();
        stopStatusPolling();
    });

    generate();
})();
</script>
@endpush
