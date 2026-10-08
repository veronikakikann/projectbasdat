@extends('pemberi.layout')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    .card { background: white; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 2px 8px rgba(0,0,0,0.02); padding: 2rem; margin-bottom: 1.5rem; }
    
    .profile-header { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; }
    .avatar-large {
        width: 90px; height: 90px; border-radius: 50%; background: #E0F2FE;
        color: var(--color-primary-dark); font-size: 2.5rem; font-weight: 800; display: flex;
        align-items: center; justify-content: center; overflow: hidden; border: 4px solid white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .avatar-large img { width: 100%; height: 100%; object-fit: cover; }
    
    .profile-name { font-family: 'Manrope', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--color-ink); margin-bottom: 0.25rem; }
    
    .badge-status { display: inline-flex; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-terverifikasi { background: #D1FAE5; color: #047857; }
    .badge-menunggu { background: #FEF3C7; color: #B45309; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .info-group { margin-bottom: 1rem; }
    .info-label { font-size: 0.85rem; font-weight: 600; color: var(--color-ink-soft); margin-bottom: 0.35rem; display: block; }
    .info-value { font-size: 0.95rem; color: var(--color-ink); font-weight: 600; line-height: 1.5; }
    
    .btn-edit { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-primary); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: 0.2s; }
    .btn-edit:hover { background: var(--color-primary-dark); }

    .rating-stat { text-align: center; padding: 1.5rem; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--color-border); }
    .rating-score { font-size: 2.5rem; font-weight: 800; color: #F59E0B; line-height: 1; margin-bottom: 0.5rem; }
    .review-item { padding: 1rem 0; border-bottom: 1px solid var(--color-border); }
    .review-item:last-child { border-bottom: none; }
</style>

@if (session('success'))
    <div style="background: #C6F6D5; border: 1px solid #48BB78; color: #2F855A; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">✅ {{ session('success') }}</div>
@endif

<div class="profile-layout">
    <!-- Kolom Kiri: Info Profil Utama -->
    <div>
        <div class="card">
            <div class="profile-header">
                <div class="avatar-large">
                    <!-- Memanggil gambar lewat route khusus karena tersimpan private -->
                    @if($user->foto_profil)
                        <img src="{{ route('pemberi.profil.foto') }}" alt="Foto Profil">
                    @else
                        {{ substr($user->nama, 0, 1) }}
                    @endif
                </div>
                <div>
                    <div class="profile-name">{{ $user->nama }}</div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <span class="badge-status {{ $user->status_verifikasi == 'terverifikasi' ? 'badge-terverifikasi' : 'badge-menunggu' }}">
                            {{ $user->status_verifikasi }}
                        </span>
                        <span style="color: var(--color-ink-soft); font-size: 0.85rem;">Bergabung sejak {{ \Carbon\Carbon::parse($user->tanggal_daftar)->translatedFormat('F Y') }}</span>
                    </div>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 0 0 1.5rem 0;">

            <div class="info-grid">
                <div class="info-group">
                    <span class="info-label">Email</span>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-group">
                    <span class="info-label">Nomor Telepon</span>
                    <div class="info-value">{{ $user->no_telpon ?? '-' }}</div>
                </div>
                <div class="info-group">
                    <span class="info-label">NIK KTP</span>
                    <div class="info-value">{{ $user->nik ?? 'Belum dilengkapi' }}</div>
                </div>
                <div class="info-group">
                    <span class="info-label">Status Akun</span>
                    <div class="info-value" style="text-transform: capitalize;">{{ $user->status_akun }}</div>
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Alamat Lengkap</span>
                <div class="info-value">{{ $user->alamat ?? 'Belum ada alamat.' }}</div>
            </div>

            <div style="margin-top: 2rem;">
                <a href="{{ route('pemberi.profil.edit') }}" class="btn-edit">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Rating & Ulasan -->
    <div>
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Reputasi Anda
            </h3>
            
            <div class="rating-stat">
                <div class="rating-score">{{ $stat && $stat->rata ? number_format($stat->rata, 1) : '0.0' }}</div>
                <div style="color: var(--color-ink-soft); font-size: 0.9rem;">
                    Berdasarkan <strong>{{ $stat->jumlah ?? 0 }}</strong> ulasan pekerja
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--color-ink-soft);">Ulasan Terbaru</h4>
                @forelse($ulasan as $u)
                    <div class="review-item">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-weight: 700; font-size: 0.9rem;">{{ $u->lamaran->pencariKerja->nama ?? 'Pekerja' }}</span>
                            <span style="color: #F59E0B; font-weight: 700; font-size: 0.85rem;">★ {{ $u->skor }}</span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--color-ink); line-height: 1.4;">"{{ $u->komentar }}"</div>
                        <div style="font-size: 0.75rem; color: var(--color-ink-soft); margin-top: 4px;">{{ \Carbon\Carbon::parse($u->tanggal_rating)->diffForHumans() }}</div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--color-ink-soft); font-size: 0.85rem; padding: 1rem 0;">
                        Belum ada ulasan dari pekerja.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection