@extends('pencari.layout')

@section('title', 'Beranda')

@section('content')
<!-- KODE CSS DI BAWAH INI 100% SAMA PLEK DENGAN PEMBERI KERJA -->
<style>
    .dashboard-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start; }
    
    /* Welcome Banner - Ukuran sama persis, hanya warna gradasi yang diganti ke Biru */
    .welcome-banner { background: linear-gradient(135deg, #55B4EA 0%, #3D91C7 100%); border-radius: 12px; padding: 2.5rem 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(85, 180, 234, 0.2); }
    .welcome-title { font-family: 'Manrope', sans-serif; font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; }
    .welcome-desc { font-size: 1rem; opacity: 0.9; line-height: 1.5; margin-bottom: 1.5rem; max-width: 80%; }
    
    .btn-yellow { background-color: #FEF08A; color: #854D0E; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.2s; border: none; }
    .btn-yellow:hover { background-color: #FDE047; transform: translateY(-2px); }

    /* Header text untuk section */
    .section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem; }
    .section-title { font-family: 'Manrope', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--color-ink); }
    .section-link { font-size: 0.85rem; font-weight: 600; color: var(--color-primary); text-decoration: none; }
    .section-link:hover { text-decoration: underline; }

    /* Grid Statistik */
    .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .stat-card { background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center; }
    .stat-num { font-size: 2.5rem; font-weight: 800; color: var(--color-ink); line-height: 1; margin-bottom: 0.5rem; }
    .stat-label { font-size: 0.9rem; color: var(--color-ink-soft); font-weight: 600; }

    /* Sidebar List */
    .side-card { background: white; border-radius: 12px; border: 1px solid var(--color-border); padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
    .side-list { display: flex; flex-direction: column; gap: 0.5rem; }
    
    .list-item { display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 8px; transition: 0.2s; text-decoration: none; border: 1px solid transparent; }
    .list-item:hover { background: #F8FAFC; border-color: var(--color-border); }
    
    .item-icon { width: 40px; height: 40px; border-radius: 50%; background: #E0F2FE; color: var(--color-primary-dark); font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; text-transform: uppercase; }
    .item-title { font-weight: 700; color: var(--color-ink); font-size: 0.95rem; margin-bottom: 0.2rem; }
    .item-desc { font-size: 0.8rem; color: var(--color-ink-soft); }
    
    /* Badge dimodifikasi warnanya agar netral untuk status lamaran */
    .badge-status { background: #E2E8F0; color: #475569; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 0.7rem; margin-left: 6px; text-transform: capitalize; }
</style>

<div class="dashboard-layout">
    
    <!-- Sisi Kiri (Utama) -->
    <div>
        <div class="welcome-banner">
            <h1 class="welcome-title">Hai, {{ $pencari->nama ?? session('user_name', 'Pekerja') }}!</h1>
            <p class="welcome-desc">Siap untuk mencari cuan hari ini? Temukan berbagai lowongan pekerjaan yang cocok dengan keahlianmu dan mulai bekerja.</p>
            <a href="#" class="btn-yellow">Cari Lowongan Sekarang</a>
        </div>

        <div class="section-header">
            <h2 class="section-title">Ringkasan Peluang</h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num">{{ $jumlahPekerjaanTersedia }}</div>
                <div class="stat-label">Lowongan Tersedia</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">0</div>
                <div class="stat-label">Total Lamaran Terkirim</div>
            </div>
        </div>
    </div>

    <!-- Sisi Kanan (Sidebar) -->
    <div>
        <div class="side-card">
            <div class="section-header" style="margin-bottom: 1.5rem;">
                <h2 class="section-title" style="font-size: 1.1rem;">Lamaran Terakhir</h2>
                <a href="#" class="section-link">Semua</a>
            </div>
            
            <div class="side-list">
                @if($lamaranTerakhir)
                    <a href="#" class="list-item">
                        <div class="item-icon">{{ substr($lamaranTerakhir->pekerjaan->nama_pekerjaan ?? 'P', 0, 1) }}</div>
                        <div>
                            <div class="item-title">{{ $lamaranTerakhir->pekerjaan->nama_pekerjaan ?? 'Pekerjaan' }}</div>
                            <div class="item-desc">
                                {{ \Carbon\Carbon::parse($lamaranTerakhir->tanggal_submit)->locale('id')->diffForHumans() }}
                                <span class="badge-status">{{ str_replace('_', ' ', $lamaranTerakhir->status_lamaran) }}</span>
                            </div>
                        </div>
                    </a>
                @else
                    <div style="text-align: center; padding: 2rem 0; color: var(--color-ink-soft);">
                        <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 0.5rem; opacity: 0.5;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <div style="font-size: 0.9rem;">Belum ada lamaran.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection