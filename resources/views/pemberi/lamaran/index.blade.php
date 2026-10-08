@extends('pemberi.layout')

@section('title', $pekerjaan ? 'Daftar Pelamar' : 'Semua Pelamar')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 0.5rem;
    }

    .quota-badge {
        display: inline-flex;
        align-items: center;
        background: #F8FAFC;
        border: 1px solid var(--color-border);
        padding: 0.4rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--color-ink-soft);
    }

    .quota-badge span {
        color: var(--color-primary-dark);
        margin-left: 6px;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        text-align: left;
        padding: 1.25rem 1.5rem;
        font-size: 0.8rem;
        color: var(--color-ink-soft);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--color-border);
        background-color: #F8FAFC;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        font-size: 0.95rem;
        color: var(--color-ink);
        border-bottom: 1px solid var(--color-border);
        vertical-align: top;
    }

    .modern-table tr:last-child td {
        border-bottom: none;
    }

    .modern-table tr:hover td {
        background-color: #F8FAFC;
    }

    /* Profil Pelamar */
    .applicant-profile {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .app-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #EBF8FF;
        color: var(--color-primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .app-name {
        font-weight: 700;
        color: var(--color-ink);
        margin-bottom: 0.2rem;
        display: block;
    }

    .app-rating {
        font-size: 0.8rem;
        color: var(--color-ink-soft);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .app-rating svg {
        width: 14px;
        height: 14px;
        color: #F59E0B;
        fill: currentColor;
    }

    /* Kontak pelamar setelah diterima */
    .applicant-contact {
        margin-top: 0.9rem;
        padding: 0.9rem 1rem;
        background: #F7FBFF;
        border: 1px solid #DCEEF9;
        border-radius: 9px;
    }

    .applicant-contact-title {
        color: #55B4EA;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 0.55rem;
    }

    .applicant-contact-info {
        display: grid;
        gap: 0.35rem;
        color: var(--color-ink-soft);
        font-size: 0.82rem;
        line-height: 1.45;
    }

    .applicant-contact-info strong {
        color: var(--color-ink);
    }

    /* Tags Keahlian */
    .skill-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .skill-tag {
        background: #F1F5F9;
        color: #475569;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #E2E8F0;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
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

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-terima {
        background: #10B981;
        color: white;
    }

    .btn-terima:hover {
        background: #059669;
    }

    .btn-tolak {
        background: white;
        border: 1px solid #EF4444;
        color: #EF4444;
    }

    .btn-tolak:hover {
        background: #FEF2F2;
    }

    .btn-disabled {
        background: #F1F5F9;
        color: #94A3B8;
        cursor: not-allowed;
    }

    @media (max-width: 1000px) {
        .table-card {
            overflow-x: auto;
        }

        .modern-table {
            min-width: 900px;
        }
    }

    @media (max-width: 750px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<div class="page-header">
    <div>
        @if($pekerjaan)
            <div style="color: var(--color-primary); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                Pelamar Lowongan
            </div>

            <h1 class="page-title">
                {{ $pekerjaan->nama_pekerjaan }}
            </h1>
        @else
            <h1 class="page-title">
                Semua Pelamar
            </h1>

            <p style="color: var(--color-ink-soft); font-size: 0.9rem;">
                Daftar seluruh pelamar di semua lowongan aktif Anda.
            </p>
        @endif
    </div>

    @if($pekerjaan)
        <div class="quota-badge">
            <svg
                viewBox="0 0 24 24"
                width="16"
                height="16"
                stroke="currentColor"
                stroke-width="2"
                fill="none"
                style="margin-right: 6px;"
            >
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>

            Kuota Terpenuhi:

            <span>
                {{ $diterima }} / {{ $pekerjaan->jumlah_pekerja }} Pekerja
            </span>
        </div>
    @endif
</div>


@if (session('success'))
    <div style="background: #C6F6D5; border: 1px solid #48BB78; color: #2F855A; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
        ✅ {{ session('success') }}
    </div>
@endif


@if (session('error'))
    <div style="background: #FED7D7; border: 1px solid #EF4444; color: #C53030; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
        ⚠️ {{ session('error') }}
    </div>
@endif


<div class="table-card">

    <table class="modern-table">

        <thead>
            <tr>
                <th>Profil Pelamar</th>

                @if(!$pekerjaan)
                    <th>Melamar Untuk</th>
                @endif

                <th>Keahlian Terverifikasi</th>
                <th>Waktu Melamar</th>
                <th>Status</th>
                <th style="text-align: right;">
                    Aksi
                </th>
            </tr>
        </thead>


        <tbody>

            @forelse($lamaran as $l)

                <tr>

                    {{-- PROFIL PELAMAR --}}
                    <td>

                        <div class="applicant-profile">

                            <div class="app-avatar">
                                {{ substr($l->pencariKerja->nama ?? 'U', 0, 1) }}
                            </div>

                            <div>

                                <span class="app-name">
                                    {{ $l->pencariKerja->nama ?? 'Pelamar Anonim' }}
                                </span>


                                <div class="app-rating">

                                    <svg viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>

                                    @if(isset($rating[$l->id_pencari]))

                                        <strong>
                                            {{ number_format($rating[$l->id_pencari]->rata, 1) }}
                                        </strong>

                                        ({{ $rating[$l->id_pencari]->jumlah }} ulasan)

                                    @else

                                        Belum ada ulasan

                                    @endif

                                </div>


                                {{-- KONTAK HANYA TERBUKA SETELAH DITERIMA --}}
                                @if(
                                    in_array(
                                        $l->status_lamaran,
                                        ['diterima', 'selesai'],
                                        true
                                    )
                                )

                                    @if($l->pencariKerja)

                                        <div class="applicant-contact">

                                            <div class="applicant-contact-title">
                                                Kontak Pelamar
                                            </div>

                                            <div class="applicant-contact-info">

                                                @if($l->pencariKerja->email)
                                                    <div>
                                                        <strong>Email:</strong>
                                                        {{ $l->pencariKerja->email }}
                                                    </div>
                                                @endif

                                                @if($l->pencariKerja->no_telpon)
                                                    <div>
                                                        <strong>No. Telepon:</strong>
                                                        {{ $l->pencariKerja->no_telpon }}
                                                    </div>
                                                @endif

                                                @if($l->pencariKerja->alamat)
                                                    <div>
                                                        <strong>Alamat:</strong>
                                                        {{ $l->pencariKerja->alamat }}
                                                    </div>
                                                @endif

                                            </div>

                                        </div>

                                    @endif

                                @endif

                            </div>

                        </div>

                    </td>


                    {{-- NAMA LOWONGAN --}}
                    @if(!$pekerjaan)

                        <td>

                            <a
                                href="{{ route('pemberi.pekerjaan.show', $l->pekerjaan->id_pekerjaan) }}"
                                style="color: var(--color-primary-dark); font-weight: 600; text-decoration: none; font-size: 0.9rem;"
                            >
                                {{ $l->pekerjaan->nama_pekerjaan }}
                            </a>

                        </td>

                    @endif


                    {{-- KEAHLIAN TERVERIFIKASI --}}
                    <td>

                        <div class="skill-tags">

                            @if(
                                isset($keahlian[$l->id_pencari])
                                && $keahlian[$l->id_pencari]->isNotEmpty()
                            )

                                @foreach($keahlian[$l->id_pencari] as $k)

                                    <span class="skill-tag">
                                        {{ $k->nama_keahlian }}
                                    </span>

                                @endforeach

                            @else

                                <span style="color: var(--color-ink-soft); font-size: 0.85rem;">
                                    -
                                </span>

                            @endif

                        </div>

                    </td>


                    {{-- TANGGAL MELAMAR --}}
                    <td>

                        <div style="font-size: 0.9rem; font-weight: 500;">

                            {{ \Carbon\Carbon::parse($l->tanggal_submit)->locale('id')->diffForHumans() }}

                        </div>

                    </td>


                    {{-- STATUS LAMARAN --}}
                    <td>

                        <span class="status-badge status-{{ strtolower($l->status_lamaran) }}">

                            {{ $l->status_lamaran }}

                        </span>

                    </td>


                    {{-- AKSI --}}
                    <td style="text-align: right;">

                        <div
                            class="action-group"
                            style="justify-content: flex-end;"
                        >

                            @if(
                                $l->status_lamaran === 'menunggu'
                                && $l->pekerjaan->status_pekerjaan === 'tersedia'
                            )

                                {{-- TOLAK --}}
                                <form
                                    action="{{ route('pemberi.lamaran.update', $l->id_lamaran) }}"
                                    method="POST"
                                    style="margin: 0;"
                                    onsubmit="return confirm('Yakin ingin menolak pelamar ini?');"
                                >

                                    @csrf

                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status_lamaran"
                                        value="ditolak"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-action btn-tolak"
                                    >
                                        Tolak
                                    </button>

                                </form>


                                {{-- TERIMA --}}
                                <form
                                    action="{{ route('pemberi.lamaran.update', $l->id_lamaran) }}"
                                    method="POST"
                                    style="margin: 0;"
                                    onsubmit="return confirm('Yakin ingin menerima pelamar ini?');"
                                >

                                    @csrf

                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status_lamaran"
                                        value="diterima"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-action btn-terima"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            width="16"
                                            height="16"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            fill="none"
                                        >
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>

                                        Terima

                                    </button>

                                </form>

                            @else

                                <button
                                    class="btn-action btn-disabled"
                                    disabled
                                >
                                    Telah Diproses
                                </button>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="{{ $pekerjaan ? '5' : '6' }}"
                        style="
                            text-align: center;
                            padding: 4rem 1rem;
                            color: var(--color-ink-soft);
                        "
                    >

                        <svg
                            viewBox="0 0 24 24"
                            width="48"
                            height="48"
                            stroke="currentColor"
                            stroke-width="1.5"
                            fill="none"
                            style="
                                margin-bottom: 1rem;
                                opacity: 0.5;
                            "
                        >
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>

                        <p style="font-size: 1rem; font-weight: 500;">
                            Belum ada pelamar di daftar ini.
                        </p>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>
@endsection