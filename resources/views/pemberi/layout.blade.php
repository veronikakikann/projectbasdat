<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Teman Kerja</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #55B4EA;
            --color-primary-dark: #3D91C7;
            --color-accent: #F5FF00;
            --color-secondary: #FF8A5B; /* Oranye/Coral */
            --color-ink: #171717;
            --color-ink-soft: #718096;
            --color-surface: #FFFFFF;
            --color-bg: #F4F7FE; /* Abu-abu kebiruan muda */
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

        /* --- SIDEBAR BIRU --- */
        .sidebar {
            width: 260px;
            background-color: var(--color-primary);
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
            height: 100%;
            box-shadow: 4px 0 15px rgba(0,0,0,0.03);
            z-index: 20;
        }

        .brand {
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-surface);
            text-decoration: none;
            margin-bottom: 2.5rem;
            display: block;
        }

        .brand span { color: var(--color-accent); }

        .sidebar-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.6);
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .menu-item {
            text-decoration: none;
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .menu-item svg {
            width: 20px; height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round; stroke-linejoin: round;
        }

        .menu-item.is-active {
            background-color: rgba(255,255,255,0.2);
            color: var(--color-surface);
        }

        .menu-item:hover:not(.is-active) {
            background-color: rgba(255,255,255,0.1);
            color: var(--color-surface);
        }

        /* --- SUPPORT CARD --- */
        .support-card {
            background-color: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            margin-top: auto;
            color: white;
        }

        .support-card h4 { font-size: 0.95rem; margin-bottom: 0.25rem; }
        .support-card p { font-size: 0.8rem; color: rgba(255,255,255,0.8); margin-bottom: 1rem; }
        .support-btn {
            background-color: var(--color-surface);
            color: var(--color-primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            display: block;
            transition: 0.2s;
        }
        .support-btn:hover { background-color: #F8FAFC; }

        /* --- MAIN WRAPPER --- */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* --- TOP NAV --- */
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

        .page-header {
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-actions { display: flex; align-items: center; gap: 1rem; }

        /* DROPDOWN LOGIC */
        .dropdown { position: relative; }
        
        .icon-btn {
            background: white; border: none;
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--color-ink-soft);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            position: relative;
        }

        .icon-btn svg { width: 20px; height: 20px; stroke: currentColor; stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }

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
            font-weight: bold; font-size: 0.9rem;
        }

        .dropdown-menu {
            display: none; position: absolute; right: 0; top: 120%;
            background: white; border: 1px solid var(--color-border);
            border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            width: 220px; overflow: hidden; z-index: 100;
        }

        .dropdown-menu.show { display: block; }
        
        .dropdown-item {
            display: block; padding: 0.8rem 1.2rem;
            color: var(--color-ink); text-decoration: none;
            font-size: 0.9rem; font-weight: 500;
            border-bottom: 1px solid var(--color-border);
        }
        .dropdown-item:hover { background-color: var(--color-bg); }

        .content-area { padding: 0 2.5rem 2.5rem 2.5rem; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="/" class="brand">Teman<span>Kerja</span></a>
        
        <div class="sidebar-label">Dashboard</div>
        <nav class="sidebar-menu">
            <a href="{{ route('pemberi.dashboard') }}" class="menu-item {{ request()->routeIs('pemberi.dashboard') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Beranda
            </a>
            <a href="{{ route('pemberi.pekerjaan.create') }}" class="menu-item {{ request()->routeIs('pemberi.pekerjaan.create') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Buat Lowongan
            </a>
            <a href="{{ route('pemberi.pekerjaan.index') }}" class="menu-item {{ request()->routeIs('pemberi.pekerjaan.index', 'pemberi.pekerjaan.show') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Lowongan Saya
            </a>
            <a href="{{ route('pemberi.lamaran.index') }}" class="menu-item {{ request()->routeIs('pemberi.lamaran.*', 'pemberi.pelamar.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Semua Pelamar
            </a>
        </nav>

        <div class="support-card">
            <h4>Pusat Bantuan</h4>
            <p>Hubungi kami jika ada kendala.</p>
            <a href="/contact" class="support-btn">Chat Admin</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="top-nav">
            <div class="page-header">@yield('title')</div>
            
            <div class="nav-actions">
                <!-- Dropdown Notifikasi -->
                <div class="dropdown">
                    <button class="icon-btn" onclick="toggleDropdown('notifMenu')">
                        <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span style="position: absolute; top: 0; right: 0; background: #EF4444; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white;"></span>
                    </button>
                    <div id="notifMenu" class="dropdown-menu">
                        <a href="{{ route('pemberi.notifikasi.index') }}" class="dropdown-item">Lihat Semua Notifikasi</a>
                    </div>
                </div>
                
                <!-- Dropdown Profil -->
                <div class="dropdown">
                    <button class="profile-btn" onclick="toggleDropdown('profileMenu')">
                        <div class="avatar">{{ substr(session('user_name', 'U'), 0, 1) }}</div>
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--color-ink);">{{ session('user_name', 'Pengguna') }}</span>
                    </button>
                    <div id="profileMenu" class="dropdown-menu">
                        <a href="{{ route('pemberi.profil.show') }}" class="dropdown-item">Profil Saya</a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 0.8rem 1.2rem; cursor: pointer; color: #EF4444; font-weight: 600; font-size: 0.9rem;">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Script Sederhana untuk Dropdown Klik -->
    <script>
        function toggleDropdown(id) {
            // Tutup semua dropdown dulu
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu.id !== id) menu.classList.remove('show');
            });
            // Buka dropdown yang diklik
            document.getElementById(id).classList.toggle('show');
        }

        // Tutup dropdown jika klik di luar area
        window.onclick = function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        }
    </script>
</body>
</html>