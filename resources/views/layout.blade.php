<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teman Kerja')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ===== TEMA TEMAN KERJA ===== */
            --color-primary: #55B4EA;       /* biru utama */
            --color-primary-dark: #3D91C7;  /* biru lebih gelap / hover */

            --color-accent: #F5FF00;        /* kuning neon - CTA/highlight */
            --color-secondary: #F39AC0;     /* pink - aksen/dekorasi */

            --color-ink: #171717;           /* teks utama */
            --color-ink-soft: #5B6472;      /* teks sekunder */

            --color-bg: #F7FAFC;            /* background halaman */
            --color-surface: #FFFFFF;       /* card/navbar */
            --color-border: #E5E7EB;        /* border */
            /* ============================= */

            --font-heading: 'Manrope', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            color: var(--color-ink);
            background: var(--color-bg);
            line-height: 1.6;
        }

        a { text-decoration: none; color: inherit; }

        /* ---------- Navbar ---------- */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 5%;
            background: var(--color-primary);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar__brand {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--color-surface);
        }

        .navbar__brand span {
            color: var(--color-accent);
        }

        .navbar__menu {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .navbar__menu a {
            font-weight: 500;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.15s ease;
            position: relative;
            padding-bottom: 0.4rem;
        }

        .navbar__menu a:hover,
        .navbar__menu a.is-active {
            color: var(--color-surface);
        }

        .navbar__menu a.is-active::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            background: var(--color-accent);
            border-radius: 2px;
        }

        .navbar__login {
            display: inline-flex;
            align-items: center;
            padding: 0.65rem 1.6rem;
            background: var(--color-accent);
            color: var(--color-ink);
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: filter 0.15s ease;
        }

        .navbar__login:hover {
            filter: brightness(0.92);
        }

        @media (max-width: 780px) {
            .navbar__menu { display: none; }
        }

        /* ---------- Footer ---------- */
        .footer {
            padding: 2rem 5%;
            text-align: center;
            background: var(--color-primary);
            color: var(--color-ink);
            font-weight: 500;
            font-size: 0.85rem;
        }
    </style>

    @yield('styles')
</head>
<body>

    <nav class="navbar">
        <a href="/" class="navbar__brand">Teman <span>Kerja</span></a>
        <div class="navbar__menu">
            <a href="/" class="{{ request()->is('/') ? 'is-active' : '' }}">Beranda</a>
            <a href="/about" class="{{ request()->is('about') ? 'is-active' : '' }}">Tentang Kami</a>
            <a href="/contact" class="{{ request()->is('contact') ? 'is-active' : '' }}">Kontak Kami</a>
        </div>
        <a href="/login" class="navbar__login">Masuk</a>
    </nav>

    @yield('content')

    <footer class="footer">
        &copy; {{ date('Y') }} Teman Kerja. Menghubungkan pekerja dan pemberi kerja di sekitar kamu.
    </footer>

</body>
</html>