@extends('pemberi.layout')

@section('title', 'Detail Lowongan')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .job-title-large {
        font-family: 'Manrope', sans-serif;
        font-size: 1.8rem;
        color: var(--color-ink);
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-tersedia { background: #EBF8FF; color: #3182CE; }
    .status-penuh { background: #FEF08A; color: #854D0E; }
    .status-sedang_dikerjakan { background: #FFF5F5; color: #C53030; }
    .status-selesai { background: #F0FFF4; color: #2F855A; }

    .btn-outline {
        border: 1px solid var(--color-border);
        background: white;
        color: var(--color-ink);
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.2s;
    }
    .btn-outline:hover { background: #F8FAFC; }

    .btn-primary {
        background-color: var(--color-primary);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.2s;
    }
    .btn-primary:hover { background-color: var(--color-primary-dark); }
    
    .btn-success { background: #10B981; color: white; border-color: #10B981; }
    .btn-success:hover { background: #059669; }

    .grid-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-ink);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-list li {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--color-border);
        font-size: 0.95rem;
    }
    .info-list li:last-child { border-bottom: none; }
    .info-label { color: var(--color-ink-soft); }
    .info-value { font-weight: 600; color: var(--color-ink); text-align: right; }

    .content-text {
        color: var(--color-ink);
        line-height: 1.6;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        white-space: pre-line;
    }

    /* Tabel Pekerja */
    .worker-table { width: 100%; border-collapse: collapse; }
    .worker-table th { text-align: left; padding: 1rem; font-size: 0.85rem; color: var(--color-ink-soft); background: #F8FAFC; border-bottom: 1px solid var(--color-border); }
    .worker-table td { padding: 1rem; font-size: 0.95rem; border-bottom: 1px solid var(--color-border); vertical-align: middle; }
</style>

<!-- Header & Actions -->
<div class="page-header">
    <div>
        <h1 class="job-title-large">{{ $pekerjaan->nama_pekerjaan }}</h1>
        <span class="status-badge status-{{ strtolower($pekerjaan->status_pekerjaan) }}">
            Status: {{ str_replace('_', ' ', $pekerjaan->status_pekerjaan) }}
        </span>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        @if($pekerjaan->status_pekerjaan === 'tersedia')
            <a href="{{ route('pemberi.pekerjaan.edit', $pekerjaan->id_pekerjaan) }}" class="btn-outline">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Lowongan
            </a>
        @endif
        
        <!-- Tombol Pembayaran muncul saat pekerjaan dimulai atau selesai -->
        @if(in_array($pekerjaan->status_pekerjaan, ['sedang_dikerjakan', 'selesai']))
            <a href="{{ route('pemberi.bukti.create', $pekerjaan->id_pekerjaan) }}" class="btn-outline btn-success">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                Selesaikan Pembayaran
            </a>
        @endif

        <a href="{{ route('pemberi.pelamar.index', $pekerjaan->id_pekerjaan) }}" class="btn-primary">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Lihat Pelamar ({{ $jumlahPelamar }})
        </a>
    </div>
</div>

<!-- Menampilkan pesan alert dari session -->
@if (session('success'))
    <div style="background: #C6F6D5; border: 1px solid #48BB78; color: #2F855A; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">✅ {{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="background: #FED7D7; border: 1px solid #EF4444; color: #C53030; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">⚠️ {{ session('error') }}</div>
@endif

<div class="grid-container">
    <!-- Kolom Kiri: Deskripsi & Syarat -->
    <div class="card">
        <h3 class="section-title">Deskripsi Pekerjaan</h3>
        <div class="content-text">{{ $pekerjaan->deskripsi }}</div>

        <h3 class="section-title">Persyaratan</h3>
        <div class="content-text">{{ $pekerjaan->persyaratan ?? 'Tidak ada persyaratan khusus.' }}</div>
        
        <h3 class="section-title">Lokasi</h3>
        <div class="content-text">{{ $pekerjaan->lokasi }}</div>
    </div>

    <!-- Kolom Kanan: Ringkasan -->
    <div class="card" style="height: fit-content;">
        <h3 class="section-title">Ringkasan Pekerjaan</h3>
        <ul class="info-list">
            <li>
                <span class="info-label">Kategori</span>
                <span class="info-value">{{ $pekerjaan->keahlian->nama_keahlian ?? '-' }}</span>
            </li>
            <li>
                <span class="info-label">Upah / Gaji</span>
                <span class="info-value" style="color: var(--color-primary);">Rp {{ number_format($pekerjaan->upah, 0, ',', '.') }}</span>
            </li>
            <li>
                <span class="info-label">Tanggal Kerja</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($pekerjaan->tanggal_pengerjaan)->locale('id')->translatedFormat('d F Y') }}</span>
            </li>
            <li>
                <span class="info-label">Kebutuhan</span>
                <span class="info-value">{{ $pekerjaan->jumlah_pekerja }} Orang</span>
            </li>
            <li>
                <span class="info-label">Diposting</span>
                <span class="info-value">
                    {{ 
                        \Carbon\Carbon::parse($pekerjaan->tanggal_posting)->isToday() 
                        ? \Carbon\Carbon::parse($pekerjaan->tanggal_posting)->locale('id')->diffForHumans() 
                        : \Carbon\Carbon::parse($pekerjaan->tanggal_posting)->locale('id')->translatedFormat('d F Y, H:i') 
                    }}
                </span>
            </li>
        </ul>
    </div>
</div>

<!-- Bagian Bawah: Pekerja yang Diterima -->
<div class="card">
    <h3 class="section-title">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        Pekerja yang Diterima
    </h3>
    
    @if($pekerja->isEmpty())
        <div style="text-align: center; padding: 2rem 0; color: var(--color-ink-soft);">
            <p>Belum ada pelamar yang diterima untuk pekerjaan ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="worker-table">
                <thead>
                    <tr>
                        <th>Nama Pekerja</th>
                        <th>Status Lamaran</th>
                        <th>Bukti Penyelesaian</th>
                        <th>Rating Anda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pekerja as $pkrj)
                    <tr>
                        <td style="font-weight: 600;">{{ $pkrj->pencariKerja->nama ?? 'Nama tidak ditemukan' }}</td>
                        <td>
                            <span style="background: #E2E8F0; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: capitalize;">{{ $pkrj->status_lamaran }}</span>
                        </td>
                        <td>
                            @if($pkrj->buktiPenyelesaian && $pkrj->buktiPenyelesaian->foto_bukti_kerja)
                                <span style="color: #2F855A; font-weight: 600;">✅ Ada Bukti</span>
                            @else
                                <span style="color: var(--color-ink-soft);">Belum ada bukti</span>
                            @endif
                        </td>
                        <td>
                            <!-- Mengecek apakah status sudah selesai baru bisa diberi rating -->
                            @if($pkrj->status_lamaran === 'selesai')
                                @if(isset($ratingKu[$pkrj->id_lamaran]))
                                    <a href="{{ route('pemberi.rating.form', $pkrj->id_lamaran) }}" style="text-decoration: none; color: #F59E0B; font-weight: 700;">
                                        ⭐️ {{ $ratingKu[$pkrj->id_lamaran]->skor }} / 5
                                    </a>
                                @else
                                    <a href="{{ route('pemberi.rating.form', $pkrj->id_lamaran) }}" style="color: var(--color-primary); font-size: 0.85rem; font-weight: 600; text-decoration: none;">Beri Rating</a>
                                @endif
                            @else
                                <span style="color: var(--color-ink-soft); font-size: 0.85rem;">Selesaikan pembayaran dahulu</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection