<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0F172A">
    <title>Login — SiKunci Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%; min-height: 100dvh;
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
        }

        /* ── Left panel ── */
        .left-panel {
            background: #0F172A;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(79,70,229,.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(124,58,237,.15) 0%, transparent 40%);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: #4F46E5;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: #fff;
        }
        .brand-sub {
            font-size: .72rem;
            color: rgba(255,255,255,.4);
            margin-top: 1px;
        }

        .hero-content { position: relative; }
        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 14px;
            letter-spacing: -.02em;
        }
        .hero-title span { color: #818CF8; }
        .hero-desc {
            font-size: .9rem;
            color: rgba(255,255,255,.5);
            line-height: 1.6;
            max-width: 340px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 28px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,.6);
            font-size: .82rem;
        }
        .feature-item i {
            width: 28px; height: 28px;
            background: rgba(79,70,229,.25);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            color: #818CF8;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .left-footer {
            font-size: .72rem;
            color: rgba(255,255,255,.2);
            position: relative;
        }

        /* ── Right panel ── */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: #fff;
        }
        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-heading {
            margin-bottom: 28px;
        }
        .login-heading h2 {
            font-weight: 700;
            font-size: 1.5rem;
            color: #111827;
            letter-spacing: -.02em;
            margin-bottom: 4px;
        }
        .login-heading p {
            font-size: .85rem;
            color: #6B7280;
        }

        .form-label {
            font-weight: 600;
            font-size: .8rem;
            color: #374151;
            display: block;
            margin-bottom: 6px;
        }
        .form-control {
            display: block;
            width: 100%;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: .9rem;
            font-family: inherit;
            color: #111827;
            background: #FAFAFA;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control:focus {
            border-color: #4F46E5;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .form-control.is-invalid { border-color: #DC2626; }
        .input-with-icon {
            position: relative;
        }
        .input-with-icon .form-control { padding-left: 40px; }
        .input-with-icon .input-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: .95rem;
            pointer-events: none;
        }

        .btn-login {
            display: block;
            width: 100%;
            padding: 12px;
            background: #4F46E5;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .9rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background .15s, box-shadow .15s;
        }
        .btn-login:hover {
            background: #3730A3;
            box-shadow: 0 4px 12px rgba(79,70,229,.25);
        }
        .btn-login:active { transform: scale(.98); }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-check-input {
            width: 16px; height: 16px;
            border: 1.5px solid #D1D5DB;
            border-radius: 4px;
            flex-shrink: 0;
            accent-color: #4F46E5;
            cursor: pointer;
        }
        .form-check-label {
            font-size: .82rem;
            color: #6B7280;
            cursor: pointer;
        }

        .divider {
            border: none;
            border-top: 1px solid #F3F4F6;
            margin: 24px 0;
        }

        .demo-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: .77rem;
            color: #6B7280;
            line-height: 1.8;
        }
        .demo-box strong { color: #374151; display: block; margin-bottom: 2px; }
        .demo-code {
            background: #EEF2FF;
            color: #4F46E5;
            padding: 1px 7px;
            border-radius: 5px;
            font-size: .73rem;
            font-weight: 600;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .85rem;
            color: #DC2626;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            animation: shake .4s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        /* ── Mobile: single column ── */
        @media (max-width: 767.98px) {
            body {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
            }
            .left-panel {
                padding: 20px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                min-height: auto;
            }
            .left-panel::before { display: none; }
            .hero-content, .feature-list, .left-footer { display: none; }
            .right-panel { padding: 32px 20px; align-items: flex-start; }
            .login-box { max-width: none; }
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .right-panel {
                padding-bottom: calc(40px + env(safe-area-inset-bottom));
            }
        }
    </style>
</head>
<body>
    <!-- Left branding panel -->
    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-key-fill"></i></div>
            <div>
                <div class="brand-name">SiKunci Lab</div>
                <div class="brand-sub">Sistem Peminjaman Kunci</div>
            </div>
        </div>

        <div class="hero-content">
            <h2 class="hero-title">Kelola kunci lab<br>dengan <span>mudah</span>.</h2>
            <p class="hero-desc">
                Platform digital untuk peminjaman dan pengelolaan kunci laboratorium sekolah secara efisien dan terstruktur.
            </p>
            <div class="feature-list">
                <div class="feature-item">
                    <i class="bi bi-qr-code-scan"></i>
                    Serah terima kunci via QR Code
                </div>
                <div class="feature-item">
                    <i class="bi bi-shield-check"></i>
                    Sistem pelanggaran otomatis
                </div>
                <div class="feature-item">
                    <i class="bi bi-bar-chart-line"></i>
                    Laporan peminjaman real-time
                </div>
            </div>
        </div>

        <div class="left-footer">
            &copy; {{ date('Y') }} SiKunci Lab
        </div>
    </div>

    <!-- Right login panel -->
    <div class="right-panel">
        <div class="login-box">
            <div class="login-heading">
                <h2>Masuk</h2>
                <p>Masukkan akun Anda untuk melanjutkan</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-with-icon">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password"
                               class="form-control"
                               id="password" name="password"
                               placeholder="••••••••"
                               required>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn-login" id="login-submit-btn">
                    Masuk
                </button>
            </form>

            <hr class="divider">

            <div class="demo-box">
                <strong><i class="bi bi-info-circle me-1"></i> Akun Demo</strong>
                Admin: <span class="demo-code">admin@labsekolah.com</span><br>
                Pengawas: <span class="demo-code">pengawas@labsekolah.com</span><br>
                Murid: <span class="demo-code">ahmad@labsekolah.com</span><br>
                Password: <span class="demo-code">password</span>
            </div>
        </div>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function () {
        const btn = document.getElementById('login-submit-btn');
        btn.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        btn.style.opacity = '.7';
        btn.disabled = true;
    });
    </script>
</body>
</html>
