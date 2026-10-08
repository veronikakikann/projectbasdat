@extends('pencari.layout')

@section('title', 'Beranda')

@section('content')
<style>
    .dashboard-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #55B4EA 0%, #3D91C7 100%);
        border-radius: 12px;
        padding: 2.5rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(85, 180, 234, 0.2);
    }

    .welcome-title {
        font-family: 'Manrope', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .welcome-desc {
        font-size: 1rem;
        opacity: 0.9;
        line-height: 1.5;
        margin-bottom: 1.5rem;
        max-width: 80%;
    }

    .btn-yellow {
        background-color: #FEF08A;
        color: #854D0E;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        transition: 0.2s;
        border: none;
    }

    .btn-yellow:hover {
        background-color: #FDE047;
        transform: translateY(-2px);
    }

    /* Header text untuk section */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1rem;
    }

    .section-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--color-ink);
    }

    .section-link {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--color-primary);
        text-decoration: none;
    }

    .section-link:hover {
        text-decoration: underline;
    }

    /* Grid Statistik */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-num {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--color-ink);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--color-ink-soft);
        font-weight: 600;
    }

    /* Sidebar */
    .side-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .side-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .list-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        border-radius: 8px;
        transition: 0.2s;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .list-item:hover {
        background: #F8FAFC;
        border-color: var(--color-border);
    }

    .item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #E0F2FE;
        color: var(--color-primary-dark);
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
        text-transform: uppercase;
    }

    .item-content {
        min-width: 0;
    }

    .item-title {
        font-weight: 700;
        color: var(--color-ink);
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-desc {
        font-size: 0.8rem;
        color: var(--color-ink-soft);
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }

    /* Badge status */
    .badge-status {
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.7rem;
        margin-left: 4px;
        text-transform: capitalize;
    }

    .badge-menunggu {
        background: #FEF3C7;
        color: #B45309;
    }

    .badge-diterima {
        background: #D1FAE5;
        color: #047857;
    }

    .badge-ditolak {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .badge-selesai {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .badge-default {
        background: #E2E8F0;
        color: #475569;
    }

    .empty-mini {
        text-align: center;
        padding: 2rem 0;
        color: var(--color-ink-soft);
        font-size: 0.9rem;
    }

    @media (max-width: 850px) {
        .dashboard-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .welcome-desc {
            max-width: 100%;
        }
    }
</style>

<div class="dashboard-layout">

    <!-- Sisi Kiri -->
    <div>

        <div class="welcome-banner">

            <h1 class="welcome-title">
                Hai, {{ $pencari->nama ?? session('user_name', 'Pekerja') }}!
            </h1>

            <p class="welcome-desc">
                Siap untuk mencari cuan hari ini? Temukan berbagai lowongan
                pekerjaan yang cocok dengan keahlianmu dan mulai bekerja.
            </p>

            <a
                href="{{ route('pencari.cari-pekerjaan') }}"
                class="btn-yellow"
            >
                Cari Lowongan Sekarang
            </a>

        </div>


        <div class="section-header">

            <h2 class="section-title">
                Ringkasan Peluang
            </h2>

        </div>


        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-num">
                    {{ $jumlahPekerjaanTersedia }}
                </div>

                <div class="stat-label">
                    Lowongan Tersedia
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-num">
                    {{ $totalLamaranTerkirim }}
                </div>

                <div class="stat-label">
                    Total Lamaran Terkirim
                </div>

            </div>

        </div>

    </div>


    <!-- Sisi Kanan -->
    <div>

        <div class="side-card">

            <div
                class="section-header"
                style="margin-bottom: 1.5rem;"
            >

                <h2
                    class="section-title"
                    style="font-size: 1.1rem;"
                >
                    Lamaran Terakhir
                </h2>

                <a
                    href="{{ route('pencari.lamaran-saya') }}"
                    class="section-link"
                >
                    Semua
                </a>

            </div>


            <div class="side-list">

                @forelse($lamaranTerakhir as $lamaran)

                    @php
                        $statusClass = match ($lamaran->status_lamaran) {
                            'menunggu' => 'badge-menunggu',
                            'diterima' => 'badge-diterima',
                            'ditolak' => 'badge-ditolak',
                            'selesai' => 'badge-selesai',
                            default => 'badge-default',
                        };

                        $statusLabel = match ($lamaran->status_lamaran) {
                            'menunggu' => 'Menunggu',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                            'selesai' => 'Selesai',
                            default => ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $lamaran->status_lamaran
                                )
                            ),
                        };
                    @endphp


                    <a
                        href="{{ route('pekerjaan.show', $lamaran->pekerjaan->id_pekerjaan) }}"
                        class="list-item"
                    >

                        <div class="item-icon">
                            {{ substr(
                                $lamaran->pekerjaan->nama_pekerjaan ?? 'P',
                                0,
                                1
                            ) }}
                        </div>


                        <div class="item-content">

                            <div class="item-title">
                                {{ $lamaran->pekerjaan->nama_pekerjaan ?? 'Pekerjaan' }}
                            </div>

                            <div class="item-desc">

                                <span>
                                    {{ \Carbon\Carbon::parse($lamaran->tanggal_submit)
                                        ->locale('id')
                                        ->diffForHumans() }}
                                </span>

                                <span class="badge-status {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                            </div>

                        </div>

                    </a>

                @empty

                    <div class="empty-mini">

                        Belum ada lamaran.

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>
@endsection