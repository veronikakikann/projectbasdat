<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Teman Kerja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/teman-kerja.css') }}">
    @yield('styles')
</head>
<body class="app-body">

    {{-- Bar atas (hanya tampil di layar kecil) --}}
    <div class="topbar-mobile">
        <div class="sidebar-brand">Teman<span>Kerja</span></div>
        <button type="button" class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('is-open')">Menu</button>
    </div>

    <div class="app">
        {{-- Sidebar sesuai role: isi lewat @section('role', 'pencari') --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">Teman <span>Kerja</span></div>
            @include('partials.sidebar-' . trim($__env->yieldContent('role')))

            <form action="{{ route('logout') }}" method="POST" class="sidebar-logout">
                @csrf
                <button type="submit">Keluar</button>
            </form>
        </aside>

        <main class="main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>