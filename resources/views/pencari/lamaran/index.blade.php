@extends('pencari.layout')

@section('title', 'Lamaran Saya')

@section('content')

<style>
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 0.4rem;
    }

    .page-desc {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .alert-success {
        background: #C6F6D5;
        border: 1px solid #48BB78;
        color: #2F855A;
    }

    .alert-error {
        background: #FED7D7;
        border: 1px solid #EF4444;
        color: #C53030;
    }

    .application-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .application-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: 0.2s;
    }

    .application-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .application-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .job-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--color-ink);
        text-decoration: none;
    }

    .job-title:hover {
        color: var(--color-primary);
    }

    .employer {
        color: var(--color-ink-soft);
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }

    .status-badge {
        display: inline-flex;
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .status-menunggu {
        background: #FEF3C7;
        color: #B45309;
    }

    .status-diterima {
        background: #D1FAE5;
        color: #047857;
    }

    .status-ditolak {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .status-selesai {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .application-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--color-border);
    }

    .info-label {
        display: block;
        color: var(--color-ink-soft);
        font-size: 0.8rem;
        margin-bottom: 0.3rem;
    }

    .info-value {
        color: var(--color-ink);
        font-size: 0.9rem;
        font-weight: 700;
    }

    .employer-contact {
        margin-top: 1.25rem;
        padding: 1.15rem 1.25rem;
        background: #F7FBFF;
        border: 1px solid #DCEEF9;
        border-radius: 10px;
    }

    .employer-contact-title {
        color: #55B4EA;
        font-size: 0.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .employer-contact-name {
        color: var(--color-ink);
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
    }

    .employer-contact-info {
        display: grid;
        gap: 0.45rem;
        color: var(--color-ink-soft);
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .employer-contact-info strong {
        color: var(--color-ink);
    }

    .application-footer {
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid var(--color-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        background: var(--color-primary);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .btn-detail:hover {
        background: var(--color-primary-dark);
    }

    .btn-proof {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        background: #16A34A;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .btn-proof:hover {
        background: #15803D;
    }

    .btn-rating {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        background: #F59E0B;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .btn-rating:hover {
        background: #D97706;
    }

    .btn-cancel {
        padding: 0.6rem 1rem;
        border: 1px solid #EF4444;
        background: white;
        color: #EF4444;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #FEF2F2;
    }

    .empty-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        text-align: center;
        padding: 4rem 1.5rem;
        color: var(--color-ink-soft);
    }

    .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
    }

    .empty-title {
        color: var(--color-ink);
        font-weight: 800;
        font-size: 1rem;
        margin-bottom: 0.4rem;
    }

    .empty-text {
        font-size: 0.9rem;
        margin-bottom: 1.25rem;
    }

    .btn-search {
        display: inline-flex;
        padding: 0.7rem 1.1rem;
        background: var(--color-primary);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.85rem;
    }

    @media (max-width: 750px) {
        .application-top {
            flex-direction: column;
        }

        .application-info {
            grid-template-columns: 1fr;
        }

        .application-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-detail,
        .btn-proof,
        .btn-rating {
            justify-content: center;
        }
    }
</style>

<div class="page-header">

```
<h1 class="page-title">
    Lamaran Saya
</h1>

<p class="page-desc">
    Lihat seluruh lamaran pekerjaan yang pernah kamu kirim.
</p>
```

</div>

@if(session('success')) <div class="alert alert-success">
✅ {{ session('success') }} </div>
@endif

@if(session('error')) <div class="alert alert-error">
⚠️ {{ session('error') }} </div>
@endif

@if($lamaran->isNotEmpty())

```
<div class="application-list">

    @foreach($lamaran as $item)

        <div class="application-card">

            <div class="application-top">

                <div>

                    <a
                        href="{{ route('pekerjaan.show', $item->pekerjaan->id_pekerjaan) }}"
                        class="job-title"
                    >
                        {{ $item->pekerjaan->nama_pekerjaan }}
                    </a>

                    <div class="employer">
                        {{ $item->pekerjaan->pemberiKerja->nama ?? 'Pemberi Kerja' }}
                    </div>

                </div>

                @if($item->status_lamaran === 'menunggu')

                    <span class="status-badge status-menunggu">
                        Menunggu
                    </span>

                @elseif($item->status_lamaran === 'diterima')

                    <span class="status-badge status-diterima">
                        Diterima
                    </span>

                @elseif($item->status_lamaran === 'ditolak')

                    <span class="status-badge status-ditolak">
                        Ditolak
                    </span>

                @elseif($item->status_lamaran === 'selesai')

                    <span class="status-badge status-selesai">
                        Selesai
                    </span>

                @else

                    <span class="status-badge">
                        {{ $item->status_lamaran }}
                    </span>

                @endif

            </div>

            <div class="application-info">

                <div>

                    <span class="info-label">
                        Lokasi
                    </span>

                    <div class="info-value">
                        {{ $item->pekerjaan->lokasi ?? '-' }}
                    </div>

                </div>

                <div>

                    <span class="info-label">
                        Tanggal Melamar
                    </span>

                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($item->tanggal_submit)->locale('id')->translatedFormat('d F Y, H:i') }}
                    </div>

                </div>

                <div>

                    <span class="info-label">
                        Keahlian
                    </span>

                    <div class="info-value">
                        {{ $item->pekerjaan->keahlian->nama_keahlian ?? '-' }}
                    </div>

                </div>

            </div>

            @if(
                in_array(
                    $item->status_lamaran,
                    ['diterima', 'selesai'],
                    true
                )
            )

                @php
                    $pemberi = $item->pekerjaan->pemberiKerja ?? null;
                @endphp

                @if($pemberi)

                    <div class="employer-contact">

                        <div class="employer-contact-title">
                            INFORMASI PEMBERI KERJA
                        </div>

                        <div class="employer-contact-name">
                            {{ $pemberi->nama }}
                        </div>

                        <div class="employer-contact-info">

                            @if($pemberi->alamat)
                                <div>
                                    <strong>Alamat:</strong>
                                    {{ $pemberi->alamat }}
                                </div>
                            @endif

                            @if($pemberi->no_telpon)
                                <div>
                                    <strong>No. Telepon:</strong>
                                    {{ $pemberi->no_telpon }}
                                </div>
                            @endif

                            @if($pemberi->email)
                                <div>
                                    <strong>Email:</strong>
                                    {{ $pemberi->email }}
                                </div>
                            @endif

                        </div>

                    </div>

                @endif

            @endif

            <div class="application-footer">

                <div style="color:var(--color-ink-soft); font-size:.8rem;">

                    Diajukan
                    {{ \Carbon\Carbon::parse($item->tanggal_submit)->locale('id')->diffForHumans() }}

                </div>

                <div style="display:flex; gap:.6rem; align-items:center; flex-wrap:wrap;">

                    <a
                        href="{{ route('pekerjaan.show', $item->pekerjaan->id_pekerjaan) }}"
                        class="btn-detail"
                    >
                        Lihat Detail
                    </a>

                    @if(
                        $item->status_lamaran === 'diterima'
                        && $item->pekerjaan->status_pekerjaan === 'sedang_dikerjakan'
                    )

                        <a
                            href="{{ route('pencari.bukti.create', $item->id_lamaran) }}"
                            class="btn-proof"
                        >
                            📤 Upload Bukti Kerja
                        </a>

                    @endif

                    @if($item->status_lamaran === 'selesai')

                        <a
                            href="{{ route('pencari.rating.form', $item->id_lamaran) }}"
                            class="btn-rating"
                        >
                            ⭐ Beri Rating Pemberi Kerja
                        </a>

                    @endif

                    @if($item->status_lamaran === 'menunggu')

                        <form
                            action="{{ route('pencari.lamaran.batal', $item->id_lamaran) }}"
                            method="POST"
                            style="margin:0;"
                            onsubmit="return confirm('Yakin ingin membatalkan lamaran ini?');"
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn-cancel"
                            >
                                Batalkan
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    @endforeach

</div>
```

@else

```
<div class="empty-card">

    <div class="empty-icon">
        📄
    </div>

    <div class="empty-title">
        Belum ada lamaran
    </div>

    <div class="empty-text">
        Kamu belum mengirim lamaran ke pekerjaan apa pun.
    </div>

    <a
        href="{{ route('pencari.cari-pekerjaan') }}"
        class="btn-search"
    >
        Cari Lowongan
    </a>

</div>
```

@endif

@endsection
