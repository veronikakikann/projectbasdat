<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard') - TemanKerja
    </title>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --color-primary: #FF8A5B;
            --color-primary-dark: #E57A50;
            --color-secondary: #55B4EA;
            --color-accent: #F5FF00;
            --color-ink: #171717;
            --color-ink-soft: #718096;
            --color-surface: #FFFFFF;
            --color-bg: #F4F7FE;
            --color-border: #E2E8F0;

            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-ink);
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--color-primary);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 10;
        }

        .sidebar-brand {
            padding: 2.5rem 2rem 2rem;
            font-family: 'Manrope', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .sidebar-brand span {
            color: var(--color-accent);
        }

        .nav-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.7);
            margin: 0 2rem 1rem;
        }

        .nav-menu {
            list-style: none;
            padding: 0 1rem;
            flex-grow: 1;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
        }

        .nav-link.active {
            background-color: rgba(255,255,255,0.25);
            color: white;
            font-weight: 700;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
        }

        /* =========================
           PUSAT BANTUAN
        ========================= */

        .help-box {
            margin: 1.5rem;
            padding: 1.5rem 1.25rem;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            text-align: center;
            border: none;
        }

        .help-box h4 {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .help-box p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .btn-help {
            display: block;
            width: 100%;
            padding: 0.7rem;
            background: white;
            color: var(--color-primary);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.2s;
        }

        .btn-help:hover {
            background: var(--color-bg);
        }

        /* =========================
           MAIN
        ========================= */

        .main-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* =========================
           TOPBAR
        ========================= */

        .topbar {
            height: 80px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            margin-top: 1rem;
        }

        .page-heading {
            font-family: 'Manrope', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--color-ink);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* =========================
           DROPDOWN
        ========================= */

        .dropdown {
            position: relative;
        }

        /* =========================
           NOTIFIKASI
        ========================= */

        .btn-notif {
            background: white;
            border: 1px solid var(--color-border);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-ink-soft);
            cursor: pointer;
            position: relative;
            transition: 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            text-decoration: none;
        }

        .btn-notif:hover {
            background: var(--color-bg);
            color: var(--color-ink);
        }

        .btn-notif svg {
            width: 20px;
            height: 20px;
        }

        .notif-dot {
            position: absolute;
            top: 8px;
            right: 9px;
            width: 8px;
            height: 8px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* =========================
           PROFILE BUTTON
        ========================= */

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: white;
            padding: 0.4rem 1rem 0.4rem 0.4rem;
            border-radius: 30px;
            cursor: pointer;
            border: 1px solid var(--color-border);
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: 0.2s;
        }

        .profile-btn:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--color-ink);
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--color-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* =========================
           DROPDOWN MENU
        ========================= */

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 120%;
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            width: 220px;
            overflow: hidden;
            z-index: 100;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 0.8rem 1.2rem;
            color: var(--color-ink);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-bottom: 1px solid var(--color-border);
        }

        .dropdown-item:hover {
            background-color: var(--color-bg);
        }

        .logout-button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 0.8rem 1.2rem;
            cursor: pointer;
            color: #EF4444;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .logout-button:hover {
            background-color: var(--color-bg);
        }

        /* =========================
           CONTENT
        ========================= */

        .content-area {
            padding: 0 2.5rem 2.5rem;
            flex-grow: 1;
        }
    </style>
</head>

<body>

    <!-- =========================
         SIDEBAR
    ========================= -->

    <aside class="sidebar">

        <div class="sidebar-brand">
            Teman<span>Kerja</span>
        </div>

        <div class="nav-label">
            Dashboard
        </div>

        <ul class="nav-menu">

            <!-- Beranda -->
            <li class="nav-item">
                <a
                    href="{{ route('pencari.dashboard') }}"
                    class="nav-link {{ request()->routeIs('pencari.dashboard') ? 'active' : '' }}"
                >
                    <svg
                        class="nav-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>

                    Beranda
                </a>
            </li>

            <!-- Cari Lowongan -->
            <li class="nav-item">
                <a
                    href="{{ route('pencari.cari-pekerjaan') }}"
                    class="nav-link {{ request()->routeIs('pencari.cari-pekerjaan', 'pekerjaan.show') ? 'active' : '' }}"
                >
                    <svg
                        class="nav-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>

                    Cari Lowongan
                </a>
            </li>

            <!-- Lamaran Saya -->
            <li class="nav-item">
                <a
                    href="{{ route('pencari.lamaran-saya') }}"
                    class="nav-link {{ request()->routeIs('pencari.lamaran-saya', 'pencari.lamar', 'pencari.lamaran.batal') ? 'active' : '' }}"
                >
                    <svg
                        class="nav-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="18"
                            rx="2"
                            ry="2"
                        ></rect>

                        <line
                            x1="16"
                            y1="2"
                            x2="16"
                            y2="6"
                        ></line>

                        <line
                            x1="8"
                            y1="2"
                            x2="8"
                            y2="6"
                        ></line>

                        <line
                            x1="3"
                            y1="10"
                            x2="21"
                            y2="10"
                        ></line>
                    </svg>

                    Lamaran Saya
                </a>
            </li>

            <!-- Keahlian Saya -->
            <li class="nav-item">
                <a
                    href="{{ route('keahlian_pencari_kerja.index') }}"
                    class="nav-link {{ request()->routeIs('keahlian_pencari_kerja.*') ? 'active' : '' }}"
                >
                    <svg
                        class="nav-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>

                    Keahlian Saya
                </a>
            </li>

        </ul>

        <!-- Pusat Bantuan -->
        <div class="help-box">

            <h4>
                Pusat Bantuan
            </h4>

            <p>
                Hubungi kami jika ada kendala.
            </p>

            <a
                href="/contact"
                class="btn-help"
            >
                Chat Admin
            </a>

        </div>

    </aside>


    <!-- =========================
         MAIN
    ========================= -->

    <main class="main-wrapper">

        <!-- =========================
             TOPBAR
        ========================= -->

        <header class="topbar">

            <div class="page-heading">
                @yield('title')
            </div>

            <div class="topbar-right">

                <!-- =====================
                     DROPDOWN NOTIFIKASI
                ====================== -->

                <div class="dropdown">

                    <button
                        type="button"
                        class="btn-notif"
                        onclick="toggleDropdown('notifMenu')"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>

                        <span class="notif-dot"></span>
                    </button>

                    <div
                        id="notifMenu"
                        class="dropdown-menu"
                    >
                        <a
                            href="{{ route('pencari.notifikasi') }}"
                            class="dropdown-item"
                        >
                            Lihat Semua Notifikasi
                        </a>
                    </div>

                </div>


                <!-- =====================
                     DROPDOWN PROFIL
                ====================== -->

                <div class="dropdown">

                    <button
                        type="button"
                        class="profile-btn"
                        onclick="toggleDropdown('profileMenu')"
                    >

                        <div class="avatar">
                            {{ strtoupper(substr(session('user_name', 'Pencari Kerja'), 0, 1)) }}
                        </div>

                        <span class="profile-name">
                            {{ session('user_name', 'Pencari Kerja') }}
                        </span>

                    </button>

                    <div
                        id="profileMenu"
                        class="dropdown-menu"
                    >

                        <a
                            href="{{ route('pencari.profil') }}"
                            class="dropdown-item"
                        >
                            Profil Saya
                        </a>

                        <form
                            action="{{ route('logout') }}"
                            method="POST"
                            style="margin: 0;"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="logout-button"
                            >
                                Keluar
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </header>


        <!-- =========================
             CONTENT
        ========================= -->

        <div class="content-area">
            @yield('content')
        </div>

    </main>


    <!-- =========================
         DROPDOWN SCRIPT
    ========================= -->

    <script>

        function toggleDropdown(id) {

            // Tutup dropdown lainnya
            document
                .querySelectorAll('.dropdown-menu')
                .forEach(function(menu) {

                    if (menu.id !== id) {
                        menu.classList.remove('show');
                    }

                });

            // Buka/tutup dropdown yang diklik
            document
                .getElementById(id)
                .classList.toggle('show');
        }

        // Tutup dropdown ketika klik di luar
        window.onclick = function(event) {

            if (!event.target.closest('.dropdown')) {

                document
                    .querySelectorAll('.dropdown-menu')
                    .forEach(function(menu) {

                        menu.classList.remove('show');

                    });

            }

        };

    </script>

</body>
</html>