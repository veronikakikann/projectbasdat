<div class="sidebar-label">Menu</div>
<nav class="sidebar-menu">
    <a href="{{ route('pencari.dashboard') }}" class="{{ request()->routeIs('pencari.dashboard') ? 'is-active' : '' }}">Beranda</a>
    <a href="{{ route('pencari.cari-pekerjaan') }}" class="{{ request()->routeIs('pencari.cari-pekerjaan', 'pekerjaan.show') ? 'is-active' : '' }}">Cari Pekerjaan</a>
    <a href="{{ route('pencari.lamaran-saya') }}" class="{{ request()->routeIs('pencari.lamaran-saya') ? 'is-active' : '' }}">Lamaran Saya</a>
    <a href="{{ route('keahlian_pencari_kerja.index') }}" class="{{ request()->routeIs('keahlian_pencari_kerja.*') ? 'is-active' : '' }}">Keahlian Saya</a>
    <a href="{{ route('notifikasi.index') }}" class="{{ request()->routeIs('notifikasi.*') ? 'is-active' : '' }}">Notifikasi</a>
    <a href="{{ route('pencari.profil') }}" class="{{ request()->routeIs('pencari.profil') ? 'is-active' : '' }}">Profil</a>
</nav>