@php
    $roomUrl = route('lamaran.chat.show', $lamaran);
    $contactRole = $role === 'pemberi_kerja' ? 'Pencari Kerja' : 'Pemberi Kerja';
@endphp

@if($messages->nextCursor() || $messages->previousCursor())
    <nav class="chat-history-nav" aria-label="Riwayat pesan">
        @if($messages->nextCursor())
            <a href="{{ $roomUrl }}?cursor={{ $messages->nextCursor()->encode() }}">Pesan sebelumnya</a>
        @endif
        @if($messages->previousCursor())
            <a href="{{ $roomUrl }}?cursor={{ $messages->previousCursor()->encode() }}">Pesan lebih baru</a>
            <a href="{{ $roomUrl }}">Pesan terbaru</a>
        @endif
    </nav>
@endif

@forelse(collect($messages->items())->reverse() as $message)
    <article class="chat-message {{ $message->peran_pengirim === $role ? 'chat-message-own' : '' }}">
        <div class="chat-message-meta">
            @php($sentAt = \Illuminate\Support\Carbon::parse($message->dikirim_pada))
            <strong>{{ $message->peran_pengirim === $role ? 'Anda' : $contactRole }}</strong>
            <time datetime="{{ $sentAt->toIso8601String() }}">{{ $sentAt->format('d/m/Y H:i') }}</time>
        </div>
        <p>{{ $message->pesan }}</p>
    </article>
@empty
    <p class="chat-empty">Belum ada pesan.</p>
@endforelse
