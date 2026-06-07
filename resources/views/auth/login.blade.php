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
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1E1B4B 0%, #312E81 40%, #4338CA 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 16px;
        }
        .login-card {
            background: rgba(255,255,255,.97);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }
        .login-header {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            padding: 32px;
            text-align: center;
            color: #fff;
        }
        .login-header .logo-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 12px;
        }
        .login-header h4 { font-weight: 700; font-size: 1.4rem; margin: 0; }
        .login-header p { font-size: .85rem; opacity: .8; margin: 4px 0 0; }
        .login-body { padding: 32px; }
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; }
        .form-control {
            border-radius: 10px;
            border-color: #E5E7EB;
            padding: 10px 14px;
            font-size: .9rem;
        }
        .form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.15);
        }
        .btn-login {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: .95rem;
            color: #fff;
            width: 100%;
            transition: opacity .2s;
        }
        .btn-login:hover { opacity: .9; color: #fff; }
        .divider {
            border-top: 1px solid #E5E7EB;
            margin: 20px 0;
            position: relative;
        }
        .accounts-info {
            background: #F8FAFC;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: .78rem;
            color: #64748B;
        }
        .accounts-info strong { color: #374151; }
    </style>
</head>
<body>
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
    <p class="text-center text-white-50 mt-3 small">
        &copy; {{ date('Y') }} SiKunci Lab — Sistem Peminjaman Kunci Laboratorium Sekolah
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
