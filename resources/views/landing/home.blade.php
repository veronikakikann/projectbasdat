@extends('layout')

@section('title', 'Beranda')

@section('styles')
<style>
    .hero {
        display: flex;
        align-items: center;
        gap: 4rem;
        max-width: 1160px;
        margin: 0 auto;
        padding: 5rem 5% 6rem;
    }

    .hero__content { flex: 1 1 480px; }

    .hero__content h1 {
        font-family: var(--font-heading);
        font-size: clamp(2.2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 1.25rem;
        max-width: 14ch;
    }

    .hero__content p {
        color: var(--color-ink-soft);
        font-size: 1.05rem;
        max-width: 52ch;
        margin-bottom: 2rem;
    }

    .hero__cta {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        padding: 0.85rem 2rem;
        background: var(--color-primary);
        color: var(--color-ink);
        font-weight: 700;
        font-size: 1rem;
        border-radius: 8px;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .btn-primary:hover {
        background: var(--color-primary-dark);
        color: var(--color-surface);
        transform: translateY(-1px);
    }

    .hero__cta-note {
        font-size: 0.9rem;
        color: var(--color-ink-soft);
    }

    .hero__cta-note a {
        color: var(--color-primary-dark);
        font-weight: 600;
    }

    .hero__art {
        flex: 1 1 380px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 900px) {
        .hero { flex-direction: column; padding-top: 3rem; }
        .hero__art { order: -1; max-width: 280px; }
    }
</style>
@endsection

@section('content')
<section class="hero">
    <div class="hero__content">
        <h1>Kerja harian, dicari langsung dari HP kamu</h1>
        <p>
            Bursa Kerja Harian menghubungkan pencari kerja lepas seperti kuli panggul,
            tukang bersih-bersih, dan bantu pindahan, dengan warga di sekitar yang
            butuh bantuan. Gak perlu CV atau ijazah, cukup keahlian yang bisa
            dibuktikan langsung lewat surat pengantar dari RT/RW.
        </p>
        <div class="hero__cta">
            <a href="/login" class="btn-primary">Masuk ke Akun</a>
            <span class="hero__cta-note">Belum punya akun? <a href="/register">Daftar di sini</a></span>
        </div>
    </div>

    <div class="hero__art">
        <svg viewBox="0 0 420 420" width="100%" height="auto" style="max-width:360px">
            <circle cx="210" cy="210" r="200" fill="var(--color-primary)" opacity="0.12" />
            <path d="M210 70 C270 70 315 115 315 175 C315 245 210 340 210 340 C210 340 105 245 105 175 C105 115 150 70 210 70 Z" fill="var(--color-primary)" />
            <circle cx="210" cy="175" r="55" fill="var(--color-surface)" />
            <path d="M175 190 a35 35 0 0 1 70 0 Z" fill="var(--color-ink)" />
            <rect x="168" y="188" width="84" height="10" rx="5" fill="var(--color-ink)" />
            <rect x="205" y="150" width="10" height="20" rx="3" fill="var(--color-ink)" />
            <circle cx="70" cy="330" r="6" fill="var(--color-primary)" />
            <circle cx="340" cy="90" r="6" fill="var(--color-primary)" />
            <circle cx="360" cy="300" r="5" fill="var(--color-primary-dark)" />
        </svg>
    </div>
</section>
@endsection