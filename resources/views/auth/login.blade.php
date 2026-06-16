<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SiKunci Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: #0F0A2E;
        }

        /* Animated gradient background */
        .bg-animated {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0F0A2E 0%, #1E1B4B 30%, #312E81 60%, #4338CA 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            z-index: 0;
        }
        .bg-animated::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background:
                radial-gradient(circle at 20% 50%, rgba(79,70,229,.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(124,58,237,.1) 0%, transparent 35%),
                radial-gradient(circle at 60% 80%, rgba(99,102,241,.12) 0%, transparent 30%);
            animation: floatingOrbs 20s linear infinite;
        }
        .bg-animated::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(2px 2px at 10% 20%, rgba(255,255,255,.15), transparent),
                radial-gradient(2px 2px at 30% 50%, rgba(255,255,255,.1), transparent),
                radial-gradient(1px 1px at 50% 30%, rgba(255,255,255,.12), transparent),
                radial-gradient(1px 1px at 70% 70%, rgba(255,255,255,.08), transparent),
                radial-gradient(2px 2px at 90% 40%, rgba(255,255,255,.1), transparent),
                radial-gradient(1px 1px at 20% 80%, rgba(255,255,255,.12), transparent),
                radial-gradient(2px 2px at 60% 10%, rgba(255,255,255,.07), transparent),
                radial-gradient(1px 1px at 80% 90%, rgba(255,255,255,.1), transparent);
            animation: twinkle 8s ease-in-out infinite alternate;
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
            0% { opacity: .6; }
            100% { opacity: 1; }
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 16px;
            position: relative;
            z-index: 1;
            animation: cardEntrance .8s cubic-bezier(.25,.46,.45,.94) both;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(30px) scale(.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            background: rgba(255,255,255,.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 25px 60px rgba(0,0,0,.35),
                0 0 0 1px rgba(255,255,255,.1) inset;
        }

        .login-header {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            padding: 36px 32px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%; right: -30%;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
        .login-header::after {
            content: '';
            position: absolute;
            bottom: -40%; left: -20%;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }

        .login-header .logo-icon {
            width: 68px;
            height: 68px;
            background: rgba(255,255,255,.2);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 14px;
            position: relative;
            animation: iconFloat 4s ease-in-out infinite;
            box-shadow: 0 8px 24px rgba(0,0,0,.15);
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .login-header h4 {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            position: relative;
            letter-spacing: -.02em;
        }
        .login-header p {
            font-size: .85rem;
            opacity: .8;
            margin: 6px 0 0;
            position: relative;
        }

        .login-body { padding: 36px 32px; }

        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #374151;
        }
        .form-control {
            border-radius: 12px;
            border-color: #E5E7EB;
            padding: 12px 16px;
            font-size: .9rem;
            transition: all .2s;
        }
        .form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79,70,229,.12);
            transform: translateY(-1px);
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            border-color: #E5E7EB;
            transition: all .2s;
        }
        .input-group:focus-within .input-group-text {
            border-color: #4F46E5;
            color: #4F46E5;
        }
        .input-group:focus-within .input-group-text i {
            color: #4F46E5;
        }

        .btn-login {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 600;
            font-size: .95rem;
            color: #fff;
            width: 100%;
            transition: all .25s;
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79,70,229,.4);
            color: #fff;
        }
        .btn-login:active {
            transform: translateY(0) scale(.98);
        }

        .divider {
            border-top: 1px solid #E5E7EB;
            margin: 24px 0;
        }

        .accounts-info {
            background: linear-gradient(135deg, #F8FAFC, #F1F5F9);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: .78rem;
            color: #64748B;
            border: 1px solid #E2E8F0;
        }
        .accounts-info strong { color: #374151; }
        .accounts-info code {
            background: rgba(79,70,229,.08);
            color: #4F46E5;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: .75rem;
        }

        .footer-text {
            animation: cardEntrance .8s cubic-bezier(.25,.46,.45,.94) .3s both;
        }

        .alert {
            border: none;
            border-radius: 12px;
            font-size: .85rem;
            animation: shakeError .4s ease;
        }
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        .form-check-input:checked {
            background-color: #4F46E5;
            border-color: #4F46E5;
        }
    </style>
</head>
<body>
<div class="bg-animated"></div>
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
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="Masukkan email" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input type="password" class="form-control border-start-0"
                               id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small" for="remember">Ingat saya</label>
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
