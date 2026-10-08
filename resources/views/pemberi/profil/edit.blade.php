@extends('pemberi.layout')

@section('title', 'Edit Profil')

@section('content')
<style>
    .form-card { background: white; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 2px 8px rgba(0,0,0,0.02); max-width: 800px; padding: 2.5rem; }
    .page-title { font-family: 'Manrope', sans-serif; font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--color-ink); }
    .page-desc { color: var(--color-ink-soft); font-size: 0.9rem; margin-bottom: 2rem; }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-ink); margin-bottom: 0.5rem; }
    
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--color-ink); transition: 0.2s; }
    .form-control:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(85, 180, 234, 0.1); }
    
    .form-control.is-invalid { border-color: #EF4444; background-color: #FEF2F2; }
    .invalid-feedback { color: #DC2626; font-size: 0.8rem; font-weight: 500; margin-top: 0.4rem; display: flex; align-items: center; gap: 4px; }
    
    .btn-submit { background-color: var(--color-primary); color: white; padding: 0.8rem 2rem; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .btn-submit:hover { background-color: var(--color-primary-dark); }
</style>

<div class="form-card">
    <h2 class="page-title">Edit Informasi Profil</h2>
    <p class="page-desc">Perbarui data dirimu agar pekerja lebih mudah mengenalimu.</p>

    <!-- Pastikan enctype="multipart/form-data" tetap ada untuk upload file foto -->
    <form action="{{ route('pemberi.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}">
                @error('nama') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="no_telpon" class="form-control @error('no_telpon') is-invalid @enderror" value="{{ old('no_telpon', $user->no_telpon) }}">
                @error('no_telpon') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
            @error('email') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" style="min-height: 80px;">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Foto Profil (Opsional, maks 2MB)</label>
            <input type="file" name="foto_profil" class="form-control @error('foto_profil') is-invalid @enderror" accept=".jpg,.jpeg,.png">
            <div style="font-size: 0.8rem; color: var(--color-ink-soft); margin-top: 0.25rem;">Biarkan kosong jika tidak ingin mengubah foto.</div>
            @error('foto_profil') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
        </div>

        <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 2rem 0;">
        
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">Ubah Password (Opsional)</h3>
        <p style="font-size: 0.85rem; color: var(--color-ink-soft); margin-bottom: 1rem;">Biarkan kedua kolom di bawah ini kosong jika Anda tidak ingin mengubah password akun Anda.</p>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter (kombinasi huruf & angka)">
                @error('password') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password baru">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="{{ route('pemberi.profil.show') }}" class="btn-submit" style="background: white; border: 1px solid var(--color-border); color: var(--color-ink); text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection