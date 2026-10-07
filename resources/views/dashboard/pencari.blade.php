@extends('layouts.app')

@section('title', 'Beranda')
@section('role', 'pencari')

@section('content')

    <div class="hero-card">
        <h2>Halo, <em>{{ $pencari->nama }}</em></h2>
        <p>Temukan pekerjaan harian yang cocok dengan keahlian dan lokasimu.</p>
        <a href="{{ route('pencari.cari-pekerjaan') }}" class="btn btn-accent">Cari Pekerjaan</a>
    </div>

    <div class="grid-2">
        {{-- Pekerjaan tersedia --}}
        <div class="card">
            <div class="stat-label">Pekerjaan Tersedia</div>
            <div class="stat-number">{{ $jumlahPekerjaanTersedia }}</div>
            <p>pekerjaan sedang tersedia di sistem.</p>
        </div>

        {{-- Lamaran terakhir --}}
        <div class="card">
            <div class="stat-label">Lamaran Terakhir</div>
            @if($lamaranTerakhir)
                <h3 style="margin-top:.5rem;">{{ $lamaranTerakhir->pekerjaan->nama_pekerjaan ?? '-' }}</h3>
                <p style="margin-bottom:.7rem;">Status lamaran:</p>
                @if($lamaranTerakhir->status_lamaran === 'menunggu')
                    <span class="badge badge-menunggu">Menunggu</span>
                @elseif($lamaranTerakhir->status_lamaran === 'diterima')
                    <span class="badge badge-diterima">Diterima</span>
                @else
                    <span class="badge badge-ditolak">Ditolak</span>
                @endif
            @else
                <div class="empty-state">
                    <p>Belum ada lamaran.</p>
                    <a href="{{ route('pencari.cari-pekerjaan') }}" class="btn btn-outline">Mulai Cari Pekerjaan</a>
                </div>
            @endif
        </div>
    </div>

    <h2 class="section-title">Akses Cepat</h2>
    <div class="grid-auto">
        <div class="card action-card">
            <h3>Cari Pekerjaan</h3>
            <p>Lihat pekerjaan yang cocok dengan keahlian dan lokasi kamu.</p>
            <a href="{{ route('pencari.cari-pekerjaan') }}" class="btn btn-accent">Cari Pekerjaan</a>
        </div>
        <div class="card action-card">
            <h3>Lamaran Saya</h3>
            <p>Lihat pekerjaan yang sudah kamu lamar dan statusnya.</p>
            <a href="{{ route('pencari.lamaran-saya') }}" class="btn btn-accent">Lihat Lamaran</a>
        </div>
        <div class="card action-card">
            <h3>Keahlian Saya</h3>
            <p>Ajukan keahlian dan bukti rekomendasi untuk diverifikasi Admin.</p>
            <a href="{{ route('keahlian_pencari_kerja.index') }}" class="btn btn-accent">Kelola Keahlian</a>
        </div>
        <div class="card action-card">
            <h3>Profil Saya</h3>
            <p>Lihat dan ubah data pribadi serta lokasi.</p>
            <a href="{{ route('pencari.profil') }}" class="btn btn-accent">Lihat Profil</a>
        </div>
    </div>

@endsection