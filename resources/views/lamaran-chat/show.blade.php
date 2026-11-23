@extends($role === 'pemberi_kerja' ? 'pemberi.layout' : 'pencari.layout')

@section('title', 'Chat Lamaran')

@section('content')
@php
    $isEmployer = $role === 'pemberi_kerja';
    $contactName = $isEmployer ? $lamaran->pencariKerja->nama : $lamaran->pekerjaan->pemberiKerja->nama;
    $backUrl = $isEmployer
        ? route('pemberi.pelamar.index', $lamaran->id_pekerjaan)
        : route('pencari.lamaran-saya');
    $bukti = $lamaran->buktiPenyelesaian;
@endphp

<style>
    .chat-room {
        max-width: 760px;
        margin-inline: auto;
        padding: clamp(1rem, 3vw, 1.5rem);
        border: 1px solid var(--color-border);
        border-radius: 16px;
        background: var(--color-surface);
    }

    .chat-room h2 {
        margin: .75rem 0 .25rem;
        font-family: 'Manrope', sans-serif;
        color: var(--color-ink);
    }

    .chat-room a {
        color: var(--color-ink);
        font-weight: 600;
    }

    .chat-context, .chat-message-meta, .chat-empty {
        color: var(--color-ink-soft);
        font-size: .875rem;
    }

    .chat-proof, .chat-history-nav {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem 1rem;
        margin: .75rem 0;
        font-size: .875rem;
    }

    .chat-history {
        height: clamp(14rem, 48vh, 30rem);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: .75rem;
        margin-block: 1rem;
        padding: .875rem;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        background: var(--color-bg);
    }

    .chat-message {
        align-self: flex-start;
        max-width: 92%;
        padding: .75rem;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        background: var(--color-surface);
    }

    .chat-message-own {
        align-self: flex-end;
        border-color: var(--color-primary);
    }

    .chat-message-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .25rem .75rem;
    }

    .chat-message p {
        margin: .3rem 0 0;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .chat-composer {
        display: flex;
        flex-direction: column;
        gap: .45rem;
    }

    .chat-composer textarea, .chat-composer button {
        font: inherit;
        border-radius: 8px;
    }

    .chat-composer textarea {
        padding: .75rem;
        resize: vertical;
        border: 1px solid var(--color-border);
        background: var(--color-surface);
        color: var(--color-ink);
    }

    .chat-composer button {
        align-self: flex-end;
        padding: .6rem 1.25rem;
        background: var(--color-primary);
        color: var(--color-ink);
        border: 0;
        font-weight: 700;
        cursor: pointer;
    }
</style>

<section class="chat-room">
    <a href="{{ $backUrl }}">← Kembali ke Daftar Lamaran</a>
    <h2>{{ $contactName }}</h2>
    <p class="chat-context">{{ $lamaran->pekerjaan->nama_pekerjaan }}</p>

    @if($bukti?->foto_bukti_kerja || $bukti?->foto_bukti_bayar)
        <div class="chat-proof">
            @include('partials.bukti-links')
        </div>
    @endif

    <div id="chat-history" class="chat-history" tabindex="0" aria-label="Riwayat chat"
        data-feed="{{ route('lamaran.chat.pesan', $lamaran) }}">
        @include('lamaran-chat.pesan')
    </div>

    <form class="chat-composer" method="POST" action="{{ route('lamaran.chat.store', $lamaran) }}">
        @csrf
        <label for="pesan">Pesan</label>
        <textarea id="pesan" name="pesan" rows="2" maxlength="2000" required>{{ old('pesan') }}</textarea>
        @error('pesan')
            <p role="alert">{{ $message }}</p>
        @enderror
        <button type="submit">Kirim Pesan</button>
    </form>
</section>

@if(!request()->has('cursor'))
    <script>
        (() => {
            const history = document.getElementById('chat-history');
            const atBottom = () => history.scrollHeight - history.clientHeight - history.scrollTop < 32;
            const scrollToLatest = () => { history.scrollTop = history.scrollHeight; };

            scrollToLatest();

            async function refreshMessages() {
                try {
                    if (document.hidden || !atBottom() || history.contains(document.activeElement)) return;
                    const response = await fetch(history.dataset.feed, { cache: 'no-store' });
                    if (!response.ok || response.redirected) return;
                    const messages = await response.text();
                    if (history.innerHTML.trim() !== messages.trim() && atBottom() && !history.contains(document.activeElement)) {
                        history.innerHTML = messages;
                        scrollToLatest();
                    }
                } catch {
                    // Keep the current conversation visible when the connection drops.
                } finally {
                    window.setTimeout(refreshMessages, 5000);
                }
            }

            window.setTimeout(refreshMessages, 5000);
        })();
    </script>
@endif
@endsection
