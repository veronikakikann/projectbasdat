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


    /* ===== Ilustrasi: titik terhubung ===== */
    .hero__art { flex-direction: column; align-items: center; gap: 0.75rem; }
    .map { max-width: 520px; overflow: visible; }
    .map__link {
        fill: none; stroke: var(--color-surface); stroke-width: 4.5;
        stroke-linecap: round; stroke-dasharray: 0.1 11;
        animation: link-flow 1.4s linear infinite;
    }
    .map__dot { fill: var(--color-ink); stroke: var(--color-surface); stroke-width: 2.5; }
    .map__halo { fill: rgba(255, 255, 255, 0.35); }
    .map__pulse {
        fill: none; stroke: var(--color-surface); stroke-width: 2.5;
        transform-box: fill-box; transform-origin: center;
        animation: pin-pulse 2.4s ease-out infinite;
    }
    .map__match rect { fill: var(--color-secondary); stroke: var(--color-surface); stroke-width: 2; }
    .map__match text { fill: var(--color-ink); font-family: var(--font-heading); font-weight: 800; font-size: 14px; }
    .map__match { animation: match-pop 3.2s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }

    .map-legend {
        display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem 1.25rem;
        list-style: none; font-size: 0.85rem; font-weight: 600; color: var(--color-surface);
    }
    .map-legend li { display: flex; align-items: center; gap: 0.45rem; }
    .map-legend__pin { width: 12px; height: 12px; border-radius: 50%; border: 2px solid var(--color-ink); }
    .map-legend__pin--pemberi { background: var(--color-accent); }
    .map-legend__pin--pencari { background: var(--color-secondary); }
    .map-legend__line { width: 24px; border-top: 3px dotted var(--color-surface); }

    @keyframes link-flow { to { stroke-dashoffset: -11.1; } }
    @keyframes pin-pulse { 0% { transform: scale(0.8); opacity: 0.9; } 100% { transform: scale(1.9); opacity: 0; } }
    @keyframes match-pop { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }

    @media (prefers-reduced-motion: reduce) {
        .map__link, .map__pulse, .map__match { animation: none; }
        .map__dot { display: none; }
    }

    @media (max-width: 900px) {
        .hero { flex-direction: column; justify-content: center; text-align: left; gap: 1rem; }
        .hero__art { order: -1; max-width: min(240px, 34vh); }
        .map-legend { font-size: 0.7rem; gap: 0.3rem 0.8rem; }
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
            
            <svg class="map" viewBox="0 0 480 480" width="100%" height="auto" role="img" aria-label="Pemberi kerja dan pencari kerja saling terhubung">
                <defs>
                    <g id="pin">
                        <path d="M0 0 C-8 -8 -13 -17 -13 -26 C-13 -35.9 -7.2 -43 0 -43 C7.2 -43 13 -35.9 13 -26 C13 -17 8 -8 0 0 Z" stroke="var(--color-ink)" stroke-width="1.6"/>
                        <circle cx="0" cy="-26" r="5" fill="var(--color-ink)"/>
                    </g>
                </defs>
                <!-- garis penghubung oranye -->
                <path class="map__link" d="M 135 205 Q 116 139 62 97"/>
                <path class="map__link" d="M 135 205 Q 206 154 235 72"/>
                <path class="map__link" d="M 135 205 Q 85 275 85 362"/>
                <path class="map__link" d="M 355 155 Q 308 94 235 72"/>
                <path class="map__link" d="M 355 155 Q 399 137 425 97"/>
                <path class="map__link" d="M 355 155 Q 371 233 430 287"/>
                <path class="map__link" d="M 270 365 Q 178 334 85 362"/>
                <path class="map__link" d="M 270 365 Q 363 351 430 287"/>
                <!-- titik bergerak -->
                <circle class="map__dot" r="4.5"><animateMotion dur="3.6s" repeatCount="indefinite" path="M 135 205 Q 116 139 62 97"/></circle>
                <circle class="map__dot" r="4.5"><animateMotion dur="4.2s" repeatCount="indefinite" path="M 355 155 Q 308 94 235 72"/></circle>
                <circle class="map__dot" r="4.5"><animateMotion dur="3.9s" repeatCount="indefinite" path="M 270 365 Q 178 334 85 362"/></circle>
                <circle class="map__dot" r="4.5"><animateMotion dur="4.6s" repeatCount="indefinite" path="M 355 155 Q 371 233 430 287"/></circle>
                <!-- pencari kerja -->
                <use href="#pin" transform="translate(62,120) scale(0.9)" fill="var(--color-secondary)"/>
                <use href="#pin" transform="translate(235,95) scale(0.9)" fill="var(--color-secondary)"/>
                <use href="#pin" transform="translate(430,310) scale(0.9)" fill="var(--color-secondary)"/>
                <use href="#pin" transform="translate(85,385) scale(0.9)" fill="var(--color-secondary)"/>
                <use href="#pin" transform="translate(425,120) scale(0.9)" fill="var(--color-secondary)"/>
                <!-- pemberi kerja: lingkaran oranye + pin kuning -->
                <circle class="map__pulse" cx="135" cy="205" r="26"/>
                <circle class="map__halo" cx="135" cy="205" r="26"/>
                <use href="#pin" transform="translate(135,235) scale(1.15)" fill="var(--color-accent)"/>
                <circle class="map__pulse" cx="355" cy="155" r="26"/>
                <circle class="map__halo" cx="355" cy="155" r="26"/>
                <use href="#pin" transform="translate(355,185) scale(1.15)" fill="var(--color-accent)"/>
                <circle class="map__pulse" cx="270" cy="365" r="26"/>
                <circle class="map__halo" cx="270" cy="365" r="26"/>
                <use href="#pin" transform="translate(270,395) scale(1.15)" fill="var(--color-accent)"/>
                <!-- lencana cocok -->
                <g transform="translate(196,146)"><g class="map__match"><rect x="-32" y="-13" width="64" height="26" rx="13"/><text y="5" text-anchor="middle">Cocok!</text></g></g>
            </svg>
            <ul class="map-legend">
                <li><i class="map-legend__pin map-legend__pin--pemberi"></i>Pemberi Kerja</li>
                <li><i class="map-legend__pin map-legend__pin--pencari"></i>Pencari Kerja</li>
                <li><i class="map-legend__line"></i>Terhubung</li>
            </ul>
                </div>
    </div>
</section>
@endsection