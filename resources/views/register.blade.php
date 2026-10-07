<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Teman Kerja</title>
    
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
            padding: 40px 0;
            font-family: var(--font-body);
            background-color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .register-card {
            background-color: var(--color-surface);
            width: 100%;
            max-width: 480px;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            margin: 20px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 25px;
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
        }

        .subtitle strong {
            color: var(--color-ink);
        }

        .form-group {
            margin-bottom: 22px;
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

        input[type="file"] {
            padding: 10px 16px;
            background-color: #FAFAFA;
            color: var(--color-ink-soft);
            font-size: 0.95rem;
        }
        
        input[type="file"]::file-selector-button {
            border: 1px solid var(--color-border);
            padding: 6px 12px;
            border-radius: 6px;
            background-color: var(--color-surface);
            color: var(--color-ink);
            cursor: pointer;
            font-weight: 600;
            margin-right: 15px;
            transition: background-color 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #F3F4F6;
        }

        .help-text {
            display: block;
            margin-top: 6px;
            font-size: 0.85rem;
            color: var(--color-ink-soft);
        }

        .btn-register {
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
            margin-top: 20px;
            margin-bottom: 25px;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn-register:hover {
            filter: brightness(0.95);
            transform: translateY(-2px);
        }

        .footer-text {
            text-align: center;
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

    <div class="register-card">
        
        <div class="register-header">
            <div class="logo">Teman<span>Kerja</span></div>
            <div class="subtitle">Buat akun sebagai pengguna <strong>Teman Kerja</strong></div>
        </div>

        <!-- KOTAK PESAN SUCCESS -->
        @if (session('success'))
            <div style="background-color: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #34D399; text-align: left;">
                {{ session('success') }}
            </div>
        @endif

        <!-- KOTAK PESAN ERROR CUSTOM (Contoh: Email sudah terdaftar) -->
        @if (session('error'))
            <div style="background-color: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #F87171; text-align: left;">
                {{ session('error') }}
            </div>
        @endif

        <!-- KOTAK PESAN ERROR VALIDASI -->
        @if ($errors->any())
            <div style="background-color: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; font-size: 0.9rem; border: 1px solid #F87171;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/register" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="role">Daftar Sebagai</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="" disabled selected>Pilih peran kamu...</option>
                    <option value="pencari_kerja">Pencari Kerja</option>
                    <option value="pemberi_kerja">Pemberi Kerja</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nik">NIK</label>
                <!-- pastikan memakai value="{{ old('nik') }}" (opsional) agar jika error ketikannya tidak hilang -->
                <input type="text" name="nik" id="nik" class="form-control" placeholder="Masukkan NIK (16 digit)" required pattern="\d{16}" title="NIK harus terdiri dari 16 angka" value="{{ old('nik') }}">
            </div>

            <div class="form-group">
                <label for="ktp">Unggah KTP</label>
                <input type="file" name="file_ktp" id="ktp" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <span class="help-text">Format: JPG, JPEG, PNG, atau PDF. Maksimal 2MB.</span>
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap Sesuai KTP" required value="{{ old('nama') }}">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat Sesuai KTP" required value="{{ old('alamat') }}">
            </div>

            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <input type="tel" name="no_telpon" id="telepon" class="form-control" placeholder="Masukkan Nomor Telepon Aktif" required value="{{ old('no_telpon') }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Alamat Email" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 Karakter" required minlength="6">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Masukkan Ulang Kata Sandi" required minlength="6">
            </div>

            <button type="submit" class="btn-register">DAFTAR</button>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="/login">Masuk</a>
        </div>
        
    </div>

</body>
</html>