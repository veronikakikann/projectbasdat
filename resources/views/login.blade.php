<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teman Kerja | Masuk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/teman-kerja.css') }}">
</head>
<body>
<div class="auth-page">
<div class="auth-card">
    <aside class="auth-aside">
        <a href="/" class="auth-brand">Teman <span>Kerja</span></a>
        <div>
            <h2>Temukan kerja. <em>Temukan orang yang tepat.</em></h2>
            <p>Teman Kerja mempertemukan pencari kerja dan pemberi kerja harian di sekitar kamu tanpa proses yang rumit.</p>
        </div>
    </aside>
    <main class="auth-main">
        <h1>Masuk ke Akun</h1>
        <p class="auth-sub">Silakan masuk untuk melanjutkan.</p>

        {{-- Pesan berhasil --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Pesan error umum (seperti "Silakan masuk terlebih dahulu" atau "Akun dinonaktifkan") --}}
        @if(session('error'))
            <p style="color: #dc3545; font-size: 14px; margin-bottom: 15px; font-weight: 500;">
                {{ session('error') }}
            </p>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <!-- ROLE -->
            <div class="form-group">
                <label for="role">Masuk sebagai</label>
                <select name="role" id="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="pemberi_kerja">Pemberi Kerja</option>
                    <option value="pencari_kerja">Pencari Kerja</option>
                </select>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                
                {{-- Pesan Error Khusus Email --}}
                @error('email')
                    <p style="color: #dc3545; font-size: 13px; margin-top: 4px; margin-bottom: 0;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                
                {{-- Pesan Error Khusus Password --}}
                @error('password')
                    <p style="color: #dc3545; font-size: 13px; margin-top: 4px; margin-bottom: 0;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="btn btn-accent btn-block">Masuk</button>
        </form>

        <!-- REGISTER -->
        <div class="auth-footer">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </main>
</div>
</div>
</body>
</html>