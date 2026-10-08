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

    .btn-disabled {
        background: #E2E8F0;
        color: #64748B;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: none;
        cursor: not-allowed;
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
        transition: 0.2s;
    }

    .btn-outline:hover {
        background: #F8FAFC;
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

    @media (max-width: 850px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .page-header-detail {
            flex-direction: column;
        }
    }
</style>


<!-- HEADER -->

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

        <span class="status-badge status-tersedia">
            Tersedia
        </span>

    </div>

    <div style="display:flex; gap:.75rem;">

        <a
            href="{{ route('pencari.cari-pekerjaan') }}"
            class="btn-outline"
        >
            Kembali
        </a>

    </div>

</div>


<!-- DETAIL -->

<div class="detail-grid">

    <!-- KOLOM KIRI -->

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

    </div>


    <!-- KOLOM KANAN -->

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

            </ul>

        </div>


        <!-- LAMAR -->

        <div class="card apply-card">

            <div class="apply-title">
                Lamar Pekerjaan
            </div>

            <div class="apply-desc">
                Pastikan kamu sudah membaca deskripsi dan persyaratan
                pekerjaan sebelum mengirim lamaran.
            </div>


            @if($sudahMelamar)

                <div class="alert alert-success">
                    ✅ Kamu sudah melamar pekerjaan ini.
                </div>

            @elseif($jumlahDiterima >= $pekerjaan->jumlah_pekerja)

                <div class="alert alert-warning">
                    Kuota pekerja untuk pekerjaan ini sudah penuh.
                </div>

            @else

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

            @endif

        </div>

    </div>

</div>

@endsection