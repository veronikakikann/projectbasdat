@extends('pemberi.layout')

@section('title', 'Notifikasi')

@section('content')
<style>
    .page-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 1.5rem;
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
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background-color: #F8FAFC; }
    
    .notif-unread { background-color: #F0F9FF; }

    .notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #E0F2FE;
        color: var(--color-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notif-content { flex-grow: 1; }
    .notif-text { font-size: 0.95rem; color: var(--color-ink); margin-bottom: 0.25rem; line-height: 1.5; }
    .notif-time { font-size: 0.8rem; color: var(--color-ink-soft); }
</style>

<h1 class="page-title">Notifikasi</h1>

<div class="notif-card">
    @forelse($notifikasi as $n)
        <!-- Tambahkan class 'notif-unread' jika notifikasi belum dibaca (asumsi ada kolom status_baca di DB) -->
        <div class="notif-item {{ !$n->status_baca ? 'notif-unread' : '' }}">
            <div class="notif-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            <div class="notif-content">
                <div class="notif-text">{{ $n->pesan_notifikasi ?? $n->pesan }}</div>
                <div class="notif-time">{{ \Carbon\Carbon::parse($n->created_at)->locale('id')->diffForHumans() }}</div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 4rem 1rem; color: var(--color-ink-soft);">
            <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 1rem; opacity: 0.5;"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><line x1="2" y1="2" x2="22" y2="22"></line></svg>
            <p style="font-size: 1rem; font-weight: 500;">Belum ada notifikasi baru.</p>
        </div>
    @endforelse
</div>
@endsection