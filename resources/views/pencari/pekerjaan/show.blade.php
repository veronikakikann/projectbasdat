@extends('pencari.layout')

@section('title', 'Detail Lowongan')

@section('content')

<style>
    .page-header-detail {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .job-title-large {
        font-family: 'Manrope', sans-serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 0.5rem;
    }

    .job-employer {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
        margin-bottom: 0.8rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-tersedia {
        background: #D1FAE5;
        color: #047857;
    }

    .status-penuh {
        background: #FEF3C7;
        color: #B45309;
    }

    .status-sedang_dikerjakan {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .status-selesai {
        background: #E0E7FF;
        color: #4338CA;
    }

    .status-ditutup {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-primary {
        background: var(--color-primary);
        color: white;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-primary:hover {
        background: var(--color-primary-dark);
    }

    .btn-outline {
        border: 1px solid var(--color-border);
        background: white;
        color: var(--color-ink);
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: 0.2s;
    }

    .btn-outline:hover {
        background: #F8FAFC;
    }

    .btn-proof {
        background: #16A34A;
        color: white;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: 0.2s;
    }

    .btn-proof:hover {
        background: #15803D;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 1rem;
    }

    .content-text {
        color: var(--color-ink);
        line-height: 1.7;
        font-size: 0.95rem;
        white-space: pre-line;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list li {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid var(--color-border);
        font-size: 0.9rem;
    }

    .info-list li:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--color-ink-soft);
    }

    .info-value {
        color: var(--color-ink);
        font-weight: 700;
        text-align: right;
    }

    .apply-card {
        background: #FFF7ED;
        border: 1px solid #FED7AA;
    }

    .apply-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .apply-desc {
        color: var(--color-ink-soft);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .alert-success {
        background: #D1FAE5;
        color: #047857;
        border: 1px solid #A7F3D0;
    }

    .alert-warning {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .alert-info {
        background: #DBEAFE;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .employer-contact {
        background: #F7FBFF;
        border: 1px solid #DCEEF9;
    }

    .employer-contact-title {
        color: #55B4EA;
        font-size: 0.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .employer-contact-name {
        color: var(--color-ink);
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .employer-contact-list {
        display: grid;
        gap: 0.75rem;
    }

    .employer-contact-item {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        font-size: 0.9rem;
    }

    .employer-contact-label {
        min-width: 90px;
        color: var(--color-ink-soft);
    }

    .employer-contact-value {
        color: var(--color-ink);
        font-weight: 600;
        word-break: break-word;
    }

    @media (max-width: 850px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .page-header-detail {
            flex-direction: column;
        }

        .header-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .employer-contact-item {
            flex-direction: column;
            gap: 0.15rem;
        }

        .employer-contact-label {
            min-width: auto;
        }
    }
</style>

@php
$statusClass = match ($pekerjaan->status_pekerjaan) {
'tersedia' => 'status-tersedia',
'penuh' => 'status-penuh',
'sedang_dikerjakan' => 'status-sedang_dikerjakan',
'selesai' => 'status-selesai',
'ditutup' => 'status-ditutup',
default => 'status-tersedia',
};


$statusLabel = match ($pekerjaan->status_pekerjaan) {
    'tersedia' => 'Tersedia',
    'penuh' => 'Penuh',
    'sedang_dikerjakan' => 'Sedang Dikerjakan',
    'selesai' => 'Selesai',
    'ditutup' => 'Ditutup',
    default => $pekerjaan->status_pekerjaan,
};

$bisaUploadBukti =
    $lamaranSaya
    && $lamaranSaya->status_lamaran === 'diterima'
    && $pekerjaan->status_pekerjaan === 'sedang_dikerjakan';


@endphp

<div class="page-header-detail">


<div>

    <h1 class="job-title-large">
        {{ $pekerjaan->nama_pekerjaan }}
    </h1>

    <div class="job-employer">
        Dibuat oleh
        <strong>
            {{ $pekerjaan->pemberiKerja->nama ?? 'Pemberi Kerja' }}
        </strong>
    </div>

    <span class="status-badge {{ $statusClass }}">
        {{ $statusLabel }}
    </span>

</div>

<div class="header-actions">

    @if($bisaUploadBukti)

        <a
            href="{{ route('pencari.bukti.create', ['lamaran' => $lamaranSaya->id_lamaran]) }}"
            class="btn-proof"
        >
            📤 Upload Bukti Kerja
        </a>

    @endif

    <a
        href="{{ route('pencari.cari-pekerjaan') }}"
        class="btn-outline"
    >
        Kembali
    </a>

</div>


</div>

<div class="detail-grid">


<div>

    <div class="card">

        <h3 class="section-title">
            Deskripsi Pekerjaan
        </h3>

        <div class="content-text">
            {{ $pekerjaan->deskripsi }}
        </div>

    </div>

    <div class="card">

        <h3 class="section-title">
            Persyaratan
        </h3>

        <div class="content-text">
            {{ $pekerjaan->persyaratan ?? 'Tidak ada persyaratan khusus.' }}
        </div>

    </div>

    <div class="card">

        <h3 class="section-title">
            Lokasi Pekerjaan
        </h3>

        <div class="content-text">
            {{ $pekerjaan->lokasi }}
        </div>

    </div>

    @if(
        $lamaranSaya
        && in_array(
            $lamaranSaya->status_lamaran,
            ['diterima', 'selesai'],
            true
        )
        && $pekerjaan->pemberiKerja
    )

        <div class="card employer-contact">

            <div class="employer-contact-title">
                Kontak Pemberi Kerja
            </div>

            <div class="employer-contact-name">
                {{ $pekerjaan->pemberiKerja->nama }}
            </div>

            <div class="employer-contact-list">

                @if($pekerjaan->pemberiKerja->email)

                    <div class="employer-contact-item">

                        <div class="employer-contact-label">
                            Email
                        </div>

                        <div class="employer-contact-value">
                            {{ $pekerjaan->pemberiKerja->email }}
                        </div>

                    </div>

                @endif

                @if($pekerjaan->pemberiKerja->no_telpon)

                    <div class="employer-contact-item">

                        <div class="employer-contact-label">
                            No. Telepon
                        </div>

                        <div class="employer-contact-value">
                            {{ $pekerjaan->pemberiKerja->no_telpon }}
                        </div>

                    </div>

                @endif

                @if($pekerjaan->pemberiKerja->alamat)

                    <div class="employer-contact-item">

                        <div class="employer-contact-label">
                            Alamat
                        </div>

                        <div class="employer-contact-value">
                            {{ $pekerjaan->pemberiKerja->alamat }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

<div>

    <div class="card">

        <h3 class="section-title">
            Ringkasan Lowongan
        </h3>

        <ul class="info-list">

            <li>
                <span class="info-label">
                    Keahlian
                </span>

                <span class="info-value">
                    {{ $pekerjaan->keahlian->nama_keahlian ?? '-' }}
                </span>
            </li>

            <li>
                <span class="info-label">
                    Upah
                </span>

                <span
                    class="info-value"
                    style="color:var(--color-primary-dark);"
                >
                    Rp {{ number_format($pekerjaan->upah, 0, ',', '.') }}
                </span>
            </li>

            <li>
                <span class="info-label">
                    Tanggal Pengerjaan
                </span>

                <span class="info-value">
                    {{ \Carbon\Carbon::parse($pekerjaan->tanggal_pengerjaan)->locale('id')->translatedFormat('d F Y') }}
                </span>
            </li>

            <li>
                <span class="info-label">
                    Kebutuhan
                </span>

                <span class="info-value">
                    {{ $pekerjaan->jumlah_pekerja }} orang
                </span>
            </li>

            <li>
                <span class="info-label">
                    Sudah Diterima
                </span>

                <span class="info-value">
                    {{ $jumlahDiterima }}
                    /
                    {{ $pekerjaan->jumlah_pekerja }}
                </span>
            </li>

            <li>
                <span class="info-label">
                    Diposting
                </span>

                <span class="info-value">
                    {{ \Carbon\Carbon::parse($pekerjaan->tanggal_posting)->locale('id')->translatedFormat('d F Y, H:i') }}
                </span>
            </li>

            @if($lamaranSaya)

                <li>
                    <span class="info-label">
                        Status Lamaran
                    </span>

                    <span class="info-value">
                        {{ ucfirst($lamaranSaya->status_lamaran) }}
                    </span>
                </li>

            @endif

        </ul>

    </div>

    <div class="card apply-card">

        <div class="apply-title">
            Status Lamaran
        </div>

        @if(!$lamaranSaya)

            <div class="apply-desc">
                Pastikan kamu sudah membaca deskripsi dan persyaratan
                pekerjaan sebelum mengirim lamaran.
            </div>

            @if(
                $pekerjaan->status_pekerjaan === 'tersedia'
                && $jumlahDiterima < $pekerjaan->jumlah_pekerja
            )

                <form
                    action="{{ route('pencari.lamar', $pekerjaan->id_pekerjaan) }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn-primary"
                        style="width:100%;"
                    >
                        Lamar Sekarang
                    </button>

                </form>

            @elseif($jumlahDiterima >= $pekerjaan->jumlah_pekerja)

                <div class="alert alert-warning">
                    Kuota pekerja untuk pekerjaan ini sudah penuh.
                </div>

            @else

                <div class="alert alert-warning">
                    Lowongan ini sudah tidak menerima lamaran.
                </div>

            @endif

        @elseif($lamaranSaya->status_lamaran === 'menunggu')

            <div class="alert alert-warning">
                ⏳ Lamaran kamu masih menunggu keputusan dari Pemberi Kerja.
            </div>

        @elseif($lamaranSaya->status_lamaran === 'diterima')

            <div class="alert alert-success">
                ✅ Lamaran kamu diterima.
                Pemberi Kerja akan memulai pekerjaan sebelum kamu mengunggah bukti pengerjaan.
            </div>

            @if($bisaUploadBukti)

                <a
                    href="{{ route('pencari.bukti.create', ['lamaran' => $lamaranSaya->id_lamaran]) }}"
                    class="btn-proof"
                >
                    📤 Upload Bukti Kerja
                </a>

            @endif

        @elseif($lamaranSaya->status_lamaran === 'ditolak')

            <div class="alert alert-warning">
                Lamaran kamu ditolak oleh Pemberi Kerja.
            </div>

            @elseif($lamaranSaya->status_lamaran === 'selesai')

            <div class="alert alert-info">
                ✅ Pekerjaan ini sudah selesai.
                Informasi kontak Pemberi Kerja tetap tersedia.
            </div>

            <a
                href="{{ route('pencari.rating.form', $lamaranSaya->id_lamaran) }}"
                class="btn-proof"
                style="background:#F59E0B;"
            >
                ⭐ Beri Rating Pemberi Kerja
            </a>

        @endif

    </div>

</div>


</div>

@endsection