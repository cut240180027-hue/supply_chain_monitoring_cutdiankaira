<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SCM Risk Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #120a14;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated gradient background */
        .bg-orbs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 400px; height: 400px; background: #ec4899; top: -100px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 350px; height: 350px; background: #f43f5e; bottom: -80px; right: -80px; animation-delay: 3s; }
        .orb-3 { width: 250px; height: 250px; background: #d946ef; top: 40%; left: 60%; animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        /* Grid pattern overlay */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(236,72,153,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(236,72,153,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.6rem;
            color: #fff;
            box-shadow: 0 0 40px rgba(236,72,153,0.4);
        }
        .login-logo h1 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
        }
        .login-logo p {
            font-size: 0.75rem;
            color: #64748b;
            margin: 0;
        }

        /* Card */
        .login-card {
            background: rgba(37, 19, 40, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(236,72,153,0.12);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .login-card h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 6px;
        }
        .login-card .subtitle {
            font-size: 0.75rem;
            color: #64748b;
            margin: 0 0 28px;
        }

        /* Alert */
        .login-alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.78rem;
            color: #f87171;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .login-alert-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.78rem;
            color: #34d399;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form */
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 8px;
        }
        .form-input-wrap {
            position: relative;
        }
        .form-input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            font-size: 0.95rem;
        }
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px;
            padding: 12px 14px 12px 40px;
            color: #e2e8f0;
            font-size: 0.82rem;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
        }
        .form-input:focus {
            outline: none;
            background: rgba(236,72,153,0.08);
            border-color: rgba(236,72,153,0.5);
            box-shadow: 0 0 0 3px rgba(236,72,153,0.12);
        }
        .form-input::placeholder { color: #475569; }

        .btn-toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #475569;
            cursor: pointer;
            padding: 4px;
            transition: color .18s;
        }
        .btn-toggle-pw:hover { color: #94a3b8; }

        .form-error {
            font-size: 0.7rem;
            color: #f87171;
            margin-top: 5px;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all .22s;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(236,72,153,0.4);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login .spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .back-to-site {
            text-align: center;
            margin-top: 24px;
        }
        .back-to-site a {
            font-size: 0.73rem;
            color: #475569;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color .18s;
        }
        .back-to-site a:hover { color: #94a3b8; }

        /* Credential hint for development */
        .dev-hint {
            background: rgba(236,72,153,0.08);
            border: 1px solid rgba(236,72,153,0.15);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.72rem;
            color: #f472b6;
            margin-top: 16px;
        }
        .dev-hint strong { display: block; margin-bottom: 3px; }
        .dev-hint code {
            background: rgba(236,72,153,0.15);
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            color: #fbcfe8;
        }
    </style>
</head>
<body>

    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="bg-grid"></div>

    <div class="login-wrapper">

        {{-- Logo --}}
        <div class="login-logo">
            <div class="logo-icon"><i class="bi bi-shield-check"></i></div>
            <h1>SCM Risk Intelligence</h1>
            <p>Admin Control Panel</p>
        </div>

        {{-- Card --}}
        <div class="login-card">
            <h2>Masuk ke Panel Admin</h2>
            <p class="subtitle">Masukkan kredensial admin Anda untuk melanjutkan.</p>

            {{-- Alerts --}}
            @if(session('error'))
                <div class="login-alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="login-alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                @csrf

                {{-- Username / Email --}}
                <div class="form-group">
                    <label class="form-label">Username / Email</label>
                    <div class="form-input-wrap">
                        <i class="bi bi-person form-input-icon"></i>
                        <input
                            type="text"
                            name="login_id"
                            id="login_id"
                            class="form-input"
                            placeholder="Username atau Email"
                            value="{{ old('login_id') }}"
                            required
                            autocomplete="username"
                        >
                    </div>
                    @error('login_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrap">
                        <i class="bi bi-lock form-input-icon"></i>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="btn-toggle-pw" id="togglePw" title="Toggle password visibility">
                            <i class="bi bi-eye" id="pwIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span id="loginText"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Panel Admin</span>
                    <div class="spinner" id="loginSpinner"></div>
                </button>
            </form>

            {{-- Development hint --}}
            @if(App\Models\User::count() > 0)
            <div class="dev-hint">
                <strong><i class="bi bi-info-circle me-1"></i> Akun Admin Tersedia</strong>
                Email: <code>{{ App\Models\User::first()->email }}</code><br>
                Password: gunakan password yang telah Anda atur saat registrasi.
            </div>
            @else
            <div class="dev-hint">
                <strong><i class="bi bi-exclamation-triangle me-1"></i> Belum ada user</strong>
                Jalankan <code>php artisan db:seed</code> atau tambah user melalui database.
            </div>
            @endif
        </div>

        {{-- Back link --}}
        <div class="back-to-site">
            <a href="{{ route('dashboard') }}">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Aplikasi Utama
            </a>
        </div>

    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePw').addEventListener('click', function () {
            const pw = document.getElementById('password');
            const icon = document.getElementById('pwIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pw.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });

        // Show loading spinner on submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            document.getElementById('loginText').style.display = 'none';
            document.getElementById('loginSpinner').style.display = 'block';
            document.getElementById('loginBtn').disabled = true;
        });
    </script>
</body>
</html>
