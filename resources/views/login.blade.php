<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Teman Kerja</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #55B4EA;
            --color-accent: #F5FF00;
            --color-surface: #FFFFFF;
            --color-ink: #171717;
            --color-ink-soft: #5B6472;
            --color-border: #E5E7EB;
            --font-heading: 'Manrope', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: var(--font-body);
            background-color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            background-color: var(--color-surface);
            width: 100%;
            max-width: 420px;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            box-sizing: border-box;
            margin: 20px;
        }

        .logo {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--color-ink);
            margin-bottom: 10px;
        }

        .logo span {
            color: var(--color-accent);
        }

        .subtitle {
            color: var(--color-ink-soft);
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            color: var(--color-ink);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            font-size: 1rem;
            font-family: var(--font-body);
            box-sizing: border-box;
            color: var(--color-ink);
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        .form-control::placeholder {
            color: #A0AEC0;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%235B6472%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 16px top 50%;
            background-size: 12px auto;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background-color: var(--color-accent);
            color: var(--color-ink);
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 800;
            font-family: var(--font-heading);
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 25px;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn-login:hover {
            filter: brightness(0.95);
            transform: translateY(-2px);
        }

        .footer-text {
            color: var(--color-ink-soft);
            font-size: 0.95rem;
        }

        .footer-text a {
            color: var(--color-ink);
            font-weight: 700;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo">Teman<span>Kerja</span></div>
        <div class="subtitle">Selamat datang kembali! Silakan masuk ke akunmu.</div>

        <!-- KOTAK PESAN SUCCESS (Berhasil Daftar) -->
        @if (session('success'))
            <div style="background-color: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #34D399; text-align: left;">
                {{ session('success') }}
            </div>
        @endif

        <!-- KOTAK PESAN ERROR CUSTOM (Contoh: Akun belum diverifikasi) -->
        @if (session('error'))
            <div style="background-color: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #F87171; text-align: left;">
                {{ session('error') }}
            </div>
        @endif

        <!-- KOTAK PESAN ERROR VALIDASI (Contoh: Email / Password kosong) -->
        @if ($errors->any())
            <div style="background-color: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; font-size: 0.9rem; border: 1px solid #F87171;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="role">Masuk Sebagai</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="" disabled selected>Pilih peran kamu...</option>
                    <option value="pencari_kerja">Pencari Kerja</option>
                    <option value="pemberi_kerja">Pemberi Kerja</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan kata sandi" required>
            </div>

            <button type="submit" class="btn-login">MASUK</button>
        </form>

        <div class="footer-text">
            Belum punya akun? <a href="/register">Daftar sekarang</a>
        </div>
    </div>

</body>
</html>