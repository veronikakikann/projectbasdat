<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Teman Kerja</title>
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
            <h2>Mulai cari kerja atau <em>cari pekerja</em> hari ini.</h2>
            <p>Daftar sebagai pencari kerja atau pemberi kerja. Akunmu akan diverifikasi admin sebelum dapat digunakan sepenuhnya.</p>
        </div>
    </aside>
    <main class="auth-main">
        <h1>Buat Akun</h1>
        <p class="auth-sub">Daftar sebagai pengguna Teman Kerja.</p>

        {{-- Error --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Validation error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- ROLE -->
            <div class="form-group">
                <label for="role">Daftar sebagai</label>
                <select name="role" id="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="pemberi_kerja" {{ old('role') == 'pemberi_kerja' ? 'selected' : '' }}>Pemberi Kerja</option>
                    <option value="pencari_kerja" {{ old('role') == 'pencari_kerja' ? 'selected' : '' }}>Pencari Kerja</option>
                </select>
            </div>

            <div class="form-row">
                <!-- NIK -->
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" name="nik" id="nik" placeholder="Masukkan NIK" value="{{ old('nik') }}" required>
                </div>

                <!-- NO TELEPON -->
                <div class="form-group">
                    <label for="no_telpon">Nomor Telepon</label>
                    <input type="text" name="no_telpon" id="no_telpon" placeholder="Masukkan nomor telepon" value="{{ old('no_telpon') }}" required>
                </div>
            </div>

            <!-- KTP -->
            <div class="form-group">
                <label for="file_ktp">Upload KTP</label>
                <input type="file" name="file_ktp" id="file_ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                <small>Format: JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.</small>
            </div>

            <!-- NAMA -->
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
            </div>

            <!-- ALAMAT -->
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
            </div>

            <div class="form-row">
                <!-- PASSWORD -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" required>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Masukkan ulang password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-accent btn-block">Daftar</button>
        </form>

        <!-- LOGIN -->
        <div class="auth-footer">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login di sini</a>
        </div>
    </main>
</div>
</div>
</body>
</html>