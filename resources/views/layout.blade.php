<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bursa Kerja Harian')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ===== GANTI WARNA TEMA KELOMPOK DI SINI ===== */
            --color-primary: #F2A93C;       /* warna aksen utama: tombol, highlight */
            --color-primary-dark: #C97E17;  /* versi lebih gelap, dipakai saat hover */
            --color-ink: #16202A;           /* warna teks utama / gelap */
            --color-ink-soft: #5B6472;      /* warna teks sekunder / abu-abu */
            --color-bg: #F7F5F1;            /* warna latar halaman */
            --color-surface: #FFFFFF;       /* warna latar navbar/card */
            --color-border: #E7E2D8;        /* warna garis pemisah tipis */
            /* =============================================== */

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
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar__brand {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--color-ink);
        }

        .navbar__menu {
            display: flex;
            gap: 2.25rem;
            align-items: center;
        }

        .navbar__menu a {
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--color-ink-soft);
            transition: color 0.15s ease;
        }

        .navbar__menu a:hover,
        .navbar__menu a.is-active {
            color: var(--color-ink);
        }

        .navbar__login {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 1.4rem;
            background: var(--color-primary);
            color: var(--color-ink);
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: background 0.15s ease;
        }

        .navbar__login:hover {
            background: var(--color-primary-dark);
            color: var(--color-surface);
        }

        @media (max-width: 780px) {
            .navbar__menu { display: none; }
        }

        /* ---------- Footer ---------- */
        .footer {
            padding: 2rem 5%;
            text-align: center;
            color: var(--color-ink-soft);
            font-size: 0.85rem;
            border-top: 1px solid var(--color-border);
            margin-top: 4rem;
        }
    </style>

    @yield('styles')
</head>
<body>

    <nav class="navbar">
        <a href="/" class="navbar__brand">Bursa Kerja Harian</a>
        <div class="navbar__menu">
            <a href="/" class="{{ request()->is('/') ? 'is-active' : '' }}">Home</a>
            <a href="/about" class="{{ request()->is('about') ? 'is-active' : '' }}">About Us</a>
            <a href="/contact" class="{{ request()->is('contact') ? 'is-active' : '' }}">Contact</a>
        </div>
        <a href="/login" class="navbar__login">Masuk</a>
    </nav>

    @yield('content')

    <footer class="footer">
        &copy; {{ date('Y') }} Bursa Kerja Harian. Menghubungkan pekerja dan pemberi kerja di sekitar kamu.
    </footer>

</body>
</html>