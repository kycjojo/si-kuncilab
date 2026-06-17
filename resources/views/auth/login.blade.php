<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0F0A2E">
    <title>Login — SiKunci Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: #0F0A2E;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Animated gradient background ── */
        .bg-animated {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0F0A2E 0%, #1E1B4B 30%, #312E81 60%, #4338CA 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 0;
        }
        .bg-animated::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background:
                radial-gradient(circle at 20% 50%, rgba(79,70,229,.18) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(124,58,237,.12) 0%, transparent 35%),
                radial-gradient(circle at 60% 80%, rgba(99,102,241,.15) 0%, transparent 30%);
            animation: floatingOrbs 25s linear infinite;
        }
        .bg-animated::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(2px 2px at 10% 20%, rgba(255,255,255,.18), transparent),
                radial-gradient(2px 2px at 30% 50%, rgba(255,255,255,.12), transparent),
                radial-gradient(1px 1px at 50% 30%, rgba(255,255,255,.15), transparent),
                radial-gradient(1px 1px at 70% 70%, rgba(255,255,255,.1), transparent),
                radial-gradient(2px 2px at 90% 40%, rgba(255,255,255,.12), transparent),
                radial-gradient(1px 1px at 20% 80%, rgba(255,255,255,.14), transparent),
                radial-gradient(2px 2px at 60% 10%, rgba(255,255,255,.08), transparent),
                radial-gradient(1px 1px at 80% 90%, rgba(255,255,255,.12), transparent),
                radial-gradient(1px 1px at 45% 45%, rgba(255,255,255,.1), transparent),
                radial-gradient(2px 2px at 15% 65%, rgba(255,255,255,.06), transparent);
            animation: twinkle 6s ease-in-out infinite alternate;
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(60px);
        }
        .orb-1 {
            width: 300px; height: 300px;
            background: rgba(79,70,229,.12);
            top: -100px; right: -80px;
            animation: orbMove1 20s ease-in-out infinite;
        }
        .orb-2 {
            width: 200px; height: 200px;
            background: rgba(124,58,237,.1);
            bottom: -60px; left: -40px;
            animation: orbMove2 18s ease-in-out infinite;
        }
        .orb-3 {
            width: 150px; height: 150px;
            background: rgba(99,102,241,.08);
            top: 50%; left: 60%;
            animation: orbMove3 22s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes floatingOrbs {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes twinkle {
            0% { opacity: .5; }
            100% { opacity: 1; }
        }
        @keyframes orbMove1 {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(-30px, 40px); }
            66% { transform: translate(20px, -20px); }
        }
        @keyframes orbMove2 {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(40px, -30px); }
            66% { transform: translate(-20px, 20px); }
        }
        @keyframes orbMove3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-40px, 30px) scale(1.15); }
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
            z-index: 1;
            animation: cardEntrance .7s cubic-bezier(.25,.46,.45,.94) both;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(30px) scale(.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            background: rgba(255,255,255,.97);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 25px 60px rgba(0,0,0,.4),
                0 0 0 1px rgba(255,255,255,.08) inset,
                0 0 80px rgba(79,70,229,.08);
        }

        .login-header {
            background: linear-gradient(135deg, #4F46E5 0%, #6D28D9 50%, #7C3AED 100%);
            padding: 40px 32px 36px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .login-header::before {
            content: '';
            position: absolute;
            top: -60%; right: -30%;
            width: 220px; height: 220px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
        .login-header::after {
            content: '';
            position: absolute;
            bottom: -50%; left: -20%;
            width: 180px; height: 180px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }

        .login-header .logo-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(12px);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 16px;
            position: relative;
            animation: iconFloat 5s ease-in-out infinite;
            box-shadow: 0 8px 32px rgba(0,0,0,.2), 0 0 0 1px rgba(255,255,255,.1) inset;
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }

        .login-header h4 {
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0;
            position: relative;
            letter-spacing: -.02em;
        }
        .login-header p {
            font-size: .85rem;
            opacity: .75;
            margin: 6px 0 0;
            position: relative;
            font-weight: 300;
        }

        .login-body { padding: 36px 32px; }

        .form-label {
            font-weight: 600;
            font-size: .82rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .form-control {
            border-radius: 14px;
            border: 1.5px solid #E5E7EB;
            padding: 13px 16px;
            font-size: .9rem;
            transition: all .2s;
            background: #FAFBFF;
        }
        .form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79,70,229,.12);
            background: #fff;
            transform: translateY(-1px);
        }
        .input-group-text {
            border-radius: 14px 0 0 14px;
            border: 1.5px solid #E5E7EB;
            border-right: none;
            background: #F8FAFF;
            transition: all .2s;
        }
        .input-group .form-control {
            border-radius: 0 14px 14px 0;
            border-left: none;
        }
        .input-group:focus-within .input-group-text {
            border-color: #4F46E5;
            color: #4F46E5;
            background: rgba(79,70,229,.04);
        }
        .input-group:focus-within .input-group-text i {
            color: #4F46E5;
        }

        .btn-login {
            background: linear-gradient(135deg, #4F46E5 0%, #6D28D9 50%, #7C3AED 100%);
            background-size: 200% 200%;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 600;
            font-size: .95rem;
            color: #fff;
            width: 100%;
            transition: all .3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(79,70,229,.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(79,70,229,.45);
            background-position: 100% 0;
            color: #fff;
        }
        .btn-login:active {
            transform: translateY(0) scale(.98);
        }

        .divider {
            border-top: 1px solid #E5E7EB;
            margin: 24px 0;
            position: relative;
        }

        .accounts-info {
            background: linear-gradient(135deg, #F8FAFF 0%, #F1F5F9 100%);
            border-radius: 14px;
            padding: 16px 18px;
            font-size: .78rem;
            color: #64748B;
            border: 1px solid #E2E8F0;
            line-height: 1.7;
        }
        .accounts-info strong { color: #374151; }
        .accounts-info code {
            background: rgba(79,70,229,.08);
            color: #4F46E5;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: .73rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }

        .footer-text {
            animation: cardEntrance .8s cubic-bezier(.25,.46,.45,.94) .3s both;
        }

        .alert {
            border: none;
            border-radius: 14px;
            font-size: .85rem;
            animation: shakeError .5s ease;
            border-left: 4px solid;
        }
        .alert-danger { border-left-color: #EF4444; }
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(2px); }
        }

        .form-check-input:checked {
            background-color: #4F46E5;
            border-color: #4F46E5;
        }
        .form-check-input {
            width: 1.1em;
            height: 1.1em;
            border-radius: 5px;
            border: 1.5px solid #CBD5E1;
        }
        .form-check-label {
            font-size: .85rem;
            padding-top: 1px;
        }

        /* ── Mobile optimizations ── */
        @media (max-width: 575.98px) {
            body { align-items: flex-end; }
            .login-wrapper {
                padding: 0;
                max-width: none;
            }
            .login-card {
                border-radius: 24px 24px 0 0;
                box-shadow: 0 -10px 60px rgba(0,0,0,.4);
            }
            .login-header { padding: 32px 24px 28px; }
            .login-header .logo-icon { width: 64px; height: 64px; font-size: 1.8rem; border-radius: 18px; }
            .login-header h4 { font-size: 1.35rem; }
            .login-body { padding: 28px 24px; }
            .form-control { padding: 14px 16px; min-height: 48px; }
            .input-group-text { min-height: 48px; }
            .btn-login { padding: 15px; min-height: 52px; font-size: 1rem; }
            .footer-text { display: none; }
            .accounts-info { font-size: .75rem; }
        }

        /* Safe area for phones with notch/gesture bar */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            @media (max-width: 575.98px) {
                .login-card {
                    padding-bottom: env(safe-area-inset-bottom);
                }
            }
        }
    </style>
</head>
<body>
<div class="bg-animated"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon"><i class="bi bi-key-fill"></i></div>
            <h4>SiKunci Lab</h4>
            <p>Sistem Peminjaman Kunci Laboratorium</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger d-flex gap-2 align-items-center mb-3" role="alert">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="bi bi-envelope" style="font-size:.75rem;opacity:.6"></i> Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="Masukkan email" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label"><i class="bi bi-lock" style="font-size:.75rem;opacity:.6"></i> Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input type="password" class="form-control"
                               id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-login" id="login-submit-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="divider"></div>

            <div class="accounts-info">
                <strong><i class="bi bi-info-circle me-1"></i>Akun Demo:</strong><br>
                Admin: <code>admin@labsekolah.com</code><br>
                Pengawas: <code>pengawas@labsekolah.com</code><br>
                Murid: <code>ahmad@labsekolah.com</code><br>
                <span class="text-muted">Password: <code>password</code></span>
            </div>
        </div>
    </div>
    <p class="text-center text-white-50 mt-3 small footer-text">
        &copy; {{ date('Y') }} SiKunci Lab — Sistem Peminjaman Kunci Laboratorium Sekolah
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Login form loading state
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('login-submit-btn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    btn.disabled = true;
});
</script>
</body>
</html>
