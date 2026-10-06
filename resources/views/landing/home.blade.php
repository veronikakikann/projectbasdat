@extends('layout')

@section('title', 'Teman Kerja | Beranda')

@section('styles')
<style>
    html, body {
        height: 100%;
        overflow: hidden;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    .hero-section {
        background: var(--color-primary);
        flex: 1;
        display: flex;
        align-items: center;
        min-height: 0; /* penting biar flex child boleh menyusut, bukan malah dorong overflow */
        overflow: hidden;
    }

    .footer {
        flex-shrink: 0;
    }

    .hero {
        display: flex;
        align-items: center;
        gap: 2rem;
        max-width: 1320px;
        margin: 0 auto;
        padding: clamp(1.5rem, 4vh, 4rem) 5%;
        width: 100%;
    }

    .hero__content { flex: 1.25 1 520px; }

    .hero__content h1 {
        font-family: var(--font-heading);
        font-size: clamp(2.2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.2;
        color: var(--color-surface);
        margin-bottom: 1.25rem;
    }

    .hero__content h1 mark {
        background: none;
        color: var(--color-ink);
    }

    .hero__content p {
        color: rgba(255, 255, 255, 0.88);
        font-size: 1.05rem;
        max-width: 56ch;
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
        background: var(--color-accent);
        color: var(--color-ink);
        font-weight: 700;
        font-size: 1rem;
        border-radius: 8px;
        transition: filter 0.15s ease, transform 0.15s ease;
    }

    .btn-primary:hover {
        filter: brightness(0.92);
        transform: translateY(-1px);
    }

    .hero__cta-note {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.88);
    }

    .hero__cta-note a {
        color: var(--color-accent);
        font-weight: 700;
    }

    .hero__art {
        flex: 0.85 1 360px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 900px) {
        .hero { flex-direction: column; justify-content: center; text-align: left; gap: 1rem; }
        .hero__art { order: -1; max-width: min(220px, 30vh); }
        .hero__content h1 { font-size: clamp(1.3rem, 5vw, 2rem); margin-bottom: 0.6rem; }
        .hero__content p { font-size: 0.85rem; margin-bottom: 1rem; -webkit-line-clamp: 4; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; }
        .btn-primary { padding: 0.6rem 1.4rem; font-size: 0.9rem; }
        .hero__cta-note { font-size: 0.8rem; }
    }
</style>
@endsection

@section('content')
<section class="hero-section">
    <div class="hero">
        <div class="hero__content">
            <h1><mark>Temukan Kerja. </mark> Temukan orang yang tepat.</h1>
            <p>
                Punya keahlian dan sedang mencari kerja? Atau sedang membutuhkan 
                bantuan untuk pekerjaan harian? Teman Kerja mempertemukan pencari 
                kerja dan pemberi kerja di sekitar kamu tanpa proses yang rumit.
            </p>
            <div class="hero__cta">
                <a href="/login" class="btn-primary">Masuk ke Akun</a>
                <span class="hero__cta-note">Belum punya akun? <a href="/register">Daftar di sini</a></span>
            </div>
        </div>

        <div class="hero__art">
            <svg viewBox="0 0 480 480" width="100%" height="auto" style="max-width:520px">
            <path d="M 425.5 341.3 L 385.8 341.5 L 360.8 345.2 L 337.5 344.1 L 325.9 337.6 L 293.4 341.4 L 264.1 355.9 L 250.3 356.3 L 240.4 344.2 L 222.8 336.5 L 214.3 339.4 L 200.0 353.8 L 185.0 363.3 L 159.5 367.4 L 140.2 381.8 L 132.0 383.7 L 138.4 364.8 L 139.4 347.0 L 122.5 329.9 L 116.6 297.2 L 102.5 294.8 L 79.1 296.9 L 75.4 290.2 L 75.9 270.5 L 71.4 252.5 L 83.3 221.1 L 80.8 210.6 L 54.2 194.3 L 41.6 173.4 L 21.2 154.5 L 20.0 146.5 L 33.1 142.7 L 33.7 111.8 L 68.5 123.1 L 94.3 110.5 L 130.7 103.3 L 128.4 107.5 L 138.7 133.1 L 147.0 145.7 L 161.5 141.5 L 172.0 147.1 L 179.7 142.9 L 188.5 150.3 L 206.6 147.5 L 238.8 133.6 L 244.4 119.6 L 241.6 110.8 L 246.7 103.8 L 261.6 121.0 L 271.9 102.8 L 289.5 96.3 L 294.2 107.0 L 320.3 101.0 L 330.5 106.6 L 341.8 121.9 L 349.2 123.3 L 362.2 149.4 L 376.6 173.6 L 383.6 181.1 L 392.5 200.7 L 426.5 215.5 L 451.6 236.0 L 460.0 254.7 L 458.6 289.1 L 452.1 305.0 L 433.7 323.0 L 425.5 341.3 Z" fill="var(--color-secondary)" />
            <g transform="translate(250,215) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(50,125) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(440,305) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(150,365) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(80,255) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(290,335) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(370,175) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(160,155) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(280,115) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(170,275) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(350,265) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(370,335) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(420,235) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(90,185) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(220,325) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(230,155) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(270,275) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(300,175) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(110,115) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-accent)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
            <g transform="translate(150,215) scale(0.55)"><path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" fill="var(--color-surface)" stroke="var(--color-ink)" stroke-width="1.8"/><circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/></g>
        </svg>
        </div>
    </div>
</section>
@endsection