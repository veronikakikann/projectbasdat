@extends('pencari.layout')

@section('title', 'Profil Saya')

@section('content')

<style>
    .profile-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .avatar-large {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #FDE2D7;
        color: var(--color-primary-dark);
        font-size: 2.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 4px solid white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
    }

    .profile-main-name {
        font-family: 'Manrope', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 0.4rem;
    }

    .profile-email {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
    }

    .badge-status {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-terverifikasi {
        background: #D1FAE5;
        color: #047857;
    }

    .badge-menunggu {
        background: #FEF3C7;
        color: #B45309;
    }

    .badge-ditolak {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .badge-aktif {
        background: #D1FAE5;
        color: #047857;
    }

    .badge-nonaktif {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-group {
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--color-ink-soft);
        margin-bottom: 0.35rem;
        display: block;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--color-ink);
        font-weight: 600;
        line-height: 1.5;
        word-break: break-word;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--color-primary);
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-edit:hover {
        background: var(--color-primary-dark);
    }

    .side-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .side-item {
        padding: 1rem;
        background: #F8FAFC;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .side-item:last-child {
        margin-bottom: 0;
    }

    .side-item-label {
        display: block;
        color: var(--color-ink-soft);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .side-item-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--color-ink);
        word-break: break-word;
    }

    .coordinate-box {
        background: #F8FAFC;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        padding: 1rem;
    }

    .coordinate-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.65rem 0;
        border-bottom: 1px solid var(--color-border);
    }

    .coordinate-row:first-child {
        padding-top: 0;
    }

    .coordinate-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .coordinate-label {
        color: var(--color-ink-soft);
        font-size: 0.85rem;
    }

    .coordinate-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--color-ink);
        text-align: right;
    }

    .success-alert {
        background: #C6F6D5;
        border: 1px solid #48BB78;
        color: #2F855A;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    @media (max-width: 900px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if (session('success'))
    <div class="success-alert">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="profile-layout">

    <!-- KOLOM KIRI -->
    <div>

        <div class="card">

            <div class="profile-header">

                <div class="avatar-large">
                    {{ strtoupper(substr($pencari->nama ?? 'P', 0, 1)) }}
                </div>

                <div>

                    <div class="profile-main-name">
                        {{ $pencari->nama }}
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">

                        @if($pencari->status_verifikasi === 'terverifikasi')
                            <span class="badge-status badge-terverifikasi">
                                Terverifikasi
                            </span>
                        @elseif($pencari->status_verifikasi === 'ditolak')
                            <span class="badge-status badge-ditolak">
                                Ditolak
                            </span>
                        @else
                            <span class="badge-status badge-menunggu">
                                Menunggu
                            </span>
                        @endif

                        <span style="color: var(--color-ink-soft); font-size: 0.85rem;">
                            Bergabung sejak
                            {{ \Carbon\Carbon::parse($pencari->tanggal_daftar)->translatedFormat('F Y') }}
                        </span>

                    </div>

                </div>

            </div>

            <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 0 0 1.5rem 0;">

            <div class="info-grid">

                <div class="info-group">
                    <span class="info-label">
                        Email
                    </span>

                    <div class="info-value">
                        {{ $pencari->email }}
                    </div>
                </div>

                <div class="info-group">
                    <span class="info-label">
                        Nomor Telepon
                    </span>

                    <div class="info-value">
                        {{ $pencari->no_telpon ?? '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <span class="info-label">
                        NIK KTP
                    </span>

                    <div class="info-value">
                        {{ $pencari->nik ?? 'Belum dilengkapi' }}
                    </div>
                </div>

                <div class="info-group">
                    <span class="info-label">
                        Status Akun
                    </span>

                    <div class="info-value">
                        @if($pencari->status_akun === 'aktif')
                            <span class="badge-status badge-aktif">
                                Aktif
                            </span>
                        @else
                            <span class="badge-status badge-nonaktif">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="info-group">

                <span class="info-label">
                    Alamat Lengkap
                </span>

                <div class="info-value">
                    {{ $pencari->alamat ?? 'Belum ada alamat.' }}
                </div>

            </div>

            <div style="margin-top: 2rem;">

                <a
                    href="{{ route('pencari.profil.edit') }}"
                    class="btn-edit"
                >
                    <svg
                        viewBox="0 0 24 24"
                        width="16"
                        height="16"
                        stroke="currentColor"
                        stroke-width="2"
                        fill="none"
                    >
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>

                    Edit Profil
                </a>

            </div>

        </div>

    </div>


    <!-- KOLOM KANAN -->
    <div>

        <div class="card">

            <h3 class="side-title">

                <svg
                    viewBox="0 0 24 24"
                    width="20"
                    height="20"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                >
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>

                Informasi Akun

            </h3>

            <div class="side-item">

                <span class="side-item-label">
                    Status Verifikasi
                </span>

                <span class="side-item-value">
                    {{ ucfirst($pencari->status_verifikasi) }}
                </span>

            </div>

            <div class="side-item">

                <span class="side-item-label">
                    Status Akun
                </span>

                <span class="side-item-value">
                    {{ ucfirst($pencari->status_akun) }}
                </span>

            </div>

            <div class="side-item">

                <span class="side-item-label">
                    NIK
                </span>

                <span class="side-item-value">
                    {{ $pencari->nik }}
                </span>

            </div>

        </div>


        <div class="card">

            <h3 class="side-title">

                <svg
                    viewBox="0 0 24 24"
                    width="20"
                    height="20"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                >
                    <path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>

                Lokasi Saya

            </h3>

            <div class="coordinate-box">

                <div class="coordinate-row">

                    <span class="coordinate-label">
                        Latitude
                    </span>

                    <span class="coordinate-value">
                        {{ $pencari->latitude ?? 'Belum diatur' }}
                    </span>

                </div>

                <div class="coordinate-row">

                    <span class="coordinate-label">
                        Longitude
                    </span>

                    <span class="coordinate-value">
                        {{ $pencari->longitude ?? 'Belum diatur' }}
                    </span>

                </div>

            </div>

            <p style="font-size: 0.8rem; color: var(--color-ink-soft); margin-top: 0.8rem; line-height: 1.5;">
                Koordinat digunakan untuk mencocokkan lowongan
                berdasarkan jarak lokasi.
            </p>

        </div>

    </div>

</div>

@endsection