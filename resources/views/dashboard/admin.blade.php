@extends('admin.layout')
@section('title', 'Beranda')
@section('content')
@php
    $totalMenunggu = $menungguPencari + $menungguPemberi + $menungguKeahlian;
@endphp
<style>
    .dashboard-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start; }
    /* Welcome Banner */
    .welcome-banner { background: linear-gradient(135deg, #3D91C7 0%, #2B6C9B 100%); border-radius: 12px; padding: 2.5rem 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(61, 145, 199, 0.25); }
    .welcome-title { font-family: 'Manrope', sans-serif; font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; }
    .welcome-desc { font-size: 1rem; opacity: 0.9; line-height: 1.5; margin-bottom: 1.5rem; max-width: 80%; }
    .btn-yellow { background-color: #FEF08A; color: #854D0E; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.2s; border: none; }
    .btn-yellow:hover { background-color: #FDE047; transform: translateY(-2px); }
    /* Header section */
    .section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem; }
    .section-title { font-family: 'Manrope', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--color-ink); }
    .section-link { font-size: 0.85rem; font-weight: 600; color: var(--color-primary); text-decoration: none; }
    .section-link:hover { text-decoration: underline; }
    /* Grid statistik */
    .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center; }
    .stat-num { font-size: 2.5rem; font-weight: 800; color: var(--color-ink); line-height: 1; margin-bottom: 0.5rem; }
    .stat-label { font-size: 0.9rem; color: var(--color-ink-soft); font-weight: 600; }
    /* Kartu samping */
    .side-card { background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
    .side-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .list-item { display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 8px; transition: 0.2s; text-decoration: none; border: 1px solid transparent; }
    .list-item:hover { background: #F8FAFC; border-color: var(--color-border); }
    .item-icon { width: 40px; height: 40px; border-radius: 50%; background: #E0F2FE; color: var(--color-primary-dark); font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; }
    .item-icon.is-warn { background: #FEF3C7; color: #B45309; }
    .item-title { font-weight: 700; color: var(--color-ink); font-size: 0.95rem; margin-bottom: 0.2rem; }
    .item-desc { font-size: 0.8rem; color: var(--color-ink-soft); }
    .empty-box { text-align: center; padding: 2rem 0; color: var(--color-ink-soft); font-size: 0.9rem; }
    /* Link cepat */
    .quick-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; }
    .quick-link { background: white; border: 1px solid var(--color-border); border-radius: 12px; padding: 1rem 1.1rem; text-decoration: none; color: var(--color-ink); font-weight: 700; font-size: 0.9rem; transition: 0.2s; }
    .quick-link:hover { border-color: var(--color-primary); transform: translateY(-2px); }
    .quick-link small { display: block; font-weight: 500; color: var(--color-ink-soft); font-size: 0.78rem; margin-top: 0.15rem; }
    @media (max-width: 1100px) { .dashboard-layout { grid-template-columns: 1fr; } }
    @media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } .welcome-desc { max-width: 100%; } .welcome-title { font-size: 1.5rem; } }
</style>

<div class="dashboard-layout">
    <!-- Sisi Kiri (Utama) -->
    <div>
        <div class="welcome-banner">
            <h1 class="welcome-title">Hai, {{ session('user_name', 'Admin') }}!</h1>
            <p class="welcome-desc">
                @if($totalMenunggu > 0)
                    Ada <strong>{{ $totalMenunggu }} data</strong> yang menunggu tindakanmu. Verifikasi sekarang agar pengguna bisa mulai memakai platform.
                @else
                    Semua data sudah terverifikasi. Tidak ada yang menunggu tindakan saat ini.
                @endif
            </p>
            <a href="{{ route('admin.verifikasi-keahlian') }}" class="btn-yellow">Verifikasi Keahlian</a>
        </div>

        <div class="section-header">
            <h2 class="section-title">Ringkasan Platform</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num">{{ $jumlahPencari }}</div>
                <div class="stat-label">Total Pencari Kerja</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ $jumlahPemberi }}</div>
                <div class="stat-label">Total Pemberi Kerja</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ $jumlahPekerjaan }}</div>
                <div class="stat-label">Total Lowongan</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ $jumlahLamaran }}</div>
                <div class="stat-label">Total Lamaran</div>
            </div>
        </div>

        <div class="section-header">
            <h2 class="section-title">Pantau Transaksi</h2>
        </div>
        <div class="quick-grid">
            <a href="{{ route('admin.transaksi.index', 'pekerjaan') }}" class="quick-link">Pekerjaan<small>Data lowongan</small></a>
            <a href="{{ route('admin.transaksi.index', 'lamaran') }}" class="quick-link">Lamaran<small>Proses melamar</small></a>
            <a href="{{ route('admin.transaksi.index', 'bukti') }}" class="quick-link">Bukti Penyelesaian<small>Hasil kerja</small></a>
            <a href="{{ route('admin.transaksi.index', 'rating') }}" class="quick-link">Rating<small>Penilaian</small></a>
            <a href="{{ route('admin.transaksi.index', 'notifikasi') }}" class="quick-link">Notifikasi<small>Pesan sistem</small></a>
        </div>
    </div>

<<<<<<< HEAD
    <!-- Sisi Kanan -->
    <div>
        <div class="side-card">
            <div class="section-header" style="margin-bottom: 1.5rem;">
                <h2 class="section-title" style="font-size: 1.1rem;">Perlu Tindakan</h2>
            </div>
            <div class="side-list">
                @if($totalMenunggu > 0)
                    @if($menungguPencari > 0)
                        <a href="{{ route('pencari_kerja.index') }}" class="list-item">
                            <div class="item-icon is-warn">{{ $menungguPencari }}</div>
                            <div>
                                <div class="item-title">Pencari Kerja</div>
                                <div class="item-desc">Menunggu verifikasi akun</div>
                            </div>
                        </a>
                    @endif
                    @if($menungguPemberi > 0)
                        <a href="{{ route('pemberi_kerja.index') }}" class="list-item">
                            <div class="item-icon is-warn">{{ $menungguPemberi }}</div>
                            <div>
                                <div class="item-title">Pemberi Kerja</div>
                                <div class="item-desc">Menunggu verifikasi akun</div>
                            </div>
                        </a>
                    @endif
                    @if($menungguKeahlian > 0)
                        <a href="{{ route('admin.verifikasi-keahlian') }}" class="list-item">
                            <div class="item-icon is-warn">{{ $menungguKeahlian }}</div>
                            <div>
                                <div class="item-title">Keahlian</div>
                                <div class="item-desc">Menunggu verifikasi bukti</div>
                            </div>
                        </a>
                    @endif
                @else
                    <div class="empty-box">
                        <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 0.5rem; opacity: 0.5;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <div>Semua sudah beres.</div>
                    </div>
                @endif
            </div>
=======

    {{-- ISI DASHBOARD --}}

    <div class="container">

        <h2>Menu Admin</h2>


        <div class="menu">


            {{-- 1. VERIFIKASI PENCARI --}}

            <div class="card">

                <h3>
                    Verifikasi Pencari Kerja
                </h3>

                <p>
                    Memeriksa dan memverifikasi
                    data pencari kerja yang
                    melakukan registrasi.
                </p>

                <a href="{{ route('pencari_kerja.index') }}" class="btn">Verifikasi</a>

            </div>


            {{-- 2. VERIFIKASI PEMBERI --}}

            <div class="card">

                <h3>
                    Verifikasi Pemberi Kerja
                </h3>

                <p>
                    Memeriksa dan memverifikasi
                    data pemberi kerja yang
                    melakukan registrasi.
                </p>

                <a href="{{ route('pemberi_kerja.index') }}" class="btn">Verifikasi</a>

            </div>


            {{-- 3. VERIFIKASI KEAHLIAN --}}

            <div class="card">

                <h3>
                    Verifikasi Keahlian
                </h3>

                <p>
                    Memeriksa dan memverifikasi
                    keahlian yang diajukan
                    oleh pencari kerja.
                </p>

                <a href="{{ route('keahlian_pencari_kerja.index') }}" class="btn">Verifikasi</a>

            </div>


            {{-- 4. KELOLA AKUN --}}

            <div class="card">

                <h3>
                    Kelola Akun
                </h3>

                <p>
                    Mengelola status akun
                    pencari kerja dan
                    pemberi kerja.
                </p>

                <a
                    href="#"
                    class="btn"
                >
                    Kelola Akun
                </a>

            </div>


>>>>>>> d645792e339e551143d96b5239c8b5a09c802856
        </div>

        <div class="side-card" style="margin-top: 1.5rem;">
            <div class="section-header" style="margin-bottom: 1rem;">
                <h2 class="section-title" style="font-size: 1.1rem;">Data Keahlian</h2>
                <a href="{{ route('keahlian.index') }}" class="section-link">Kelola</a>
            </div>
            <div class="stat-num">{{ $jumlahKeahlian }}</div>
            <div class="stat-label">keahlian terdaftar di sistem</div>
        </div>
    </div>
</div>
@endsection