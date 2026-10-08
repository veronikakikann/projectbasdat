@extends('pencari.layout')

@section('title', 'Notifikasi')

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

    .notif-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .notif-item {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--color-border);
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        transition: 0.2s;
    }

    .notif-item:last-child {
        border-bottom: none;
    }

    .notif-item:hover {
        background-color: #F8FAFC;
    }

    .notif-unread {
        background-color: #FFF7ED;
    }

    .notif-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #FDE2D7;
        color: var(--color-primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notif-content {
        flex-grow: 1;
    }

    .notif-text {
        font-size: 0.95rem;
        color: var(--color-ink);
        margin-bottom: 0.4rem;
        line-height: 1.5;
    }

    .notif-time {
        font-size: 0.8rem;
        color: var(--color-ink-soft);
    }

    .notif-new {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.55rem;
        border-radius: 20px;
        background: #FED7AA;
        color: #9A3412;
        font-size: 0.7rem;
        font-weight: 700;
        margin-left: 0.5rem;
        vertical-align: middle;
    }

    .empty-card {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--color-ink-soft);
    }

    .empty-icon {
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>


<div class="page-header">

    <h1 class="page-title">
        Notifikasi
    </h1>

    <p class="page-desc">
        Informasi terbaru mengenai lamaran dan keahlianmu.
    </p>

</div>


<div class="notif-card">

    @forelse($notifikasi as $n)

        @php
            $isBaru = in_array(
                $n->id_notifikasi,
                $baru ?? [],
                true
            );
        @endphp

        <div
            class="notif-item {{ $isBaru ? 'notif-unread' : '' }}"
        >

            <div class="notif-icon">

                <svg
                    viewBox="0 0 24 24"
                    width="20"
                    height="20"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>

            </div>


            <div class="notif-content">

                <div class="notif-text">

                    {{ $n->isi_pesan }}

                    @if($isBaru)
                        <span class="notif-new">
                            Baru
                        </span>
                    @endif

                </div>

                <div class="notif-time">

                    {{ \Carbon\Carbon::parse($n->tanggal)
                        ->locale('id')
                        ->diffForHumans()
                    }}

                </div>

            </div>

        </div>

    @empty

        <div class="empty-card">

            <div class="empty-icon">

                <svg
                    viewBox="0 0 24 24"
                    width="52"
                    height="52"
                    stroke="currentColor"
                    stroke-width="1.5"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    <line x1="2" y1="2" x2="22" y2="22"></line>
                </svg>

            </div>

            <p style="font-size:1rem; font-weight:600; color:var(--color-ink);">
                Belum ada notifikasi.
            </p>

            <p style="font-size:0.85rem; margin-top:0.4rem;">
                Notifikasi akan muncul ketika ada pembaruan
                mengenai lamaran atau keahlianmu.
            </p>

        </div>

    @endforelse

</div>

@endsection