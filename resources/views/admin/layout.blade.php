<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Teman Kerja</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #3D91C7;      /* sidebar admin: biru lebih tua dari Pemberi Kerja */
            --color-primary-dark: #2B6C9B;
            --color-accent: #F5FF00;
            --color-secondary: #FF8A5B;
            --color-ink: #171717;
            --color-ink-soft: #718096;
            --color-surface: #FFFFFF;
            --color-bg: #F4F7FE;
            --color-border: #E2E8F0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-ink);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background-color: var(--color-primary);
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
            height: 100%;
            overflow-y: auto;
            box-shadow: 4px 0 15px rgba(0,0,0,0.03);
            z-index: 20;
        }
        .brand {
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-surface);
            text-decoration: none;
            margin-bottom: 2rem;
            display: block;
        }
        .brand span { color: var(--color-accent); }
        .sidebar-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.6);
            font-weight: 700;
            margin: 1.25rem 0 0.6rem;
            letter-spacing: 0.5px;
        }
        .sidebar-label:first-of-type { margin-top: 0; }
        .sidebar-menu { display: flex; flex-direction: column; gap: 0.25rem; }
        .menu-item {
            text-decoration: none;
            color: rgba(255,255,255,0.8);
            padding: 0.7rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .menu-item svg { width: 20px; height: 20px; flex-shrink: 0; stroke: currentColor; stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .menu-item.is-active { background-color: rgba(255,255,255,0.2); color: var(--color-surface); }
        .menu-item:hover:not(.is-active) { background-color: rgba(255,255,255,0.1); color: var(--color-surface); }

        /* --- MAIN --- */
        .main-wrapper { flex: 1; min-width: 0; display: flex; flex-direction: column; overflow-y: auto; }
        .top-nav {
            background-color: var(--color-bg);
            padding: 1.5rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .top-left { display: flex; align-items: center; gap: 0.9rem; }
        .page-header { font-family: 'Manrope', sans-serif; font-size: 1.5rem; font-weight: 700; }
        .menu-toggle {
            display: none; background: white; border: none; width: 42px; height: 42px;
            border-radius: 10px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            color: var(--color-ink-soft);
        }
        .menu-toggle svg { width: 22px; height: 22px; stroke: currentColor; stroke-width: 2; fill: none; stroke-linecap: round; }

        /* DROPDOWN */
        .dropdown { position: relative; }
        .profile-btn {
            display: flex; align-items: center; gap: 0.75rem;
            background: white; padding: 0.4rem 1rem 0.4rem 0.4rem;
            border-radius: 30px; cursor: pointer; border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--color-primary-dark); color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 0.9rem; text-transform: uppercase;
        }
        .dropdown-menu {
            display: none; position: absolute; right: 0; top: 120%;
            background: white; border: 1px solid var(--color-border);
            border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            width: 200px; overflow: hidden; z-index: 100;
        }
        .dropdown-menu.show { display: block; }
        .content-area { padding: 0 2.5rem 2.5rem 2.5rem; }

        /* --- RESPONSIVE --- */
        .backdrop { display: none; }
        @media (max-width: 900px) {
            .sidebar { position: fixed; left: 0; top: 0; transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.is-open { transform: translateX(0); }
            .backdrop.is-open { display: block; position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 15; }
            .menu-toggle { display: inline-flex; align-items: center; justify-content: center; }
            .top-nav { padding: 1rem 1.2rem; }
            .content-area { padding: 0 1.2rem 1.5rem; }
            .page-header { font-size: 1.2rem; }
            .profile-btn span { display: none; }
            .profile-btn { padding: 0.4rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="backdrop" id="backdrop" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">Teman<span>Kerja</span></a>

        <div class="sidebar-label">Dashboard</div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Beranda
            </a>
        </nav>

        <div class="sidebar-label">Pengguna</div>
        <nav class="sidebar-menu">
            <a href="{{ route('pencari_kerja.index') }}" class="menu-item {{ request()->routeIs('pencari_kerja.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Pencari Kerja
            </a>
            <a href="{{ route('pemberi_kerja.index') }}" class="menu-item {{ request()->routeIs('pemberi_kerja.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                Pemberi Kerja
            </a>
            <a href="{{ route('admin.index') }}" class="menu-item {{ request()->routeIs('admin.index', 'admin.create', 'admin.edit') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Akun Admin
            </a>
        </nav>

        <div class="sidebar-label">Keahlian</div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.verifikasi-keahlian') }}" class="menu-item {{ request()->routeIs('admin.verifikasi-keahlian*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Verifikasi Keahlian
            </a>
            <a href="{{ route('keahlian.index') }}" class="menu-item {{ request()->routeIs('keahlian.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Data Keahlian
            </a>
        </nav>

        <div class="sidebar-label">Pantau Transaksi</div>
        <nav class="sidebar-menu">
            @foreach(['pekerjaan' => 'Pekerjaan', 'lamaran' => 'Lamaran', 'bukti' => 'Bukti Penyelesaian', 'rating' => 'Rating', 'notifikasi' => 'Notifikasi'] as $jenis => $label)
                <a href="{{ route('admin.transaksi.index', $jenis) }}" class="menu-item {{ request()->is('admin/transaksi/' . $jenis) ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><circle cx="3.5" cy="6" r="1"></circle><circle cx="3.5" cy="12" r="1"></circle><circle cx="3.5" cy="18" r="1"></circle></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="top-nav">
            <div class="top-left">
                <button type="button" class="menu-toggle" onclick="toggleSidebar()" aria-label="Buka menu">
                    <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <div class="page-header">@yield('title')</div>
            </div>
            <div class="dropdown">
                <button class="profile-btn" onclick="toggleDropdown('profileMenu')">
                    <div class="avatar">{{ substr(session('user_name', 'A'), 0, 1) }}</div>
                    <span style="font-weight: 600; font-size: 0.9rem; color: var(--color-ink);">{{ session('user_name', 'Admin') }}</span>
                </button>
                <div id="profileMenu" class="dropdown-menu">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 0.8rem 1.2rem; cursor: pointer; color: #EF4444; font-weight: 600; font-size: 0.9rem;">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div style="background:#DCFCE7;color:#166534;padding:.8rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:.92rem;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#FEE2E2;color:#991B1B;padding:.8rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:.92rem;">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleDropdown(id) {
            document.getElementById(id).classList.toggle('show');
        }
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('is-open');
            document.getElementById('backdrop').classList.toggle('is-open');
        }
        window.addEventListener('click', function (event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(function (m) { m.classList.remove('show'); });
            }
        });
    </script>
</body>
</html>